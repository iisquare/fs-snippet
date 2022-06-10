# Python

## Anaconda
- 从文件创建环境
```
conda env create -f environment.yml

name: env-name
channels:
  - pytorch
dependencies:
  - python=3.6
  - conda-package
  - pip:
    - pip-package

```
- 单独创建环境
```
conda create --name xxx python=3.7
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
- 环境变量
```
PYTHONPATH=.
```
- 与pip协作
```
pip freeze > requirements.txt
pip install -r requirements.txt
python -m pip install 'git+https://github.com/xxx.git'
python -m pip install -e [local clone]
conda list -e > requirements.txt
conda install --yes --file requirements.txt
```

## Config
- ~/.condarc & conda clean -i
```
channels:
  - defaults
show_channel_urls: true
channel_alias: https://mirrors.tuna.tsinghua.edu.cn/anaconda
default_channels:
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/main
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/free
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/r
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/pro
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/msys2
custom_channels:
  conda-forge: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  msys2: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  bioconda: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  menpo: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  pytorch: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  simpleitk: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
ssl_verify: true
remote_read_timeout_secs: 100.0
report_errors: false
```
- ~/.pip/pip.conf
```
[global]
timeout = 6000
index-url = https://pypi.tuna.tsinghua.edu.cn/simple
```
