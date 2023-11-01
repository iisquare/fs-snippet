# Windows 11

### WSL
- 重置WLS2网络
```
wsl --shutdown
netsh winsock reset
netsh int ip reset all
netsh winhttp reset proxy
ipconfig /flushdns
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
- 重启
```
net stop LxssManager
net start LxssManager
```
- 安装目录迁移
```
wsl --shutdown
wsl -l --all -v
wsl --export Ubuntu E:\System\archives\Ubuntu.tar
wsl --unregister Ubuntu
wsl --import Ubuntu E:\System\Ubuntu E:\System\archives\Ubuntu.tar
Ubuntu config --default-user ouyang
```

### WSA
- 安装
```
Add-AppxPackage
adb connect <TEST DEVICE IP ADDRESS>
adb install app-debug .apk
```
- [Android SDK Platform-Tools](https://developer.android.google.cn/studio/releases/platform-tools?hl=zh-cn)

## 参考
- [如何在 Windows 10 中安装 WSL2 的 Linux 子系统](https://blog.walterlv.com/post/how-to-install-wsl2.html)
- [Win10 WSL2 安装Docker](https://www.jianshu.com/p/a20c2d58eaac)
- [迁移wsl2子系统文件目录](https://juejin.cn/post/7024498662935904269)
- [如何管理 WSL 磁盘空间](https://learn.microsoft.com/zh-cn/windows/wsl/disk-space)
