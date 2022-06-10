# Java

## JDK
```
vim /etc/bashrc
export JAVA_HOME=/opt/jdk1.8.0_271
export JRE_HOME=$JAVA_HOME/jre
export CLASSPATH=.:$JAVA_HOME/lib/dt.jar:$JAVA_HOME/lib/tools.jar
export PATH=$PATH:$JAVA_HOME/bin:$JRE_HOME/bin
export ANT_HOME=/opt/apache-ant-1.9.15
export MAVEN_HOME=/opt/apache-maven-3.6.3
export GRADLE_HOME=/opt/gradle-6.8.2
export PATH=$PATH:$ANT_HOME/bin:$MAVEN_HOME/bin:$GRADLE_HOME/bin
# source /etc/bashrc
```
