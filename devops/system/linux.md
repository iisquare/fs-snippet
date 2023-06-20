# Linux

## 内容编辑

### VIM常用命令
```
删除列
1.光标定位到要操作的地方。
2.CTRL+v 进入“可视 块”模式，选取这一列操作多少行。
3.d 删除。
 
插入列
插入操作的话知识稍有区别。例如我们在每一行前都插入"() "：
1.光标定位到要操作的地方。
2.CTRL+v 进入“可视 块”模式，选取这一列操作多少行。
3.SHIFT+i(I) 输入要插入的内容。
4.ESC 按两次，会在每行的选定的区域出现插入的内容。

xp 交换前后两个字符的位置
ddp 上下两行的位置交换
```
- [linux vi编辑常用命令](https://www.jb51.net/LINUXjishu/57196.html)
- [VIM的列编辑操作](https://www.cnblogs.com/xiaowant/articles/1992923.html)

### 文件属性
```
lsattr /etc/passwd # 查看文件属性
chattr +i /etc/passwd # 将文件设置为Immutable不可改变状态
chattr -i /etc/passwd # 解除文件不可改变状态
# +a: 只能给文件添加内容，但是删除不了
```
- [Linux chattr 命令详解](https://cloud.tencent.com/developer/article/1598636)

## 基础信息

### 发行版本
```
ls /etc/*-release
```

## 基础配置

### 免密切换root

```
sudo -s
sudo su -
```

### sudo免输入密码

```
vim /etc/sudoers
doge   ALL=(root)     NOPASSWD:ALL
```

## 分析调试

### 常用工具
- 查看进程
```
top
ps aux
ps -ef # 查看父进程PPID
pstree -p # 查看进程树
```
- 查看端口
```
netstat -anltp
```
- 查看资源占用
```
lsof -p <进程PID>
lsof -i <:端口号>
```
- 查看硬盘
```
df -h
du -h -d 1
iotop -P
```
- 查看内存
```
free -h
```
- 按进程实时统计网络带宽利用率
```
命令用法：
1、设置5秒钟刷新一次，通过-d来指定刷新频率：nethogs -d 5
2、监视eth0网络带宽 :nethogs eth0
3、同时监视eth0和eth1接口 : nethogs eth0 eth1
交互命令：
以下是NetHogs的一些交互命令(键盘快捷键)
m : 修改单位
r : 按流量排序
s : 按发送流量排序
q : 退出命令提示符
```

### tcpdump抓包，配合wireshark进行分析

- [Linux tcpdump命令详解](http://www.cnblogs.com/ggjucheng/archive/2012/01/14/2322659.html)

### 命名空间
```
docker inspect -f {{.State.Pid}} nginx # 查看容器PID
nsenter -n -t5645 # 进入网络命名空间
```
- [nsenter命令简介](https://staight.github.io/2019/09/23/nsenter%E5%91%BD%E4%BB%A4%E7%AE%80%E4%BB%8B/)

