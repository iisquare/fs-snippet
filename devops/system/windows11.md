# Windows 11

### WSL
- 重置WLS2网络
```
netsh winsock reset
```
- 解决WSL2与Proxifier无法同时使用的问题
```
# http://www.proxifier.com/tmp/Test20200228/NoLsp.exe
cd D:\openservices\cmd
.\NoLsp.exe C:\Windows\System32\wsl.exe
```
- 端口转发
```
netsh interface portproxy show all
netsh  interface portproxy add v4tov4 listenport=<port> listenaddress=0.0.0.0 connectport=<target-port> connectaddress=<target-ip>
netsh interface portproxy delete v4tov4 listenport=<port> listenaddress=0.0.0.0
```

## 参考
- [如何在 Windows 10 中安装 WSL2 的 Linux 子系统](https://blog.walterlv.com/post/how-to-install-wsl2.html)
- [Win10 WSL2 安装Docker](https://www.jianshu.com/p/a20c2d58eaac)
