# Docker
项目依赖的基础服务，适用于单机临时测试运行，请勿在线上环境使用。

## 安装配置
- 安装依赖
```
yum install -y yum-utils device-mapper-persistent-data lvm2
```
- 设置Docker源
```
yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
```
- 查看Docker版本
```
yum list docker-ce --showduplicates | sort -r
```
- 安装Docker
```
yum install docker-ce-18.09.9 docker-ce-cli-18.09.9 containerd.io -y
yum install docker-compose -y
```
- 启动Docker
```
systemctl start docker
systemctl enable docker
systemctl status docker
```
- 镜像加速
```
tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": [
    "https://hub-mirror.c.163.com"
  ]
}
EOF
systemctl daemon-reload
systemctl restart docker
```
- 修改Cgroup Driver
```
vim /etc/docker/daemon.json
{
  "exec-opts": ["native.cgroupdriver=systemd"]
}
systemctl daemon-reload
systemctl restart docker
```
- 修改默认网段（若与宿主机172冲突）
```
vim /etc/docker/daemon.json
{
  "default-address-pools" : [
    {
      "base" : "192.168.10.0/16",
      "size" : 24
    }
  ]
}
systemctl daemon-reload
systemctl restart docker
```

## Docker
- Exec
```bash
docker exec -it {container-id} /bin/bash
docker run -it --entrypoint /bin/bash name:version
```
- Start Policy
```
docker run --restart=always
docker update --restart=always <CONTAINER ID>
```
- Info
```
docker inspect [OPTIONS] {NAME|ID}
```
- 复制文件
```
docker cp host_path containerID:container_path
docker cp containerID:container_path host_path
```
- 转移镜像
```
docker pull openjdk:8
docker pull openjdk:8-jdk-slim
docker pull openjdk:8-jre-slim
docker save -o openjdk-8.tar 89f100fa8f9f cd08b38dfcae fe56938077d4
tar -czvf openjdk-8.tar.gz openjdk-8.tar
tar -xzvf openjdk-8.gz
docker load < openjdk-8.tar
docker tag 89f100fa8f9f openjdk:8
docker tag fe56938077d4 openjdk:8-jdk-slim
docker tag cd08b38dfcae openjdk:8-jre-slim
```
- 上传镜像
```
# 拉取镜像
docker pull k8s.gcr.io/kubernetes-zookeeper:1.0-3.4.10
# 生成标签
docker tag anjia0532/google-containers.kubernetes-zookeeper:1.0-3.4.10 harbor.iisquare.com/gcr/kubernetes-zookeeper:1.0-3.4.10
# 登录仓库
docker login --username=admin harbor.iisquare.com
# 推送镜像
docker push harbor.iisquare.com/gcr/kubernetes-zookeeper:1.0-3.4.10
```

## Compose
- Start
```bash
docker-compose up -d {container-name}
docker-compose up -d
```
- Stop
```bash
docker-compose stop {container-name}
docker-compose stop
```
- Build
```bash
docker-compose build {container-name}
```
- Log
```bash
docker-compose logs {container-name}
docker-compose logs -f {container-name}
```
- Delete
```bash
docker-compose rm {container-name}
docker-compose down
```

## Best Practice
- network
```
bridge，桥接网络，以桥接模式连接到宿主机，默认方式；
host，宿主网络，即与宿主机共用网络；
none，表示无网络，容器将无法联网。
```
- privileged
```
true，container内的root拥有真正的root权限。
false，container内的root只是外部的一个普通用户权限。
```
- [run older base images](https://github.com/microsoft/WSL/issues/4694)
```
%userprofile%\.wslconfig

[wsl2]
kernelCommandLine = vsyscall=emulate
```
- volumes short syntax
```
[SOURCE:]TARGET[:MODE]
ro for read-only
rw for read-write (default)
```

## 参考连接
- [Compose file version 3 reference](https://docs.docker.com/compose/compose-file/compose-file-v3/)
- [为容器设置启动时要执行的命令和参数](https://kubernetes.io/zh/docs/tasks/inject-data-application/define-command-argument-container/)
- [docker-compose建立容器之间的连接关系](https://www.jianshu.com/p/1e80c2866a9d)
- [Docker run reference VOLUME (shared filesystems)](https://docs.docker.com/engine/reference/run/#volume-shared-filesystems)
- [Segmentation fault when run old debian containers if docker host is debian10(buster)](https://stackoverflow.com/questions/57807835/segmentation-fault-when-run-old-debian-containers-if-docker-host-is-debian10bus)
- [Enable vsyscall=emulate in the kernel config to run older base images such as Centos 6](https://github.com/microsoft/WSL/issues/4694)
