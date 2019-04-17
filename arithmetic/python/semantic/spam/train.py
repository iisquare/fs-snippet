import datetime
import os
import time

import tensorflow as tf
import numpy as np
from spam import config
from word2vec import service as word2vec
from spam import helper
from spam.rnn import RNN


def flags():
    # Parameters
    # ==================================================

    # Data loading params
    tf.flags.DEFINE_float("dev_sample_percentage", .1, "Percentage of the training data to use for validation")

    # Model Hyperparameters
    tf.flags.DEFINE_string("cell_type", "vanilla",
                           "Type of rnn cell. Choose 'vanilla' or 'lstm' or 'gru' (Default: vanilla)")
    tf.flags.DEFINE_integer("hidden_size", 128, "Dimensionality of character embedding (Default: 128)")
    tf.flags.DEFINE_float("dropout_keep_prob", 0.5, "Dropout keep probability (Default: 0.5)")
    tf.flags.DEFINE_float("l2_reg_lambda", 3.0, "L2 regularization lambda (Default: 3.0)")

    # Training parameters
    tf.flags.DEFINE_integer("batch_size", 64, "Batch Size (Default: 64)")
    tf.flags.DEFINE_integer("num_epochs", 100, "Number of training epochs (Default: 100)")
    tf.flags.DEFINE_integer("display_every", 10, "Number of iterations to display training info.")
    tf.flags.DEFINE_integer("evaluate_every", 100, "Evaluate model on dev set after this many steps")
    tf.flags.DEFINE_integer("checkpoint_every", 100, "Save model after this many steps")
    tf.flags.DEFINE_integer("num_checkpoints", 5, "Number of checkpoints to store")
    tf.flags.DEFINE_float("learning_rate", 1e-3, "Which learning rate to start with. (Default: 1e-3)")


    FLAGS = tf.flags.FLAGS
    FLAGS.flag_values_dict()
    print("\nParameters:")
    for attr, value in sorted(FLAGS.__flags.items()):
        print("{} = {}".format(attr.upper(), value))
    print("")
    return FLAGS

