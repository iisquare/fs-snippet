# Ubuntu

## WSL

### 基础环境
- Shell
```
sudo dpkg-reconfigure dash
```
- SSH
```
sudo apt install ssh
systemctl start sshd
```
- Proxy
```
sudo apt install proxychains4
sudo vi /etc/proxychains4.conf
socks5  192.168.x.x   10808    lamer   secret
proxychains curl https://www.google.com/
```
- Sources
```
sudo cp /etc/apt/sources.list /etc/apt/sources.list.back
sudo vi /etc/apt/sources.list
# https://mirrors.tuna.tsinghua.edu.cn/help/ubuntu/
sudo apt-get update
# sudo apt-get upgrade
```
