#!/bin/bash
#
# 集群服务管理脚本
#

SERVER_JAVA_HOME=/opt/jdk1.8.0_111

sshCmd() # 执行远程Shell
{
    ssh -p 22 root@${1} "${2}"
}

hbase()
{
    local serverDir=/opt/hbase-1.2.4
    case "$2" in
        start|stop )
            echo "${2} in master..."
            sshCmd cluster1 "${serverDir}/bin/${2}-hbase.sh"
        ;;
        * )
            echo "Usage: ${1} [start|stop]"
        ;;
    esac
}

spark()
{
    local serverDir=/opt/spark-2.1.0-bin-hadoop2.7
    case "$2" in
        start|stop )
            echo "${2} in master..."
            sshCmd cluster1 "${serverDir}/sbin/${2}-all.sh"
        ;;
        * )
            echo "Usage: ${1} [start|stop]"
        ;;
    esac
}

zkcf()
{
    local nodeCount=2
    local serverDir=/opt/hadoop-2.7.3
    case "$2" in
        start|stop )
            local i
            for((i=1; i<=${nodeCount}; i++))
            do
                local nodeName="cluster${i}"
                echo "${2} ${nodeName}.${1}..."
                sshCmd ${nodeName} "${serverDir}/sbin/hadoop-daemon.sh ${2} zkfc"
            done
        ;;
        * )
            echo "Usage: ${1} [start|stop]"
        ;;
    esac
}

yarn()
{
    local serverDir=/opt/hadoop-2.7.3
    case "$2" in
        start )
            echo "${2} in master..."
            sshCmd cluster1 "${serverDir}/sbin/start-all.sh"
        ;;
        stop )
            echo "${2} in master..."
            sshCmd cluster1 "${serverDir}/sbin/stop-all.sh"
        ;;
        * )
            echo "Usage: ${1} [start|stop]"
        ;;
    esac
}

zookeeper()
{
    local nodeCount=3
    local serverDir=/opt/zookeeper-3.4.8
    if [ "${2}" ]; then
        local i
        for((i=1; i<=${nodeCount}; i++))
        do
            local nodeName="cluster${i}"
            echo "${2} ${nodeName}.${1}..."
            sshCmd ${nodeName} "${serverDir}/bin/zkServer.sh ${2}"
        done
    else
        echo "Usage: ${1} [start|stop|restart|status]"
    fi
}

kafka()
{
    local nodeCount=3
    local serverDir=/opt/kafka_2.11-0.10.1.0
    case "$2" in
        start )
            local i
            for((i=1; i<=${nodeCount}; i++))
            do
                local nodeName="cluster${i}"
                echo "${2} ${nodeName}.${1}..."
                sshCmd ${nodeName} "${serverDir}/bin/kafka-server-start.sh -daemon ${serverDir}/config/server.properties"
            done
        ;;
        stop )
            local i
            for((i=1; i<=${nodeCount}; i++))
            do
                local nodeName="cluster${i}"
                echo "${2} ${nodeName}.${1}..."
                #sshCmd ${nodeName} "${serverDir}/bin/kafka-server-stop.sh"
				sshCmd ${nodeName} "jps|grep Kafka|awk '{print \"kill -s TERM \"\$1}'|sh"
            done
        ;;
        * )
            echo "Usage: ${1} [start|stop]"
        ;;
    esac
}

tomcat()
{
    local nodeCount=3
    local serverDir=/opt/apache-tomcat-8.5.6
    if [ "${2}" ]; then
        local i
        for((i=1; i<=${nodeCount}; i++))
        do  
            local nodeName="cluster${i}"
            echo "${2} ${nodeName}.${1}..."
            sshCmd ${nodeName} "${serverDir}/bin/catalina.sh ${2}"
        done
    else
        echo "Usage: ${1} [start|stop]"
    fi
}

elasticsearch()
{   
    local nodeCount=3
    local serverDir=/opt/elasticsearch-5.0.0
    case "$2" in
        start )
            local i
            for((i=1; i<=${nodeCount}; i++))
            do
                local nodeName="cluster${i}"
                echo "${2} ${nodeName}.${1}..."
                sshCmd ${nodeName} "sudo -u www ${serverDir}/bin/elasticsearch -d &"
            done
        ;;
        stop )
            local i
            for((i=1; i<=${nodeCount}; i++))
            do
                local nodeName="cluster${i}"
                echo "${2} ${nodeName}.${1}..."
                sshCmd ${nodeName} "${SERVER_JAVA_HOME}/bin/jps|awk '/Elasticsearch/{print \"kill -9 \"\$1}'|sh"
            done
        ;;
        * )
            echo "Usage: ${1} [start|stop]"
        ;;
    esac
}

cmd()
{
    local nodeCount=6
    if [ "${2}" ]; then
        local i
        for((i=1; i<=${nodeCount}; i++))
        do
            local nodeName="cluster${i}"
            echo "----------------------------"
            echo "${nodeName}.${1}:${2}"
            echo "----------------------------"
            sshCmd ${nodeName} "${2}"
        done
    else
        echo "Usage: ${1} shell"
    fi
}

if [ "${1}"  ];then
    $1 $*
else
    echo "Usage: serverName serverOptions"
fi

