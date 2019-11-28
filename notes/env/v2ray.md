## v2ray+websocket+tls+nginx+letsencrypt

### 安装v2ray
- 下载自动并执行安装脚本
```
wget https://install.direct/go.sh
chmod +x go.sh
./go.sh
```
- 修改配置文件（删除注释）
```
vi /etc/v2ray/config.json
{
  "inbounds": [
    {
      "port": 36722, // 建议修改
      "protocol": "vmess",
      "settings": {
        "clients": [
          {
            "id": "3cce7a7c-906f-4f3d-bae9-66ba015b5f5a", // 建议修改，可使用客户端生成
            "level": 1,
            "alterId": 64
          }
        ]
      },
      "streamSettings": {
        "network": "ws",
        "wsSettings": {
          "path": "/ray" // 建议修改
        }
      }
    }
  ],
  "outbounds": [
    {
      "protocol": "freedom",
      "settings": {}
    },
    {
      "protocol": "blackhole",
      "settings": {},
      "tag": "blocked"
    }
  ],
  "routing": {
    "rules": [
      {
        "type": "field",
        "ip": [
          "geoip:private"
        ],
        "outboundTag": "blocked"
      }
    ]
  }
}
```
- 执行v2ray程序
```
systemctl start v2ray
```
- 相关管理指令
  - 启动：`systemctl start v2ray`
  - 停止：`systemctl stop v2ray`
  - 重启：`systemctl restart v2ray`
  - 状态：`systemctl status v2ray`
  - 卸载：`./go.sh --remove`

### 申请letsencrypt证书
- 下载并执行certbot-auto客户端
```
wget https://dl.eff.org/certbot-auto
chmod +x certbot-auto
./certbot-auto --server https://acme-v02.api.letsencrypt.org/directory -d "*.yourdomain.com" --manual --preferred-challenges dns-01 certonly
```
执行完这一步之后，会下载一些需要的依赖，稍等片刻之后，会提示输入邮箱，随便输入都行【该邮箱用于安全提醒以及续期提醒】
![certbot-auto安装过程](https://images2018.cnblogs.com/blog/1021265/201803/1021265-20180315132910940-1161725031.png)
！！！注意，这里不要继续往下了申请通配符证书是要经过DNS认证的，按照提示，前往域名后台添加对应的DNS TXT记录。我这里使用的是阿里云域名解析，这里我们添加一下具体的DNS TXT 记录。
![aliyun添加TXT解析](https://images2018.cnblogs.com/blog/1021265/201803/1021265-20180315133252804-758436958.png)
添加之后，不要心急着按回车，先执行 dig _acme-challenge.tinywan.top txt 确认解析记录是否生效，生效之后再回去按回车确认。　
![确认解析是否生效](https://images2018.cnblogs.com/blog/1021265/201803/1021265-20180315133639536-608265497.png)
上面表示解析生效，按回车确认继续申请letsencrypt证书。
![继续申请letsencrypt证书](https://images2018.cnblogs.com/blog/1021265/201803/1021265-20180315133903909-1743522399.png)
出现以上界面说明配置成功，配置证书存放在 /etc/letsencrypt/live/tinywan.top/ 里面了。

### nginx反向代理
- 使用tls证书
```
server {
    server_name yourdomain.com;
    listen 443 ssl;
    ssl on;
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
}
```
- 反向代理v2ray（删除注释）
```
map $http_upgrade $connection_upgrade {
    default upgrade;
    '' close;
}

upstream websocket {
    server localhost:36722; // v2ray的运行端口
}

server { // 仅列出相关配置，清自行整合
    location /ray { // v2ray中配置的ws路径
        proxy_pass http://websocket;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection $connection_upgrade;
    }

}
```
- 重新载入nginx配置`nginx -s reload`

### 自动更新证书（以阿里云的PHP脚本为例）
- 下载钩子脚本并修改修改对应认证密钥
```
git clone https://github.com/ywdblog/certbot-letencrypt-wildcardcertificates-alydns-au
vi certbot-letencrypt-wildcardcertificates-alydns-au/alydns.php
define("accessKeyId","");
define("accessSecrec", "");
```
  - au.sh：操作阿里云 DNS hook shell（PHP 环境）。
  - autxy.sh：操作腾讯云 DNS hook shell（PHP 环境）。
  - python-version/au.py：操作阿里云 DNS hook shell（Python 2.7/3.6。
  - alydns.php：修改 accessKeyId、accessSecrec 变量，[阿里云 API key 和 Secrec 官方申请文档](https://help.aliyun.com/knowledge_detail/38738.html)。
  - txydns.php：修改 txyaccessKeyId、txyaccessSecrec 变量，[腾讯云 API 密钥官方申请文档](https://console.cloud.tencent.com/cam/capi)。
  - python-version/alydns27.py：修改 ACCESS_KEY_ID、ACCESS_KEY_SECRET，[阿里云 API key 和 Secrec 官方申请文档](https://help.aliyun.com/knowledge_detail/38738.html)。
- 配置自动更新
```
crontab -e
0 5 * * * /pat/to/certbot-auto renew --manual --preferred-challenges dns --manual-auth-hook /path/to/certbot-au/au.sh --renew-hook "systemctl restart  nginx"
```

### 防火墙配置
```
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
iptables -A INPUT -p tcp --dport 22 -j ACCEPT

#开放Web端口
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT

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
```

### 参考链接
- [V2ray+websocket+tls+caddy+serverSpeeder](https://segmentfault.com/a/1190000018242765)
- [申请Let's Encrypt通配符HTTPS证书](https://www.cnblogs.com/tinywan/p/8573169.html)
- [Nginx 实现 HTTPS（基于 Let's Encrypt 的免费证书）](https://blog.csdn.net/kikajack/article/details/79122701)
- [letsencrypt证书-使用certbot申请wildcard证书](https://www.cnblogs.com/redirect/p/10140254.html)
