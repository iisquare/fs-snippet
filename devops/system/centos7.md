# CentOS 7

系统环境准备

## 基础配置
- 修改主机名称
```
hostnamectl set-hostname hostname
```
- 修改文件句柄数
```
ulimit -a
ulimit -n 65535
vim /etc/security/limits.conf
* soft nofile 65535
* hard nofile 65535
* soft nproc  65535
* hard nproc  65535
```
- 网络参数优化
```
vim /etc/sysctl.conf
net.ipv4.tcp_syncookies = 1
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_tw_recycle = 1
net.ipv4.tcp_fin_timeout = 30
net.ipv4.tcp_keepalive_time = 1200
net.ipv4.ip_local_port_range = 10000 65000
net.ipv4.tcp_max_syn_backlog = 8192
net.ipv4.tcp_max_tw_buckets = 5000
net.ipv6.conf.all.disable_ipv6 =1
net.ipv6.conf.default.disable_ipv6 =1
# sysctl -p
```
- 域名解析
```
vim /etc/resolv.conf
nameserver 223.5.5.5
nameserver 192.168.0.1
# systemctl restart NetworkManager
```
- 命令补全
```
yum -y install bash-completion
source /etc/profile.d/bash_completion.sh
```
- 配置防火墙


### 临时文件

```

```

- [Linux管理临时文件tmpfiles](https://www.jianshu.com/p/a338f0705615)
- [tmpfiles.d 中文手册](http://www.jinbuguo.com/systemd/tmpfiles.d.html)
