import math
import os
import pickle
import time

from word2vec import helper
from word2vec import config
import tensorflow as tf
from six.moves import xrange
from word2vec import graphic

if __name__ == "__main__":
    print("retrain word2vec start")
    words = helper.load_words(config.corpus_path)
    print('Data size', len(words))
    data, count, dictionary, reverse_dictionary = helper.build_dataset(words, config.vocabulary_size)
    del words  # 删除words节省内存
    print('Most common words (+UNK)', count[:5])
    print('Sample data', data[:10], [reverse_dictionary[i] for i in data[:10]])
    # 预览批量生成的前8条数据
    _, batch, labels = helper.generate_batch(0, data, 8, config.num_skips, config.skip_window)
    for i in range(8):
        print(batch[i], reverse_dictionary[batch[i]], '->', labels[i, 0], reverse_dictionary[labels[i, 0]])

    valid_examples = [dictionary[li] for li in config.valid_word]

    # 构建神经网络结构
    graph = tf.Graph()
    with graph.as_default():
        # Input data.
        train_inputs = tf.placeholder(tf.int32, shape=[config.batch_size], name='train_inputs')
        train_labels = tf.placeholder(tf.int32, shape=[config.batch_size, 1], name='train_labels')
        valid_dataset = tf.constant(valid_examples, dtype=tf.int32, name='valid_dataset')

        # Ops and variables pinned to the CPU because of missing GPU implementation
        with tf.device(config.tf_device):
            # Look up embeddings for inputs.
            embeddings = tf.Variable(
                tf.random_uniform([config.vocabulary_size, config.embedding_size], -1.0, 1.0), name='embeddings')
            embed = tf.nn.embedding_lookup(embeddings, train_inputs)

            # Construct the variables for the NCE loss
            nce_weights = tf.Variable(
                tf.truncated_normal([config.vocabulary_size, config.embedding_size],
                                    stddev=1.0 / math.sqrt(config.embedding_size)), name='nce_weights')
            nce_biases = tf.Variable(tf.zeros([config.vocabulary_size]), dtype=tf.float32, name='nce_biases')

        # Compute the average NCE loss for the batch.
        # tf.nce_loss automatically draws a new sample of the negative labels each
        # time we evaluate the loss.
        loss = tf.reduce_mean(tf.nn.nce_loss(
            name='nce_loss',
            weights=nce_weights,
            biases=nce_biases,
            inputs=embed,
            labels=train_labels,
            num_sampled=config.num_sampled,
            num_classes=config.vocabulary_size), name='loss_reduce_mean')

        # Construct the SGD optimizer using a learning rate of 1.0.
        optimizer = tf.train.GradientDescentOptimizer(1.0).minimize(loss)

        # Compute the cosine similarity between minibatch examples and all embeddings.
        norm = tf.sqrt(tf.reduce_sum(tf.square(embeddings), 1, keepdims=True))
        normalized_embeddings = embeddings / norm
        valid_embeddings = tf.nn.embedding_lookup(normalized_embeddings, valid_dataset)
        similarity = tf.matmul(valid_embeddings, normalized_embeddings, transpose_b=True, name='similarity')

        # Add variable initializer.
        init = tf.global_variables_initializer()

    # Step 5: Begin training.
    if not os.path.exists(config.model_dir):
        os.makedirs(config.model_dir)
    timestamp = str(int(time.time()))
    out_dir = os.path.abspath(os.path.join(config.board_path, timestamp))
    ckpt_path = os.path.abspath(os.path.join(config.model_dir, 'ckpt'))
    with tf.Session(graph=graph) as session:
        saver = tf.train.Saver()
        summary_writer = tf.summary.FileWriter(out_dir, graph=tf.get_default_graph())
        # We must initialize all variables before we use them.
        init.run()
        print("Initialized")
        average_loss = 0
        data_index = 0
        for step in xrange(config.num_steps):
            data_index, batch_inputs, batch_labels = helper.generate_batch(data_index, data, config.batch_size, config.num_skips, config.skip_window)
            feed_dict = {train_inputs: batch_inputs, train_labels: batch_labels}

            # We perform one update step by evaluating the optimizer op (including it
            # in the list of returned values for session.run()
            _, loss_val = session.run([optimizer, loss], feed_dict=feed_dict)
            average_loss += loss_val

            if step % 2000 == 0:
                if step > 0:
                    average_loss /= 2000
                # The average loss is an estimate of the loss over the last 2000 batches.
                print("Average loss at step ", step, ": ", average_loss)
                average_loss = 0

            # Note that this is expensive (~20% slowdown if computed every 500 steps)
            if step % 10000 == 0:
                sim = similarity.eval()
                for i in xrange(config.valid_size):
                    valid_word = reverse_dictionary[valid_examples[i]]
                    top_k = 8  # number of nearest neighbors
                    nearest = (-sim[i, :]).argsort()[:top_k]
                    log_str = "Nearest to %s:" % valid_word
                    for k in xrange(top_k):
                        close_word = reverse_dictionary[nearest[k]]
                        log_str = "%s %s," % (log_str, close_word)
                    print(log_str)
        final_embeddings = normalized_embeddings.eval()
        saver.save(session, ckpt_path)

    print('draw participle image...')
    graphic.draw(reverse_dictionary, final_embeddings)
    with open(config.vocabulary_path, 'wb') as file:
        pickle.dump([dictionary, final_embeddings], file)
    print("retrain word2vec done")