def train(FLAGS):
    with tf.device(config.tf_device):
        x_text, y = helper.load_data(config.corpus_path, config.labels)

    # 在RNN中会通过tf.nn.embedding_lookup将位置转换为词向量
    x = np.array(word2vec.position(x_text, config.max_sentence_length))

    print("Text Vocabulary Size: {:d}".format(len(word2vec.dictionary)))
    print("x = {0}".format(x.shape))
    print("y = {0}".format(y.shape))
    print("")

    # Randomly shuffle data
    np.random.seed(10)
    shuffle_indices = np.random.permutation(np.arange(len(y)))
    x_shuffled = x[shuffle_indices]
    y_shuffled = y[shuffle_indices]
    # 减少数据集，训练数据太多会导致如下异常，暂未解决
    # tensorflow.python.framework.errors_impl.InvalidArgumentError: indices[0] = -1 is not in [0, 6400)
    x_shuffled = x_shuffled[0:5000]
    y_shuffled = y_shuffled[0:5000]

    # Split train/test set
    # TODO: This is very crude, should use cross-validation
    dev_sample_index = -1 * int(FLAGS.dev_sample_percentage * float(len(y)))
    x_train, x_dev = x_shuffled[:dev_sample_index], x_shuffled[dev_sample_index:]
    y_train, y_dev = y_shuffled[:dev_sample_index], y_shuffled[dev_sample_index:]
    print("Train/Dev split: {:d}/{:d}\n".format(len(y_train), len(y_dev)))

    with tf.Graph().as_default():
        session_conf = tf.ConfigProto(
            allow_soft_placement=config.allow_soft_placement,
            log_device_placement=config.log_device_placement)
        sess = tf.Session(config=session_conf)
        with sess.as_default():
            rnn = RNN(
                sequence_length=x_train.shape[1],
                num_classes=y_train.shape[1],
                vocab_size=len(word2vec.dictionary),
                embedding_size=word2vec.embeddings.shape[1],
                cell_type=FLAGS.cell_type,
                hidden_size=FLAGS.hidden_size,
                l2_reg_lambda=FLAGS.l2_reg_lambda
            )

            # Define Training procedure
            global_step = tf.Variable(0, name="global_step", trainable=False)
            train_op = tf.train.AdamOptimizer(FLAGS.learning_rate).minimize(rnn.loss, global_step=global_step)

            # Output directory for models and summaries
            timestamp = str(int(time.time()))
            out_dir = os.path.abspath(os.path.join(config.board_path, timestamp))
            print("Writing to {}\n".format(out_dir))

            # Summaries for loss and accuracy
            loss_summary = tf.summary.scalar("loss", rnn.loss)
            acc_summary = tf.summary.scalar("accuracy", rnn.accuracy)

            # Train Summaries
            train_summary_op = tf.summary.merge([loss_summary, acc_summary])
            train_summary_dir = os.path.join(out_dir, "summaries", "train")
            train_summary_writer = tf.summary.FileWriter(train_summary_dir, sess.graph)

            # Dev summaries
            dev_summary_op = tf.summary.merge([loss_summary, acc_summary])
            dev_summary_dir = os.path.join(out_dir, "summaries", "dev")
            dev_summary_writer = tf.summary.FileWriter(dev_summary_dir, sess.graph)

            # Checkpoint directory. Tensorflow assumes this directory already exists so we need to create it
            checkpoint_dir = os.path.abspath(config.model_dir)
            checkpoint_prefix = os.path.join(checkpoint_dir, "ckpt")
            if not os.path.exists(checkpoint_dir):
                os.makedirs(checkpoint_dir)
            saver = tf.train.Saver(tf.global_variables(), max_to_keep=FLAGS.num_checkpoints)

            # Initialize all variables
            sess.run(tf.global_variables_initializer())

            # Pre-trained word2vec
            sess.run(rnn.W_text.assign(word2vec.embeddings))
            print("Success to load pre-trained word2vec model!\n")

            # Generate batches
            batches = helper.batch_iter(
                list(zip(x_train, y_train)), FLAGS.batch_size, FLAGS.num_epochs)
            # Training loop. For each batch...
            for batch in batches:
                x_batch, y_batch = zip(*batch)
                # Train
                feed_dict = {
                    rnn.input_text: x_batch,
                    rnn.input_y: y_batch,
                    rnn.dropout_keep_prob: FLAGS.dropout_keep_prob
                }
                _, step, summaries, loss, accuracy = sess.run(
                    [train_op, global_step, train_summary_op, rnn.loss, rnn.accuracy], feed_dict)
                train_summary_writer.add_summary(summaries, step)

                # Training log display
                if step % FLAGS.display_every == 0:
                    time_str = datetime.datetime.now().isoformat()
                    print("{}: step {}, loss {:g}, acc {:g}".format(time_str, step, loss, accuracy))

                # Evaluation
                if step % FLAGS.evaluate_every == 0:
                    print("\nEvaluation:")
                    feed_dict_dev = {
                        rnn.input_text: x_dev,
                        rnn.input_y: y_dev,
                        rnn.dropout_keep_prob: 1.0
                    }
                    summaries_dev, loss, accuracy = sess.run(
                        [dev_summary_op, rnn.loss, rnn.accuracy], feed_dict_dev)
                    dev_summary_writer.add_summary(summaries_dev, step)

                    time_str = datetime.datetime.now().isoformat()
                    print("{}: step {}, loss {:g}, acc {:g}\n".format(time_str, step, loss, accuracy))

                # Model checkpoint
                if step % FLAGS.checkpoint_every == 0:
                    path = saver.save(sess, checkpoint_prefix, global_step=step)
                    print("Saved model checkpoint to {}\n".format(path))


def main(_):
    FLAGS = flags()
    train(FLAGS)


if __name__ == "__main__":
    print('run main')
    tf.app.run()
