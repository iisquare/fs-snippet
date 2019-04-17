import unittest
from word2vec import service as word2vec
import numpy as np


class Tester(unittest.TestCase):

    def test_predict(self):
        result = word2vec.predict([['你好', '世界']], 100)
        print(len(word2vec.dictionary), len(word2vec.embeddings), np.array(result).shape)
        print(result[0][0])
        print(result[0][-1])


if __name__ == '__main__':
    unittest.main()