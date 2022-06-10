# Spring Boot

## 参数优化
- 监控
```
# build.gradle
compile group: 'org.springframework.boot', name: 'spring-boot-starter-actuator', version: springBootVersion
compile group: 'io.micrometer', name: 'micrometer-registry-prometheus', version: '1.6.3'

# application.yml
management:
  endpoints:
    web:
      exposure:
        include: '*'
```
- Jedis
```
# build.gradle
compile (group: 'org.springframework.boot', name: 'spring-boot-starter-data-redis', version: springBootVersion) {
  exclude group: 'io.lettuce', module: 'lettuce-core'
}
compile group: 'redis.clients', name: 'jedis', version: '3.5.1'
compile group: 'org.apache.commons', name: 'commons-pool2', version: '2.6.2'

# application.yml
# 单机
spring:
  redis:
    database: 1
    host: 127.0.0.1
    port: 6379
    password:
    timeout: 5000
    jedis:
      pool:
        max-active: 8 # 连接池最大连接数（使用负值表示没有限制）
        max-wait: -1 # 连接池最大阻塞等待时间（使用负值表示没有限制）
        max-idle: 10 # 连接池中的最大空闲连接
        min-idle: 5 # 连接池中的最小空闲连接
# 集群
spring:
  redis:
    cluster:
      nodes: ip:6379,ip:6379,ip:6379
      max-redirects: 2
      timeout: 300
      max-attempts: 3
    jedis:
      pool:
        max-active: 100  # 连接池最大连接数（使用负值表示没有限制）
        max-wait: 200 # 连接池最大阻塞等待时间（使用负值表示没有限制）
        max-idle: 50 # 连接池中的最大空闲连接
        min-idle: 10 # 连接池中的最小空闲连接
```
- Tomcat
```
server:
  tomcat:
    max-connections: 8192
    max-threads: 200
    accept-count: 100
    min-spare-threads: 10
```
