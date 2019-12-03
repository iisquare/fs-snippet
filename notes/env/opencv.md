# OpenCV环境搭建

在阅读本章之前，读者需要具备一定的编程知识并对自己所用的操作系统有足够的了解，之后选择自己所熟悉的系统搭建开发环境。在编写此书之时，OpenCV最新版本为3.2.0，所以本章及之后的章节都会采用该版本进行代码讲述。

## 安装官方发布版本
官方发布版本一般为最新稳定版，预编译了Win、iOS、Android等主流操作系统下的安装包，但限于开发环境的不同和安装包文件大小等问题，在官方发布的安装包中，不包含内置的部分工具性程序，仅满足常规的开发需求。从3.x版本起，官方将不稳定的贡献模块单独拆分了出去，如人脸识别、目标跟踪等，若需要用到这类功能，只能自己单独编译，请参照后面的章节。因此，本节仅以Win操作系统为例来讲解开发环境的安装部署。
### 1.下载安装
官方下载页面地址为：`http://opencv.org/releases.html`

其中，Documentation为在线文档地址，后面会详细介绍如何使用该文档查找自己需要的资料。Sources为对应版本的源码包，链接到GitHub进行下载。Win pack、iOS pack、Android pack为对应系统下的安装包和SDK。此处我们选择Win pack进行下载，执行opencv-3.2.0-vc14.exe安装程序解压资源后，对应的目录结构如下。
```
./opencv-3.2.0
├── build
│   ├── bin
│   ├── etc
│   ├── include
│   ├── java
│   ├── python
│   ├── x64
│   ├── LICENSE
│   ├── OpenCVConfig.cmake
│   └── OpenCVConfig-version.cmake
├── sources
│   ├── 3rdparty
│   ├── apps
│   ├── cmake
│   ├── data
│   ├── doc
│   ├── include
│   ├── modules
│   ├── platforms
│   ├── samples
│   ├── CMakeLists.txt
│   ├── CONTRIBUTING.md
│   ├── LICENSE
│   └── README.md
├── LICENSE_FFMPEG.txt
├── LICENSE.txt
└── README.md.txt
```
### 2.编写第一个示例程序
细心的读者在下载安装程序时可能会发现，开发包对应的编译器版本为vc14，与IDE的对应关系如下。
|  编译器   | IDE  |
|  ----  | ----  |
|  vc8  |  Visual Studio 2005  |
|  vc9  |  Visual Studio 2008  |
|  vc10  |  Visual Studio 2010  |
|  vc11  |  Visual Studio 2012  |
|  vc12  |  Visual Studio 2013  |
|  vc13  |  Visual Studio 2014  |
|  vc14  |  Visual Studio 2015  |
|  vc15  |  Visual Studio 2017  |
另外，除编译器不同之外，根据系统平台架构，还分为x86、x64和ARM版本，读者应根据自己的系统环境进行选择，在编译安装环境会详细介绍。

