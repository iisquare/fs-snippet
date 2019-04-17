import numpy as np
import tensorflow as tf
from spam import config
from word2vec import service as word2vec


checkpoint_file = tf.train.latest_checkpoint(config.model_dir)


def predict(participles_list):
    x_batch = np.array(word2vec.position(participles_list, config.max_sentence_length))
    graph = tf.Graph()
    with graph.as_default():
        session_conf = tf.ConfigProto(
            allow_soft_placement=config.allow_soft_placement,
            log_device_placement=config.log_device_placement)
        sess = tf.Session(config=session_conf)
        with sess.as_default():
            # Load the saved meta graph and restore variables
            saver = tf.train.import_meta_graph("{}.meta".format(checkpoint_file))
            saver.restore(sess, checkpoint_file)

            # Get the placeholders from the graph by name
            input_text = graph.get_operation_by_name("input_text").outputs[0]
            # input_y = graph.get_operation_by_name("input_y").outputs[0]
            dropout_keep_prob = graph.get_operation_by_name("dropout_keep_prob").outputs[0]

            # Tensors we want to evaluate
            predictions = graph.get_operation_by_name("output/predictions").outputs[0]

            # Collect the predictions here
            return sess.run(predictions, {input_text: x_batch, dropout_keep_prob: dropout_keep_prob})
