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

- [Linux管理临时文件tmpfiles](https://www.jianshu.com/p/a338f0705615)
- [tmpfiles.d 中文手册](http://www.jinbuguo.com/systemd/tmpfiles.d.html)

### 升级内核

```
wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-7.repo
wget -O /etc/yum.repos.d/epel.repo http://mirrors.aliyun.com/repo/epel-7.repo
yum list kernel # 查看可用内核版本
yum update -y kernel # 升级内核
rpm -q kernel # 查看已安装的内核
reboot # 重启
uname -a # 查看当前版本
yum remove kernel-3.10.0-957.el7.x86_64 -y # 移除旧内核
yum clean all # 清理
```
- [Linux之Centos7小版本升级](https://blog.csdn.net/carefree2005/article/details/114819885)


### Yum管理

```
yum clean headers # 清除header
yum clean packages # 清除下载的rpm包
yum clean all # 全部清除
yum makecache # 生成缓存
```

### 文件恢复

```
df /path/to/file # 查看文件目录所在分区
debugfs # 进入文件调试工具
open /dev/vda1 # 打开误删文件所在分区
ls -ld /path/to/file # 查看文件inode值
logdump -i <1452682> # 查找文件所在区块号
quit # 退出
dd if=/dev/vda1 of=/path/to/restore bs=<logdump-offset> count=1 skip=<logdump-block>
```
- [centos误删除恢复](https://blog.csdn.net/cheers_bin/article/details/112380310)