首先打开Visual Studio 2015（需要自行安装，此处不累述），新建VC++win32控制台项目，在主文件中输入测试代码。
```
#include "stdafx.h"
#include <opencv2\opencv.hpp>
using namespace std;
using namespace cv;

int main(int argc, char* argv[])
{
    const char* imagename = "C:\\Users\\Ouyang\\Pictures\\test.jpg";
    Mat image = imread(imagename);
    imshow("image", image);
    waitKey();
    return 0;
}
```
然后到项目->属性->VC++目录里依次修改如下编译配置。
```
可执行文件目录：
Driver:\path\to\opencv-3.2.0\build\x64\vc14\bin

包含文件目录：
Driver:\path\to\opencv-3.2.0\build\include
Driver:\path\to\opencv-3.2.0\build\include\opencv
Driver:\path\to\opencv-3.2.0\build\include\opencv2

库目录：
Driver:\path\to\opencv-3.2.0\build\x64\vc14\lib
```
在连接器->输入里添加附加依赖项，其中320d表示该文件为Debug版。
```
opencv_world320.lib
opencv_world320d.lib
```
在C/C++->预处理器->预处理器定义中忽略安全提示。
```
_CRT_SECURE_NO_WARNINGS
```
将依赖的DLL文件拷贝至项目的x64\Debug目录下，或者将DLL文件所在目录加入到环境变量中。
```
opencv_world320.dll
opencv_world320d.dll
```
编译并执行。
## Win下源码安装
官方发布的安装包仅提供了特定环境下的常规操作，如果需要更多的特性支持，就需要自己进行编译。
### 1.下载源代码
通过GitHub下载最新源代码并切换到Tags-3.2.0分支，或者直接下载对应分支的源代码。采用对应分支代码编译的原因，一是统一笔者和读者的开发环境，方便定位书中代码的位置；二是最新代码中可能存在官方正在开发调试的代码，会有编译不通过的情况。以下步骤，读者也可以安装TortoiseGit，通过界面化操作完成。
```
cd /path/to/workspaces
git clone https://github.com/opencv/opencv.git
cd opencv
git checkout -b Branch_3.2.0 3.2.0
cd /path/to/workspaces
git clone https://github.com/opencv/opencv_contrib.git
cd opencv_contrib
git checkout -b Branch_3.2.0 3.2.0
```
### 2.使用CMake编译
编译Visual Studio版本
- 安装并打开CMake3.8.1程序
- 在`Where is the source code`栏填入源码所在目录`Driver:/path/to/opencv`
- 在`Where to build thebinaries`栏填入编译后的文件存放目录`Driver:/path/to/opencv/build`（此处笔者是在opencv源码同级目录下建了一个build/VS2015目录方便区分不同版本）
- 点击`Add Entry`按钮添加自定义参数`OPENCV_EXTRA_MODULES_PATH=Driver:/path/to/opencv_contrib/modules`
- 点击`Configure`按钮，在弹出的对话框中`Specify the generator for this project`栏选择对应编译器版本`Visual Studio 14 2015`
- 点击`Finish`按钮确定并等待配置过程执行完成
- 点击`Generate`按钮生成配置文件
- 打开VS2015的MSBuild命令提示符，执行编译
  ```
  cd Driver:/path/to/opencv/build/VS2015
  msbuild /m OpenCV.sln /t:Build /p:Configuration=Release /v:m
  ```
编译Qt版本
- 下载并安装qt-opensource-windows-x86-mingw530-5.8.0.exe
- 将`C:\Qt\Qt5.8.0\Tools\mingw530_32\bin`加入到Path环境变量
- 安装并打开CMake3.8.1程序
- 在`Where is the source code`栏填入源码所在目录`Driver:/path/to/opencv`
- 在`Where to build thebinaries`栏填入编译后的文件存放目录`Driver:/path/to/opencv/build`
- 点击`Add Entry`按钮添加自定义参数`OPENCV_EXTRA_MODULES_PATH=Driver:/path/to/opencv_contrib/modules`
- 点击`Configure`按钮，在弹出的对话框中`Specify the generator for this project`栏选择对应编译器版本`MingGW Makefiles`，然后选择`Specify native compilers`
  - 在C编译器中填入`C:/Qt/Qt5.8.0/Tools/mingw530_32/bin/gcc.exe`
  - 在C++编译器中填入`C:/Qt/Qt5.8.0/Tools/mingw530_32/bin/g++.exe`
