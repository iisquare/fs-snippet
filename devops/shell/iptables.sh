#!/bin/sh

#查看本机配置
#iptables -L -n

#清除预设规则
iptables -F
#清除自定义规则
iptables -X

#拒绝所有流入
iptables -P INPUT DROP
#接受所有流出
iptables -P OUTPUT ACCEPT
#接受所有转发
iptables -P FORWARD ACCEPT

#接受SSH登录，端口22，以实际情况为准
iptables -A INPUT -p tcp --dport 100 -j ACCEPT

#开放Web端口
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT
iptables -A INPUT -p tcp -m tcp --dport 8080:8088 -j ACCEPT

#允许ping，根据实际情况确认是否需要启用
iptables -A INPUT -p icmp -j ACCEPT
iptables -A OUTPUT -p icmp -j ACCEPT

#允许loopback!(不然会导致DNS无法正常关闭等问题)
iptables -A INPUT -i lo -p all -j ACCEPT
iptables -A OUTPUT -o lo -p all -j ACCEPT

#在下面就是FORWARD链,FORWARD链的默认规则是DROP,所以我们就写需要ACCETP(通过)的链,对正在转发链的监控.
#开启转发功能,(在做NAT时,FORWARD默认规则是DROP时,必须做)
#iptables -A FORWARD -i eth0 -o eth1 -m state --state RELATED,ESTABLISHED -j ACCEPT
#iptables -A FORWARD -i eth1 -o eh0 -j ACCEPT
#丢弃坏的TCP包
#iptables -A FORWARD -p TCP ! --syn -m state --state NEW -j DROP
#处理IP碎片数量,防止攻击,允许每秒100个
#iptables -A FORWARD -f -m limit --limit 100/s --limit-burst 100 -j ACCEPT
#设置ICMP包过滤,允许每秒1个包,限制触发条件是10个包.
#iptables -A FORWARD -p icmp -m limit --limit 1/s --limit-burst 10 -j ACCEPT

#drop非法连接
#iptables -A INPUT -m state --state INVALID -j DROP
#iptables -A OUTPUT -m state --state INVALID -j DROP
#iptables -A FORWARD -m state --state INVALID -j DROP
#允许所有已经建立的和相关的连接
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

#写入配置/etc/sysconfig/iptables
#service iptables save

#重启服务，确保规则完全没有问题再写入和重启，切记！
#service iptables restart

