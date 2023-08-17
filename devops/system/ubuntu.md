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
