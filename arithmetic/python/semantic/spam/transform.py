import json
import shutil
import unittest
import tensorflow as tf

from spam import config
from word2vec import service as word2vec


class Tester(unittest.TestCase):

    def test_share(self):
        shutil.rmtree(config.share_dir)
        self.test_model()
        self.test_vocabulary()

    """
    保存词典配置
    """
    def test_vocabulary(self):
        print('write vocabulary data...')
        with open(config.vocabulary_path, 'w') as file:
            file.writelines([json.dumps({
                'dictionary': word2vec.dictionary,
                # 'embeddings': word2vec.embeddings.tolist(),
                'labels': config.labels,
                'maxSentenceLength': config.max_sentence_length
            })])
        print('done')

    """
    将模型转换为其他语言可调用的PB文件
    Java:
    try (SavedModelBundle b = SavedModelBundle.load("/tmp/mymodel", "serve")) {
      // b.session().run(...)
    }
    """
    def test_model(self):
        print('restore...')
        checkpoint_file = tf.train.latest_checkpoint(config.model_dir)
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
                print('convert...')
                input_text = graph.get_operation_by_name("input_text").outputs[0]
                dropout_keep_prob = graph.get_operation_by_name("dropout_keep_prob").outputs[0]
                predictions = graph.get_operation_by_name("output/predictions").outputs[0]
                signature = tf.saved_model.signature_def_utils.build_signature_def(
                    inputs={
                        'input_text': tf.saved_model.utils.build_tensor_info(input_text),
                        'dropout_keep_prob': tf.saved_model.utils.build_tensor_info(dropout_keep_prob)
                    },
                    outputs={'predictions': tf.saved_model.utils.build_tensor_info(predictions)},
                )
                builder = tf.saved_model.builder.SavedModelBuilder(config.share_dir)
                builder.add_meta_graph_and_variables(sess,
                       [tf.saved_model.tag_constants.SERVING],
                       signature_def_map={
                           tf.saved_model.signature_constants.DEFAULT_SERVING_SIGNATURE_DEF_KEY: signature})
                builder.save()

    print('done')


if __name__ == '__main__':
    unittest.main()
