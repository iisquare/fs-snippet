from matplotlib.font_manager import FontProperties
import matplotlib.pyplot as plt
from sklearn.manifold import TSNE
from word2vec import config
from six.moves import xrange

# 绘制并保存分词关系坐标系图
def plot_with_labels(low_dim_embs, labels, filename, fonts=None):
    assert low_dim_embs.shape[0] >= len(labels), "More labels than embeddings"
    plt.figure(figsize=(18, 18))  # in inches
    for i, label in enumerate(labels):
        x, y = low_dim_embs[i, :]
        plt.scatter(x, y)
        plt.annotate(label,
                     fontproperties=fonts,
                     xy=(x, y),
                     xytext=(5, 2),
                     textcoords='offset points',
                     ha='right',
                     va='bottom')
    plt.savefig(filename, dpi=800)

def draw(reverse_dictionary, final_embeddings):
    print(type(reverse_dictionary), type(final_embeddings), final_embeddings.shape)
    print(reverse_dictionary)
    print(final_embeddings[0])
    # 为了在图片上能显示出中文
    font = FontProperties(fname=config.plot_participle_font_path, size=14)
    tsne = TSNE(perplexity=30, n_components=2, init='pca', n_iter=5000)
    plot_only = 500
    low_dim_embs = tsne.fit_transform(final_embeddings[:plot_only, :])
    labels = [reverse_dictionary[i] for i in xrange(plot_only)]
    plot_with_labels(low_dim_embs, labels, config.plot_participle_image_path, fonts=font)
