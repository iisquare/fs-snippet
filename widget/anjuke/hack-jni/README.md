## 编译链接库

- 生成头文件
```
javah -jni Sign 
```

- Win.dll
```
cl -I "%JAVA_HOME%\include" -I "%JAVA_HOME%\include\win32"  -LD Sign.cpp
```