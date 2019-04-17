import numpy as np

def load_data(corpus_path, labels):
    x_text = [] # x:ndarray(记录条数, 分词列表)[['分词1', '分词2']]
    y = [] # y:ndarray(记录条数, 分类)[[0 1], [1, 0]]
    yi_size = len(labels)
    labels = dict(zip(labels, np.arange(0, yi_size, dtype=np.int)))
    # 内容格式为：分类 分词1 分词2 ... 分词N
    with open(corpus_path, 'r', encoding='UTF-8') as file:
        line = file.readline()
        while line:
            participles = line.replace('\n', '').split(' ')
            label = participles[0]
            corpus = participles[1:]
            if label in labels and len(''.join(corpus).replace(' ', '')) > 0:
                x_text.append(corpus)
                yi_vec = np.zeros((yi_size), dtype=np.int)
                yi_vec[labels[label]] = 1
                y.append(yi_vec)
            line = file.readline()
    return x_text, np.array(y)


def batch_iter(data, batch_size, num_epochs, shuffle=True):
    """
    Generates a batch iterator for a dataset.
    """
    data = np.array(data)
    data_size = len(data)
    num_batches_per_epoch = int((len(data) - 1) / batch_size) + 1
    for epoch in range(num_epochs):
        # Shuffle the data at each epoch
        if shuffle:
            shuffle_indices = np.random.permutation(np.arange(data_size))
            shuffled_data = data[shuffle_indices]
        else:
            shuffled_data = data
        for batch_num in range(num_batches_per_epoch):
            start_index = batch_num * batch_size
            end_index = min((batch_num + 1) * batch_size, data_size)
            yield shuffled_data[start_index:end_index]
