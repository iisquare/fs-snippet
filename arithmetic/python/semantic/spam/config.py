# 数据目录路径
data_dir = '../data'
# 日志目录路径
logs_dir = '../logs'
# 模型存储目录
model_dir = data_dir + '/model/spam'
# TensorBoard存储路径
board_path = logs_dir + '/spam'
# 语料库文件地址，内容格式为：分类 分词1 分词2 ... 分词N
corpus_path = data_dir + '/corpus.txt'
# 模型共享目录
share_dir = data_dir + '/model/spam/share'
# 字典生成路径
vocabulary_path = share_dir + '/vocabulary.json'
# 训练标签，不在列表中的分类将被忽略
labels = ['deny', 'normal']
tf_device = '/cpu:0'
# 语句最大单词个数
max_sentence_length = 100
# Allow device soft device placement
allow_soft_placement = True
# Log placement of ops on devices
log_device_placement = False
