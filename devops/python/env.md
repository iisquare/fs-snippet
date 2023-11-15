# Python

## Anaconda
- 安装建议
```
可将~/.bashrc文件中conda生成的环境变量，
迁移到任意文件中（如~/env/conda），
通过source方式加载，避免影响默认环境。
# >>> conda initialize >>>
# !! Contents within this block are managed by 'conda init' !!
# ******
# <<< conda initialize <<<
```
- 从文件创建环境
```
conda env create -f environment.yml

name: env-name
channels:
  - pytorch
dependencies:
  - python=3.6
  - pip
  - pip:
    - pip-package==x.x.x

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
conda search xxx
conda install jupyter=xxx
conda update jupyter
conda uninstall jupyter
pip install jupyter==xxx
pip install --upgrade jupyter
pip uninstall jupyter
```
- 更新导出环境
```
conda env update -f environment.yml
conda env export >  environment.yml
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
- 环境要求
```
# https://peps.python.org/pep-0508/
xxx==0.0.1; platform_system != "Darwin" and platform_machine != "x86_64" and python_version == "3.11"
platform_system: Darwin, Windows, Linux
platform_machine: x86_64, i386, AMD64
python_version: 3.8, 3.9, 3.11
```

## Config
- ~/.condarc & conda clean -i
```
# @see(https://mirrors.tuna.tsinghua.edu.cn/help/anaconda/)
channels:
  - defaults
show_channel_urls: true
default_channels:
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/main
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/r
  - https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/msys2
custom_channels:
  conda-forge: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  msys2: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  bioconda: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  menpo: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  pytorch: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  pytorch-lts: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  simpleitk: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud
  deepmodeling: https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/
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

## 参考链接

- [Anaconda Download](https://www.anaconda.com/download/)
