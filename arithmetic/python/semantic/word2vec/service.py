import pickle

from word2vec import config
import numpy as np


# 获取语句分词在词典中的位置
def position(participles_list, max_sentence_length = None):
    results = []
    for participles in participles_list:
        # 如果设置了数组最大长度就采用最大值，否则保持原数组大小
        sentence_length = max_sentence_length if (max_sentence_length is not None) else len(participles)
        # 采用位置0作为PAD值
        result = [0] * sentence_length
        # 使用[设置的最大值、数组长度]中的最小值进行循环
        size = len(participles) if (max_sentence_length is None) else min(len(participles), max_sentence_length)
        for index in range(size):
            text = participles[index]
            if text not in dictionary:
                text = 'UNK'
            result[index] = dictionary[text]
        results.append(result)
    return results


# 获取分词语句的词向量
def predict(participles_list, max_sentence_length = None):
    results = []
    for participles in participles_list:
        # 如果设置了数组最大长度就采用最大值，否则保持原数组大小
        sentence_length = max_sentence_length if (max_sentence_length is not None) else len(participles)
        # 采用全[float(0.0) * dim]向量作为PAD值
        result = [np.zeros((embeddings.shape[1]), dtype=np.float32)] * sentence_length
        # 使用[设置的最大值、数组长度]中的最小值进行循环
        size = len(participles) if (max_sentence_length is None) else min(len(participles), max_sentence_length)
        for index in range(size):
            text = participles[index]
            if text not in dictionary:
                text = 'UNK'
            result[index] = embeddings[dictionary[text]]
        results.append(result)
    return results


print('reload dictionary from ' + config.vocabulary_path + '...')
with open(config.vocabulary_path, 'rb') as file:
    dictionary, embeddings = pickle.load(file)
print(type(dictionary), type(embeddings))
print('reload done\n')

