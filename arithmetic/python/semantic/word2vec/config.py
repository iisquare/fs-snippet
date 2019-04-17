# 数据目录路径
data_dir = '../data'
# 日志目录路径
logs_dir = '../logs'
# 模型存储目录
model_dir = data_dir + '/model/corpus'
# TensorBoard存储路径
board_path = logs_dir + '/corpus'
# 字典生成路径
vocabulary_path = model_dir + '/vocabulary.pkl'
# 词典大小，可根据语料分词个数进行调整，建议在50000以上
vocabulary_size = 10000
# 语料库文件地址，内容格式为：分类 分词1 分词2 ... 分词N
corpus_path = data_dir + '/corpus.txt'
# 生成分词关系坐标系图片保存地址
plot_participle_image_path = '../images/corpus.png'
# 生成分词关系坐标系字体库路径
plot_participle_font_path = r"c:\windows\fonts\simsun.ttc"
batch_size = 128
embedding_size = 128
skip_window = 1
num_skips = 2
# 验证集
valid_word = ["您", "好", "请问", "北京", "现在", "房", "价", "是", "多少"]
# 切记这个数字要和len(valid_word)对应，要不然会报错哦
valid_size = len(valid_word)
valid_window = 100
# Number of negative examples to sample.
num_sampled = 64
tf_device = '/cpu:0'
# 训练次数，推荐值3000000
num_steps = 5000
