# analytics-rest-xlab

前沿业务实验室，包含图像处理、语义分析、反垃圾、反爬虫等功能

> 分类识别流程
- 维护ElasticSearch分词词库，词典编号spam，重载后生效
```
GET [OP]/lucene/dict/?iframe=auto
```
- 维护分类语料库
```
GET [OP]/semantic/corpus/?iframe=auto
```
- 执行语料库格式化脚本
```
GET [springcloud-rest-xlab]/semantic/generate
```
- 执行word2vec训练词向量字典
```
python word2vec/train.py
```
- 执行spam训练分类网络
```
python spam/train.py
```
- 转换神经网络模型以供其他语言调用
```
python -m unittest spam/transform.TestStringMethods.test_share
```

> 调试说明
- 安装依赖
```
pip3 install -r requirements.txt
```
- 查看训练过程日志
```
tensorboard --logdir=./logs
```

> 参考资料
- [《神经网络和深度学习》 - 网易云课堂](https://mooc.study.163.com/course/2001281002)
- [《莫烦 tensorflow 神经网络 教程》 - 优酷](https://v.youku.com/v_show/id_XMjc3MzA3OTM0NA==.html)
- [《运用TensorFlow处理简单的NLP问题》](http://sharkdtu.com/posts/nn-nlp.html)
