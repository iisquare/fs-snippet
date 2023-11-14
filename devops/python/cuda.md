## Nvidia CUDA
- [GPU算力](https://developer.nvidia.com/cuda-gpus)
- [显卡驱动](https://www.geforce.com/drivers)
- [cuda-toolkit](https://developer.nvidia.com/cuda-toolkit)
- [CUDA Toolkit Archive](https://developer.nvidia.com/cuda-toolkit-archive)
- [NVIDIA cuDNN](https://developer.nvidia.cn/zh-cn/cudnn)
- [NVIDIA cuDNN Archive](https://developer.nvidia.com/rdp/cudnn-archive)
- [CUDA Toolkit and Compatible Driver Versions](https://docs.nvidia.com/deploy/cuda-compatibility/index.html)

## Nvidia VGA
| 型号 | 显存 | 位宽 | 频率 | 建议电源 | 电源接口 | 参考价 |
| :----- | :----- | :----- | :----- | :----- | :----- | :----- |
| GeForce RTX 3090 | 24GB GDDR6X | 384bit | 19500MHz | 750W | 8pin+8pin | 12K |
| GeForce RTX 3080 | 10GB GDDR6X | 320bit | 19000MHz | 750W | 8pin+8pin | 5.5K |
| GeForce RTX 3070 | 8GB GDDR6 | 256bit | 14000MHz | 650W | 8pin | 3.9K |
| GeForce RTX 3060Ti | 8GB GDDR6 | 256bit | 14000MHz | 600W | 8pin | 3K |
| GeForce RTX 2080Ti | 11GB GDDR6 | 352bit | 14000MHz | - | 8pin+8pin | 10K |
| GeForce RTX 2080 SUPER | 8GB GDDR6 | 256bit | 15500MHz | 650W | 6pin+8pin | 8K |
| GeForce RTX 2080 | 8GB GDDR6 | 256bit | 14000MHz | - | 6pin+8pin | 14K |
| GeForce RTX 2070 SUPER | 8GB GDDR6 | 256bit | 14000MHz | 650W | 6pin+8pin | 3.2K |
| GeForce RTX 2070 | 8GB GDDR6 | 256bit | 14000MHz | - | 8pin | 6.6K |
| GeForce RTX 2060 SUPER | 8GB GDDR6 | 256bit | 14000MHz | 550W | 8pin | 4K |
| GeForce GTX 1080Ti | 11GB GDDR5X | 352bit | 11000MHz | - | 6pin+8pin | 8.7K |
| GeForce GTX 1080 | 8GB GDDR5X | 256bit | 10000MHz | - | 8pin | 4.9K |
| GeForce GTX 1070Ti | 8GB GDDR5 | 256bit | 8000MHz | - | 8pin | - |

## 使用说明

### 安装步骤
- 根据操作系统和显卡型号，下载最新版本驱动。
```
nvidia-smi
```
- 根据工程环境要求，下载对应CUDA版本，参考显卡驱动支持。
```
创建任意文件（如~/env/cuda12）用以载入环境变量，
export PATH=$PATH:/usr/local/cuda-12.1/bin
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usr/local/cuda-12.1/lib64
通过source方式独立加载以上配置，避免多个工程项目相互影响。
特殊情况下，
可将/usr/local/cuda-12.1/lib64加入到/etc/ld.so.conf中，通过root运行ldconfig命令加载。
```
- 根据CUDA版本，下载对应cuDNN深度神经网络库，推荐压缩包文件。
```
将cuDNN压缩包中的文件，拷贝到CUDA对应版本的目录下。
sudo cp -R cudnn-linux-x86_64-8.9.1.23_cuda12-archive/lib/* /usr/local/cuda-12.1/lib64/
sudo cp -R cudnn-linux-x86_64-8.9.1.23_cuda12-archive/include/* /usr/local/cuda-12.1/include/
sudo chmod -x /usr/local/cuda-12.1/include/cudnn*
```
- 卸载
```
sudo /usr/local/cuda-12.1/bin/cuda-uninstaller
```

## 参考链接
- [TensorFlow Keras Version](https://docs.floydhub.com/guides/environments/)
- [TensorFlow CUDA Version](https://tensorflow.google.cn/install/source)