- 点击`Finish`按钮确定并等待配置过程执行完成
- 在配置栏中找到`WITH_QT`和`WITH_OPENGL`并勾选，再次点击`Configure`按钮，此时会弹出`Error in configuration process,project files may be invalid`错误提示，在配置栏中补充一下配置项
  ```
  QT_QMAKE_EXECUTABLE=C:/Qt/Qt5.8.0/5.8/mingw53_32/bin/qmake.exe
  Qt5Concurrent_DIR=C:\Qt\Qt5.8.0\5.8\mingw53_32\lib\cmake\Qt5Concurrent
  Qt5Core_DIR=C:\Qt\Qt5.8.0\5.8\mingw53_32\lib\cmake\Qt5Core
  Qt5Gui_DIR=C:\Qt\Qt5.8.0\5.8\mingw53_32\lib\cmake\Qt5Gui
  Qt5Test_DIR=C:\Qt\Qt5.8.0\5.8\mingw53_32\lib\cmake\Qt5Test
  Qt5Widgets_DIR=C:\Qt\Qt5.8.0\5.8\mingw53_32\lib\cmake\Qt5Widgets
  Qt5OpenGL_DIR=C:\Qt\Qt5.8.0\5.8\mingw53_32\lib\cmake\Qt5OpenGL
  ```
- 再次点击`Configure`按钮，直到配置栏中没有红色提示项
- 点击`Generate`按钮生成配置文件
- 进入`Driver:\path\to\opencv\build\MinGW`目录
- 执行`mingw32-make`命令并等待完成，次过程耗时较长
- 执行`mingw32-make install`命令并等待完成，默认安装目录为`Driver:\path\to\opencv\build\MinGW\install`，可通过`CMAKE_INSTALL_PREFIX`配置项进行更改
### 3.编写Qt程序
打开`Qt Creator 4.2.1 (Community)`新建`Qt Console Application`，在主文件中输入测试代码。
```
#include "cv.h"
#include "cxcore.h"
#include "highgui.h"

int main(int argc, char* argv[])
{
    //声明IplImage指针
    IplImage*pImg;
    //载入图片
    pImg=cvLoadImage("C:/Users/Ouyang/Pictures/test.jpg", 1);
    //创建窗口
    cvNamedWindow("Image", 1);
    //显示图像
    cvShowImage("Image", pImg);
    //等待按键
    cvWaitKey(0);
    //销毁窗口
    cvDestroyWindow("Image");
    //释放图像
    cvReleaseImage(&pImg);
    return 0;
}
```
编辑项目配置文件`project.pro`，增加如下配置项。
```
INCLUDEPATH += Driver:/path/to/opencv/build/MinGW/install/include/opencv \
              Driver:/path/to/opencv/build/MinGW/install/include/opencv2 \
              Driver:/path/to/opencv/build/MinGW/install/include

LIBS += -LDriver:/path/to/opencv/build/MinGW/install/x86/mingw/lib
```
将依赖的DLL文件拷贝至项目的`build-qt_demo-Desktop_Qt_5_8_0_MinGW_32bit-Debug\debug`目录下，或者将DLL文件所在目录加入到环境变量中。
```
Driver:/path/to/opencv/build/MinGW/bin/*.dll
```
编译并执行。

## Linux下源码安装
OpenCV3.x版本源码编译需要Python3.x+、CMake3.x+版本，当前主流的Unix操作系统中CentOS6默认为2.x版本，CentOS7和Ubuntu默认为3.x版本，在安装对应依赖时需要注意下。
### 1.下载源代码
```
cd /path/to/workspaces
git clone https://github.com/opencv/opencv.git
cd opencv
git checkout -b Branch_3.2.0 3.2.0
cd /path/to/workspaces
git clone https://github.com/opencv/opencv_contrib.git
cd opencv_contrib
git checkout -b Branch_3.2.0 3.2.0
```
### 2.安装依赖
CentOS6下为
```
yum install cmake3
yum install python3
yum install gcc
yum groupinstall "Development Tools"
```
CentOS7下为
```
yum install cmake
yum install python
yum install gcc
yum groupinstall "Development Tools"
```
以下依赖不区分CentOS系统版本
```
yum install gcc-c++ gtk+-devel gimp-devel gimp-devel-tools gimp-help-browser zlib-devel libtiff-devel libjpeg-devel libpng-devel gstreamer-devel libavc1394-devel libraw1394-devel libdc1394-devel jasper-devel jasper-utils swig libtool nasm
```
### 3.编译安装
创建编译目录
```
cd /path/to/workspaces
mkdir build
cd build
```
CentOS6下生成配置文件为
```
cmake3 -DCMAKE_BUILD_TYPE=RELEASE -DBUILD_SHARED_LIBS=OFF -DBUILD_TESTS=OFF -D PYTHON_EXECUTABLE=/usr/bin/python3  -DOPENCV_EXTRA_MODULES_PATH=/path/to/workspaces/opencv_contrib/modules -DCMAKE_INSTALL_PREFIX=/path/to/workspaces/opencv_build/install ../opencv
```
CentOS7和Ubuntu下生成配置文件为
```
cmake DCMAKE_BUILD_TYPE=RELEASE -DBUILD_SHARED_LIBS=OFF -DBUILD_TESTS=OFF -DOPENCV_EXTRA_MODULES_PATH=/path/to/workspaces/opencv_contrib/modules -DCMAKE_INSTALL_PREFIX=/path/to/workspaces/opencv_build/install ../opencv
```
编译并安装
```
make -j8
sudo make install
```
其中make -j8表示采用8线程编译，读者可根据自己的情况进行调整。另外，生成配置的步骤也可以通过cmkae-gui在可视化界面下操作。

