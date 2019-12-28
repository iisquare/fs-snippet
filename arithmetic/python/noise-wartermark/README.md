## Anaconda
- 从文件创建环境
```
conda env create -f environment.yml
```
- 单独创建环境
```
conda create --name xxx
```
- 查看及使用环境
```
conda info --envs
conda activate xxx
```
- 安装及卸载组件
```
conda install jupyter
conda uninstall jupyter
```
- 退出及删除环境
```
conda deactivate
conda env remove -n xxx
```

## 参考链接
- [deep-image-prior](https://github.com/DmitryUlyanov/deep-image-prior)
- [noise2noise](https://github.com/yu4u/noise2noise)
- [使用深度学习去除复杂图像水印](https://zhuanlan.zhihu.com/p/81373663)