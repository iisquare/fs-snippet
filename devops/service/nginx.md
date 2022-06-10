# Nginx

### 安装步骤
- 添加源
```
rpm -Uvh http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
```
- 安装并设置开机启动
```
yum install -y nginx
systemctl start nginx.service
systemctl enable nginx.service
```

### 参数配置
- 并发参数
```
user  nginx;
worker_processes  6;
worker_rlimit_nofile 65535;

events {
    worker_connections  10240;
}
```
- 拒绝默认访问
```
vim /etc/nginx/conf.d/default.conf
server {
    listen 80 default_server;
    server_name _;
    return 502;
```
- 访问认证
```
yum -y install httpd-tools
htpasswd pass.db username
server {
    auth_basic "User Authentication";
    auth_basic_user_file /path/to/pass.db;
```
- 静态资源
```
location /uri/ {
  # /uri/index.html -> /path/to/static/uri/index.html
  # root /path/to/static/;

  # /uri/index.html -> /path/to/static/index.html
  # alias /path/to/static/;

  # gzip on;

  # ETag: "文件大小的十六进制"; Last-Modified: UTC DateTime; Status Code: 304 OK
  # etag on;

  # exact：完全符合; before：响应的修改时间小于或等于请求头中的 “If-Modified-Since” 字段的时间
  # if_modified_since off | exact | before;

  # 在缓存期内不会请求服务端，更不会触发ETag判断。Status Code: 200 OK (from disk/memory cache)
  # expires 30d;

  # autoindex on;
  # autoindex_localtime on;
  # autoindex_exact_size off;

}
```
- 反向代理
```
upstream web {
    server ip:port weight=5;
    server ip:port weight=1;
    server ip:port backup;
}

log_format filebeat '$remote_addr - $remote_user [$time_local] "$request" $status $body_bytes_sent "$http_referer" "$http_user_agent" $request_length $request_time [$proxy_upstream_name] [$proxy_alternative_upstream_name] $upstream_addr $upstream_response_length $upstream_response_time $upstream_status $req_id';

server {

    listen 80;
    server_name xxxxxx;

    location /api/v1/ {
        access_log /var/log/nginx/xxxxxx.access.log filebeat;
        error_log  /var/log/nginx/xxxxxx.error.log;

        rewrite  ^/api/v1/?(.*)$ /$1 break;
        proxy_pass http://web;
    }

}

```