## 使用Java开发应用
OpenCV 源码安装方式在cmake生成配置阶段会自动检测系统是否安装了JDK和ANT，若存在默认进行编译。
### 1.环境准备
下载并安装JDK和ANT
```
export JAVA_HOME=/opt/jdk1.8.0_111
export JRE_HOME=$JAVA_HOME/jre
export CLASSPATH=.:$JAVA_HOME/lib/dt.jar:$JAVA_HOME/lib/tools.jar
export ANT_HOME=/opt/apache-ant-1.9.7
export PATH=$PATH:$JAVA_HOME/bin:$JRE_HOME/bin:$ANT_HOME/bin
```
### 2.编译安装
请参照对应平台的源码安装章节，在cmake配置阶段，确认有以下配置项。
```
BUILD_SHARED_LIBS=OFF
```
在输出结果中，确认有以下内容。
```
  Java:
    ant:                         /path/to/apache-ant/bin/ant (ver 1.9.7)
    JNI:                         /path/to/jdk1.8.0_111/include
    Java wrappers:               YES
    Java tests:                  NO
```
### 3.编写示例程序
打开Eclipse编辑器，选择对应的JDK，引入opencv-320.jar，新建测试类。
```
import org.opencv.core.Core;
import org.opencv.core.Mat;
import org.opencv.core.CvType;
import org.opencv.core.Scalar;

class SimpleSample {

    static {
        System.loadLibrary(Core.NATIVE_LIBRARY_NAME);
    }

    public static void main(String[] args) {
        System.out.println("Welcome to OpenCV " + Core.VERSION);
        Mat m = new Mat(5, 10, CvType.CV_8UC1, new Scalar(0));
        System.out.println("OpenCV Mat: " + m);
        Mat mr1 = m.row(1);
        mr1.setTo(new Scalar(1));
        Mat mc5 = m.col(5);
        mc5.setTo(new Scalar(5));
        System.out.println("OpenCV Mat data:\n" + m.dump());
    }

}
```
### 4.启用更多模块
在贡献库中，某些模块默认没有添加Java的WRAP，若有需要可以手动添加，以人脸识别模块为例。
- 进入贡献库的人脸识别模块
  ```
  cd /path/to/opencv_contrib/modules/face
  ```
- 编辑CMakeLists.txt文件，在WRAP后添加java
  ```
  set(the_description "Face recognition etc")
  ocv_define_module(face opencv_core opencv_imgproc opencv_objdetect WRAP python java)
  # NOTE: objdetect module is needed for one of the samples
  ```
- 参照对应源码安装章节进行编译配置
- 编写测试代码
  ```
  import org.opencv.face.*;
  FaceRecognizer fr = Face.createLBPHFaceRecognizer();
  ```
## 参考链接
来源：https://iisquare.gitbooks.io/opencv/content/
