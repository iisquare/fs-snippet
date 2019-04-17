<?php
/**
 * Redis操作功能类
 * 命令参考：http://doc.redisfans.com/string/set.html
 * 函数声明参考：https://github.com/phpredis/phpredis/blob/develop/redis.c
 * 使用说明参考：https://github.com/phpredis/phpredis
 */
class RedisProxy {
    
    /**
     * @var Redis
     */
    private $redis;
    private static $masters = array();
    private static $slavers = array();
    private static $dbConfigs = array(
        'default' => array(
            'db' => 0,
            'host' => 'host:port'
        )
    );

    private function __construct($host, $port, $dbNumber) {
        $this->redis = new Redis();
        if($this->connect($host, $port)) {
            $this->select($dbNumber);
        }
    }
    
    public function __destruct() {
        $this->close();
    }

    private function __clone() {}
    
    /**
     * 获取主库对象
     * @return System_Cache_Redis
     */
    public static function master($name = 'default') {
        if (isset(self::$masters[$name])) return self::$masters[$name];
        if(isset(self::$dbConfigs[$name])) {
            $config = array_merge(self::$dbConfigs['default'], self::$dbConfigs[$name]);
        } else {
            $config = self::$dbConfigs['default'];
        }
        $arr = explode(':', $_SERVER[$config['host']]);
        return self::$masters[$name] = new self($arr[0], $arr[1], $config['db']);
    }
    
    /**
     * 获取从库对象
     * @return System_Cache_Redis
     */
    public static function slaver($name = 'default') {
        if (isset(self::$slavers[$name])) return self::$slavers[$name];
        if(isset(self::$dbConfigs[$name])) {
            $config = array_merge(self::$dbConfigs['default'], self::$dbConfigs[$name]);
        } else {
            $config = self::$dbConfigs['default'];
        }
        $arr = explode(':', $_SERVER[$config['host'].'_R']);
        return self::$slavers[$name] = new self($arr[0], $arr[1], $config['db']);
    }

    /**
     * 连接到Redis实例
     * @param string $host
     * @param int $port
     * @param double $timeout seconds
     * @param string $persistentId 持久化标识
     * @param long $retryInterval miliseconds
     * @return boolean
     */
    public function connect ($host, $port = null, $timeout = null, $persistentId = null, $retryInterval = null) {
        if(null === $port && null === $timeout && null === $persistentId && null === $retryInterval) {
            return $this->redis->connect($host);
        }
        if(null === $timeout && null === $persistentId && null === $retryInterval) {
            return $this->redis->connect($host, $port);
        }
        if(null === $persistentId && null === $retryInterval) {
            return $this->redis->connect($host, $port, $timeout);
        }
        if(null === $retryInterval) {
            return $this->redis->connect($host, $port, $timeout, $persistentId);
        }
        return $this->redis->connect($host, $port, $timeout, $persistentId, $retryInterval);
    }
    
    /**
     * 持久连接到Redis实例
     * @param string $host
     * @param int $port
     * @param double $timeout seconds
     * @param string $persistentId 持久化标识
     * @param long $retryInterval miliseconds
     * @return boolean
     */
    public function pconnect ($host, $port = null, $timeout = null, $persistentId = null, $retryInterval = null) {
        if(null === $port && null === $timeout && null === $persistentId && null === $retryInterval) {
            return $this->redis->pconnect($host);
        }
        if(null === $timeout && null === $persistentId && null === $retryInterval) {
            return $this->redis->pconnect($host, $port);
        }
        if(null === $persistentId && null === $retryInterval) {
            return $this->redis->pconnect($host, $port, $timeout);
        }
        if(null === $retryInterval) {
            return $this->redis->pconnect($host, $port, $timeout, $persistentId);
        }
        return $this->redis->pconnect($host, $port, $timeout, $persistentId, $retryInterval);
    }
    
    /**
     * 认证
     * @param string $password
     * @return boolean
     */
    public function auth ($password) {
        return $this->redis->auth($password);
    }
    
    /**
     * 选择数据库
     * @param long $dbNumber
     * @return boolean
     */
    public function select ($dbNumber) {
        return $this->redis->select($dbNumber);
    }
    
    /**
     * 关闭连接，除了使用pconnect的时候
     * @return boolean
     */
    public function close () {
        return $this->redis->close();
    }
    
    /**
     * 设置客户端配置选项
     * @param string $option
     * @param mixed $value
     * @return boolean
     */
    public function setOption ($option, $value) {
        return $this->redis->setOption($option, $value);
    }
    
    /**
     * 获取客户端配置选项
     * @param string $option
     * @return string
     */
    public function getOption ($option) {
        return $this->redis->getOption($option);
    }
    
    /**
     * 获取当前连接状态
     * @return string
     *  - +PONG 成功
     *  - throw RedisException 失败
     */
    public function ping () {
        return $this->redis->ping();
    }
    
    /**
     * 回显
     * @param string $message
     * @return string
     */
    public function _echo ($message) {
        return $this->redis->echo($message);
    }
    
    /**
     * 触发后台重写AOF文件
     * @return boolean
     */
    public function bgRewriteAOF () {
        return $this->redis->bgRewriteAOF();
    }
    
    /**
     * 异步方式保存数据到硬盘（后台运行）
     * @return boolean 如果正在执行save操作，直接返回FALSE
     */
    public function bgSave () {
        return $this->redis->bgSave();
    }
    
    /**
     * 设置或获取服务器配置参数
     * @param string $key
     * @param mixed $value
     *  - null 执行GET操作
     *  - not null 执行SET操作
     * @return mixed
     *  - boolean 执行SET操作时
     *  - array 执行GET操作时返回key->value联合数组
     */
    public function config ($key, $value = null) {
        if(null === $value) {
            return $this->redis->config('GET', $key);
        } else {
            return $this->redis->config('SET', $key, $value);
        }
    }
    
    /**
     * 获取当前数据库keys的数量
     * @return int
     */
    public function dbSize () {
        return $this->redis->dbSize();
    }
    
    /**
     * 清除全部数据库的全部keys
     * @return boolean 始终返回TRUE
     */
    public function flushAll () {
        return $this->redis->flushAll();
    }
    
    /**
     * 清除当前数据库的全部keys
     * @return boolean 始终返回TRUE
     */
    public function flushDb () {
        return $this->redis->flushDB();
    }
    
    /**
     * 获取服务器统计信息
     * @param string $option
     *  - null 全部信息
     *  - not null 参数指定部分的信息
     * @return array
     */
    public function info ($option = null) {
        if(null === $option) return $this->redis->info();
        return $this->redis->info($option);
    }
    
    /**
     * 最后一次保存到硬盘的时间戳
     * @return int
     */
    public function lastSave () {
        return $this->redis->lastSave();
    }
    
    /**
     * 重置info统计
     * @return boolean
     */
    public function resetStat () {
        return $this->redis->resetStat();
    }
    
    /**
     * 同步方式保存数据到硬盘（等待完成）
     * @return boolean 如果正在执行save操作，直接返回FALSE
     */
    public function save () {
        return $this->redis->save();
    }
    
    /**
     * 切换从状态
     * 若参数为空，则停止从状态
     * @param string $host
     * @param int $port
     * @return boolean
     */
    public function slaveOf ($host = null, $port = null) {
        if(null === $host) return $this->redis->slaveOf();
        return $this->redis->slaveOf($host, $port);
    }
    
    /**
     * 返回服务器当前时间
     * @return array [时间戳, 毫秒]
     */
    public function time () {
        return $this->redis->time();
    }
    
    /**
     * 访问慢日志
     * @param $operation 操作类型
     *  - GET 获取日志信息
     *  - LEN 获取日志长度
     *  - RESET 重置日志
     * @param $option 操作参数，根据操作类型
     *  - GET 执行获取日志信息的条数，null不限制
     *  - LEN 无
     *  - RESET 无
     * @return mixed
     *  - array GET
     *  - int LEN
     *  - boolean RESET
     */
    public function slowLog ($operation, $option = null) {
        switch ($operation) {
            case 'GET' :
                if(null === $option) {
                    return $this->redis->slowLog($operation);
                } else {
                    return $this->redis->slowLog($operation, $option);
                }
            default :
                return $this->redis->slowLog($operation);
        }
    }
    
    /**
     * String:根据key获取对应值
     * @param string $key
     * @return mixed
     *  - string key存在返回对应值
     *  - boolean key不存在时返回FALSE
     */
    public function get ($key) {
        return $this->redis->get($key);
    }
    
    /**
     * String:根据key设置string值
     * @param string $key
     * @param mixed $value 存储为strval之后的结果
     * @param mixed $options long Timeout or array Options
     *  - Array('nx', 'ex'=>10) 如果不存在则设置，超时时间为10 seconds
     *  - Array('xx', 'px'=>1000) 如果存在则设置，超时时间为 1000 miliseconds
     * @return boolean
     */
    public function set ($key, $value, $options = null) {
        if(null === $options) {
            return $this->redis->set($key, $value);
        } else {
            return $this->redis->set($key, $value, $options);
        }
    }
    
    /**
     * String:根据key设置string值，同时指定过期时间
     * @param string $key
     * @param long $expire seconds
     * @param string $value
     * @return boolean
     */
    public function setEx ($key, $expire, $value) {
        return $this->redis->setEx($key, $expire, $value);
    }
    
    /**
     * String:根据key设置string值，同时指定过期时间
     * @param string $key
     * @param long $expire milliseconds
     * @param string $value
     * @return boolean
     */
    public function pSetEx ($key, $expire, $value) {
        return $this->redis->pSetEx($key, $expire, $value);
    }
    
    /**
     * String:当key不存在时，根据key设置string值
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function setNx ($key, $value) {
        return $this->redis->setNx($key, $value);
    }
    
    /**
     * 删除keys
     * @param array $keys
     * @return long 删除完成的数量
     */
    public function del (Array $keys) {
        return $this->redis->delete($keys);
    }
    
    /**
     * 判断key是否存在
     * @param string $key
     * @return boolean
     */
    public function exists ($key) {
        return $this->redis->exists($key);
    }
    
    /**
     * String:累加key值，若key不存在，先自动将key设置为0
     * @param string $key
     * @param mixed $value
     *  - null 自增1
     *  - int incrBy
     *  - float incrByFloat
     *  @return mixed 累加后的结果
     *   - int incrBy
     *   - float incrByFloat
     */
    public function incr ($key, $value = null) {
        if(null === $value) return $this->redis->incr();
        if(is_float($value)) {
            return $this->redis->incrByFloat($key, $value);
        }
        return $this->redis->incrBy($key, $value);
    }
    
    /**
     * String:递减key值，若key不存在，先自动将key设置为0
     * 暂时没有decrByFloat方法，decr、decrBy不能对float值进行操作
     * @param string $key
     * @param int $value
     *  - null 自减1
     *  - int decrBy
     *  @return int 累加后的结果
     */
    public function decr ($key, $value = null) {
        if(null === $value) return $this->redis->decr();
        return $this->redis->decrBy($key, $value);
    }
    
    /**
     * String:批量获取keys，如果key不存在，则对应位置返回为FALSE
     * @param array $keys
     * @return array
     */
    public function mGet (Array $keys) {
        return $this->redis->mGet($keys);
    }
    
    /**
     * String:根据key设置string值，并返回先前的值
     * @param string $key
     * @param string $value
     * @return string
     */
    public function getSet ($key, $value) {
        $this->redis->getSet($key, $value);
    }
    
    /**
     * 随机返回一个key
     * @return string
     */
    public function randomKey () {
        return $this->redis->randomKey();
    }
    
    /**
     * 将key移动到其他数据库
     * @param string $key
     * @param int $dbNumber
     * @return boolean
     */
    public function move ($key, $dbNumber) {
        return $this->redis->move($key, $dbNumber);
    }
    
    /**
     * 重命名key
     * @param string $srcKey
     * @param string $dstKey
     * @return boolean
     */
    public function rename ($srcKey, $dstKey) {
        return $this->redis->rename($srcKey, $dstKey);
    }
    
    /**
     * 当目标key名称不存在时，重命名key
     * @param string $srcKey
     * @param string $dstKey
     * @return boolean
     */
    public function renameNx ($srcKey, $dstKey) {
        return $this->redis->renameNx($srcKey, $dstKey);
    }
    
    /**
     * 设置过期时间
     * @param string $key
     * @param int $ttl seconds
     * @return boolean
     */
    public function expire ($key, $ttl) {
        return $this->redis->expire($key, $ttl);
    }
    
    /**
     * 设置过期时间
     * @param string $key
     * @param int $ttl milliseconds
     * @return boolean
     */
    public function pexpire ($key, $ttl) {
        return $this->redis->pexpire($key, $ttl);
    }
    
    /**
     * 设置过期时间
     * @param string $key
     * @param int $time 时间戳seconds
     * @return boolean
     */
    public function expireAt ($key, $time) {
        return $this->redis->expireAt($key, $time);
    }
    
    /**
     * 设置过期时间
     * @param string $key
     * @param int $time 时间戳milliseconds
     * @return boolean
     */
    public function pexpireAt ($key, $time) {
        return $this->redis->pexpireAt($key, $time);
    }
    
    /**
     * 匹配获取keys
     * @param string $pattern
     * @return array
     */
    public function keys ($pattern) {
        return $this->redis->keys($pattern);
    }
    
    /**
     * 扫描全部keys
     * @param long $iterator 游标，引用值，每次被调用之后， 都会向用户返回一个新的游标。
     *  用户在下次迭代时需要使用这个新游标作为 SCAN 命令的游标参数， 以此来延续之前的迭代过程。
     *  设置为NULL时，服务器将开始一次新的迭代，而当服务器向用户返回值为0的游标时，表示迭代已结束。
     * @param string $pattern
     * @param int $count 单次循环返回的key数量（仅为建议性）
     * @return mixed
     *  - array 单次扫描结果
     *  - boolean 没有更多结果时返回FALSE
     */
    public function scan (&$iterator, $pattern = null, $count = null) {
        if(null === $pattern && null === $count) {
            return $this->redis->scan($iterator);
        }
        if(null === $count) {
            return $this->redis->scan($iterator, $pattern);
        }
        return $this->redis->scan($iterator, $pattern, $count);
    }
    
    /**
     * 获取key的描述信息
     * @param string $retrieve 检索类型
     *  - encoding 数据类型
     *  - refcount 引用计数
     *  - idletime 闲置时间
     * @param string $key
     * @return mixed
     *  - string encoding
     *  - long refcount
     *  - long idletime
     *  - boolean key不存在时返回FALSE
     */
    public function object ($retrieve, $key) {
        return $this->redis->object($retrieve, $key);
    }
    
    /**
     * 获取key的类型
     * @param string $key
     * @return string
     */
    public function type ($key) {
        return $this->redis->type($key);
    }
    
    /**
     * 向key附加string值
     * @param string $key
     * @param string $value
     * @return int 附加后的长度
     */
    public function append ($key, $value) {
        return $this->redis->append($key, $value);
    }
    
    /**
     * String:根据key获取对应值指定范围内的字符串
     * @param string $key
     * @param long $start 负数为反方向
     * @param long $end 负数为反方向
     * @return string
     */
    public function getRange ($key, $start, $end) {
        return $this->redis->getRange($key, $start, $end);
    }
    
    /**
     * String:局部替换字符串
     * @param string $key
     * @param int $offset 开始替换位置
     * @param string $value
     */
    public function setRange ($key, $offset, $value) {
        return $this->redis->setRange($key, $offset, $value);
    }
    
    /**
     * String:获取字符串长度
     * @param string $key
     * @return int
     */
    public function strLen ($key) {
        return $this->redis->strLen($key);
    }
    
    /**
     * String:获取字符串的一个bit位
     * @param string $key
     * @param int $offset
     * @param long
     */
    public function getBit ($key, $offset) {
        return $this->redis->getBit($key, $offset);
    }
    
    /**
     * String:获取字符串的一个bit位
     * @param string $key
     * @param int $offset
     * @param mixed int or boolean
     * @param long 返回对应位置之前的值
     */
    public function setBit ($key, $offset, $value) {
        return $this->redis->getBit($key, $offset);
    }
    
    /**
     * String:位操作
     * @param string $operation
     *  - AND 与
     *  - OR 或
     *  - NOT 非
     *  - XOR 异或
     * @param string $destKey
     * @param array $keys
     * @return 目标key的字符串长度
     */
    public function bitOp ($operation, $destKey, Array $keys) {
        array_unshift($keys, $operation, $destKey);
        $method = new ReflectionMethod($this->redis, 'bitOp');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * String:计算给定字符串中，被设置为 1 的比特位的数量
     * @param string $key
     * @return long
     */
    public function bitCount ($key) {
        return $this->redis->bitCount($key);
    }
    
    /**
     * 对list, set or sorted set中的元素进行排序
     * @param string $key
     * @param array $option
     *  - by -> 'some_pattern_*',
     *  - limit -> array(0, 1),
     *  - get -> 'some_other_pattern_*' or an array of patterns,
     *  - sort -> 'asc' or 'desc',
     *  - alpha -> TRUE,
     *  - store -> 'external-key'
     *  @return mixed
     *   - array 排序后的值
     *   - int 如果设置了store参数，返回使用到的元素数量
     */
    public function sort ($key, Array $option = null) {
        if(null === $option) return $this->redis->sort($key);
        return $this->redis->sort($key, $option);
    }
    
    /**
     * 获取key的剩余生存时长
     * @param string $key
     * @return long seconds
     */
    public function ttl ($key) {
        return $this->redis->ttl($key);
    }
    
    /**
     * 获取key的剩余生存时长
     * @param string $key
     * @return long milliseconds
     */
    public function pttl ($key) {
        return $this->redis->pttl($key);
    }
    
    /**
     * 持久化key，使之永不过期（移除过期时间）
     * @param string $key
     * @return boolean 如果key不存在或key没有设置过期时间则返回FALSE
     */
    public function persist ($key) {
        return $this->redis->persist($key);
    }
    
    /**
     * String:批量设置键值对
     * @param array $array
     * @return boolean
     */
    public function mSet (Array $array) {
        return $this->redis->mSet($array);
    }
    
    /**
     * String:当对应key不存在时，批量设置键值对
     * @param array $array
     * @return boolean 只有当全部key设置成功时才返回TRUE
     */
    public function mSetNx (Array $array) {
        return $this->redis->mSetNx($array);
    }
    
    /**
     * 序列化给定 key ，并返回被序列化的值
     * 使用 RESTORE 命令可以将这个值反序列化
     * @param string $key
     * @return mixed
     *  - string 对应值的序列化字符串
     *  - boolean 当key不存在时返回FALSE
     */
    public function dump ($key) {
        return $this->redis->dump($key);
    }
    
    /**
     * 反序列化给定的序列化值，并将它和给定的key关联
     * @param string $key
     * @param int $ttl 若为0则不设置过期时间
     * @param string $value 序列化字符串
     * @return boolean
     */
    public function restore ($key, $ttl, $value) {
        return $this->redis->restore($key, $ttl, $value);
    }
    
    /**
     * 迁移key到其他实例（阻塞执行）
     * @param string $host
     * @param int $port
     * @param string $key
     * @param int $destDB
     * @param int $timeout 传输超时milliseconds
     * @param boolean $copy 移除原实例上的key
     * @param boolean $replace 替换目标实例上的key
     * @return boolean
     */
    public function migrate ($host, $port, $key, $destDB, $timeout, $copy = false, $replace = false) {
        return $this->redis->migrate($host, $port, $key, $destDB, $timeout, $copy, $replace);
    }
    
    /**
     * Hash:根据hashKey设置string值
     * @param string $key
     * @param string $hashKey
     * @param string $value
     * @return mixed
     *  - int 操作成功
     *   - 1 hashKey不存在并且设置成功
     *   - 0 hashKey存在并且替换成功
     *  - boolean 操作失败返回FALSE
     */
    public function hSet ($key, $hashKey, $value) {
        return $this->redis->hSet($key, $hashKey, $value);
    }
    
    /**
     * Hash:当key不存在时，根据hashKey设置string值
     * @param string $key
     * @param string $hashKey
     * @param string $value
     * @return boolean
     */
    public function hSetNx ($key, $hashKey, $value) {
        return $this->redis->hSetNx($key, $hashKey, $value);
    }
    
    /**
     * Hash:根据hashKey获取指定hash中的string值
     * @param string $key
     * @param string $hashKey
     * @return mixed
     *  - string
     *   - boolean 当key不存在时返回FALSE
     */
    public function hGet ($key, $hashKey) {
        return $this->redis->hGet($key, $hashKey);
    }
    
    /**
     * Hash:获取指定hash中元素数量
     * @param string $key
     * @return mixed
     *  - long
     *  - boolean 当key不存在时返回FALSE
     */
    public function hLen ($key) {
        return $this->redis->hLen($key);
    }
    
    /**
     * Hash:删除指定hash中的hashKeys
     * @param string $key
     * @param array $hashKeys
     * @return mixed
     *  - long 删除完成的数量
     *  - boolean 如果key不存在或key不是一个Hash则返回FALSE
     */
    public function hDel ($key, Array $hashKeys) {
        array_unshift($hashKeys, $key);
        $method = new ReflectionMethod($this->redis, 'hDel');
        return $method->invokeArgs($this->redis, $hashKeys);
    }
    
    /**
     * Hash:获取指定hash中的全部keys
     * @param string $key
     * @return array 顺序随机
     */
    public function hKeys ($key) {
        return $this->redis->hKeys($key);
    }
    
    /**
     * Hash:获取指定hash中的全部值
     * @param string $key
     * @return array 顺序随机
     */
    public function hVals ($key) {
        return $this->redis->hVals($key);
    }
    
    /**
     * Hash:获取指定hash中的全部键值对
     * @param string $key
     * @return array 顺序随机
     */
    public function hGetAll ($key) {
        return $this->redis->hGetAll($key);
    }
    
    /**
     * Hash:判断指定hash中的hashKey是否存在
     * @param string $key
     * @param string $hashKey
     * @return boolean
     */
    public function hExists ($key, $hashKey) {
        return $this->redis->hExists($key, $hashKey);
    }
    
    /**
     * Hash:累加指定hash中hashKey对应的值
     * @param string $key
     * @param string $hashKey
     * @param int $value
     *  - int hIncrBy
     *  - float hIncrByFloat
     * @return mixed 累加后的值
     *  - int hIncrBy
     *  - float hIncrByFloat
     */
    public function hIncr ($key, $hashKey, $value = null) {
        if(null === $value) {
            $value = 1;
        } else if(is_float($value)) {
            return $this->redis->hIncrByFloat($key, $hashKey, $value);
        }
        return $this->redis->hIncrBy($key, $hashKey, $value);
    }
    
    /**
     * Hash:批量设置指定hash的键值对
     * @param string $key
     * @param array $array
     * @return boolean
     */
    public function hMSet ($key, Array $array) {
        return $this->redis->hMSet($key, $array);
    }
    
    /**
     * Hash:批量获取指定hash的键值对
     * @param string $key
     * @param array $hashKeys
     * @return array
     */
    public function hMGet ($key, Array $hashKeys) {
        return $this->redis->hMGet($key, $hashKeys);
    }
    
    /**
     * Hash:扫描指定hash的全部键值对
     * @param string $key
     * @param long $iterator 游标，引用值，每次被调用之后， 都会向用户返回一个新的游标。
     *  用户在下次迭代时需要使用这个新游标作为 SCAN 命令的游标参数， 以此来延续之前的迭代过程。
     *  设置为NULL时，服务器将开始一次新的迭代，而当服务器向用户返回值为0的游标时，表示迭代已结束。
     * @param string $pattern
     * @param int $count 单次循环返回的key数量（仅为建议性）
     * @return mixed
     *  - array 单次扫描结果
     *  - boolean 没有更多结果时返回FALSE
     */
    public function hScan ($key, &$iterator, $pattern = null, $count = null) {
        if(null === $pattern && null === $count) {
            return $this->redis->hScan($key, $iterator);
        }
        if(null === $count) {
            return $this->redis->hScan($key, $iterator, $pattern);
        }
        return $this->redis->hScan($key, $iterator, $pattern, $count);
    }
    
    /**
     * List:阻塞式弹出指定list中左部元素
     * @param array $keys 当给定多个 key 参数时，按参数 key 的先后顺序依次检查各个列表，弹出第一个非空列表的头元素
     * @param int $timeout seconds 如果为0，当没有可弹出元素时则无限等待
     * @return array ['listName', 'element']
     */
    public function blPop (Array $keys, $timeout) {
        return $this->redis->blPop($keys, $timeout);
    }
    
    /**
     * List:阻塞式弹出指定list中右部元素
     * @param array $keys 当给定多个 key 参数时，按参数 key 的先后顺序依次检查各个列表，弹出第一个非空列表的尾元素
     * @param int $timeout seconds 如果为0，当没有可弹出元素时则无限等待
     * @return array ['listName', 'element']
     */
    public function brPop (Array $keys, $timeout) {
        return $this->redis->brPop($keys, $timeout);
    }
    
    /**
     * List:阻塞式弹出指定$srcKey中的右部元素，并将该元素推入$dstKey中的左部
     * @param string $srcKey
     * @param string $dstKey
     * @param long $timeout seconds 如果为0，当没有可弹出元素时则无限等待
     * @return mixed
     *  - string 成功
     *  - boolean 超时返回FALSE
     */
    public function bRPopLPush ($srcKey, $dstKey, $timeout) {
        return $this->redis->bRPopLPush($srcKey, $dstKey, $timeout);
    }
    
    /**
     * List:获取指定list中对应位置的元素
     * @param string $key
     * @param int $index
     * @return mixed
     *  - string
     *  - boolean 如果对应key不是list或对应位置不存在则返回FALSE
     */
    public function lIndex ($key, $index) {
        return $this->redis->lIndex($key, $index);
    }
    
    /**
     * List:在对应位置插入元素
     * @param string $key
     * @param string $position 设置是在$pivot参照元素[之前|之后]插入
     *  - Redis::BEFORE 之前
     *  - Redis::AFTER 之后
     *  @param string $pivot 参照元素的值
     *  @param string $value
     *  @return int 列表中的元素个数，如果参照元素不存在则返回-1
     */
    public function lInsert ($key, $position, $pivot, $value) {
        return $this->redis->lInsert($key, $position, $pivot, $value);
    }
    
    /**
     * List:弹出并返回指定list中的第一个元素
     * @param string $key
     * @return mixed
     *  - string 成功
     *  - boolean 失败或列表为空
     */
    public function lPop ($key) {
        return $this->redis->lPop($key);
    }
    
    /**
     * List:推入元素到指定list的头部
     * @param string $key 如果指定key不存在，则创建一个list
     * @param array $values
     * @return mixed
     *  - long 列表长度
     *  - boolean 如果对应key不是一个list则返回FALSE
     */
    public function lPush ($key, Array $values) {
        array_unshift($values, $key);
        $method = new ReflectionMethod($this->redis, 'lPush');
        return $method->invokeArgs($this->redis, $values);
    }
    
    /**
     * List:推入元素到已存在的list的头部
     * @param string $key 对应key必须存在，并且是一个list
     * @param string $value
     * @return mixed
     *  - long 列表长度
     *  - boolean 失败
     */
    public function lPushx ($key, $value) {
        return $this->redis->lPushx($key, $value);
    }
    
    /**
     * List:获取list中指定范围的元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array
     */
    public function lRange ($key, $start, $end) {
        return $this->redis->lRange($key, $start, $end);
    }
    
    /**
     * List:移除list中的指定元素
     * @param string $key
     * @param string $value
     * @param int $count
     *  - 0 移除全部
     *  - gt 0 从头至尾
     *  - lt 0 从尾至头
     *  @return mixed
     *   - long 移除的元素个数
     *   - boolean 如果key对应的值不为list则返回FALSE
     */
    public function lRem ($key, $value, $count) {
        return $this->redis->lRem($key, $value, $count);
    }
    
    /**
     * List:在list的指定位置上设置新值
     * @param string $key
     * @param int $index
     * @param string $value
     * @return boolean 如果key对应的值不为list或index超出范围则返回FALSE
     */
    public function lSet ($key, $index, $value) {
        return $this->redis->lSet($key, $index, $value);
    }
    
    /**
     * List:修剪list，只保留指定区间内的元素，不在指定区间之内的元素都将被删除
     * @param string $key
     * @param int $start 含
     * @param int $stop 含，可以为负数
     * @return boolean
     */
    public function lTrim ($key, $start, $stop) {
        return $this->redis->lTrim($key, $start, $stop);
    }
    
    /**
     * List:弹出并返回list中的最后一个元素
     * @param string $key
     * @return mixed
     *  - string
     *  - boolean 如果list为空则返回FALSE
     */
    public function rPop ($key) {
        return $this->redis->rPop($key);
    }
    
    /**
     * List:弹出指定$srcKey中的右部元素，并将该元素推入$dstKey中的左部
     * @param string $srcKey
     * @param string $dstKey
     * @return mixed
     *  - string 成功
     *  - boolean 失败
     */
    public function rPopLPush ($srcKey, $dstKey) {
        return $this->redis->rPopLPush($srcKey, $dstKey);
    }
    
    /**
     * List:推入值到list的右部
     * @param string $key 如果对应key不存在则创建一个list
     * @param array $value
     * @return mixed
     *  - long 列表的长度
     *  - boolean 如果对应key不是一个list则返回FALSE
     */
    public function rPush ($key, Array $values) {
        array_unshift($values, $key);
        $method = new ReflectionMethod($this->redis, 'rPush');
        return $method->invokeArgs($this->redis, $values);
    }
    
    /**
     * List:推入值到一个存在的list的右部
     * @param string $key 对应key必须存在且是一个list
     * @param string $value
     * @return mixed
     *  - long 列表的长度
     *  - boolean 失败
     */
    public function rPushX ($key, $value) {
        return $this->redis->rPushX($key, $value);
    }
    
    /**
     * List:获取对应list的大小
     * @param string $key
     * @return mixed
     *  - long 如果对应key不存在或list为空则返回0
     *  - boolean 如果对应key不是一个list则返回FALSE
     */
    public function lLen ($key) {
        return $this->redis->lLen($key);
    }
    
    /**
     * Set:添加值到指定set中
     * @param string $key
     * @param array $values
     * @return mixed
     *  - long set中的元素个数
     *  - boolean 如果value已存在则返回FALSE
     */
    public function sAdd ($key, Array $values) {
        array_unshift($values, $key);
        $method = new ReflectionMethod($this->redis, 'sAdd');
        return $method->invokeArgs($this->redis, $values);
    }
    
    /**
     * Set:获取set的基数（集合中元素的个数）
     * @param string $key
     * @return long 如果对应set不存在则返回0
     */
    public function sCard ($key) {
        return $this->redis->sCard($key);
    }
    
    /**
     * Set:计算$keys中第一个集合与其他集合的差集
     * @param array $keys
     * @return array
     */
    public function sDiff (Array $keys) {
        $method = new ReflectionMethod($this->redis, 'sDiff');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * Set:计算$keys中第一个集合与其他集合的差集，并将结果保存在$key中
     * @param string $key
     * @param array $keys
     * @return int 差集中的元素个数
     */
    public function sDiffStore ($key, Array $keys) {
        array_unshift($keys, $key);
        $method = new ReflectionMethod($this->redis, 'sDiffStore');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * Set:计算多个集合的交集
     * @param array $keys
     * @return array
     */
    public function sInter (Array $keys) {
        $method = new ReflectionMethod($this->redis, 'sInter');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * Set:计算$keys中指定集合的交集，并将结果保存在$key中
     * @param string $key 如果key已存在则覆盖
     * @param array $keys
     * @return int 交集中的元素个数
     */
    public function sInterStore ($key, Array $keys) {
        array_unshift($keys, $key);
        $method = new ReflectionMethod($this->redis, 'sInterStore');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * Set:判断set是否包含指定value
     * @param string $key
     * @param string $value
     * @return boolean 成员顺序随机
     */
    public function sIsMember ($key, $value) {
        return $this->redis->sIsMember($key, $value);
    }
    
    /**
     * Set:获取set全部成员
     * @param string $key
     * @return array
     */
    public function sMembers ($key) {
        return $this->redis->sMembers($key);
    }
    
    /**
     * Set:将$srcKey中的$member移动到$dstKey中
     * @param string $srcKey
     * @param string $dstKey
     * @param string $member
     * @return boolean
     */
    public function sMove ($srcKey, $dstKey, $member) {
        return $this->redis->sMove($srcKey, $dstKey, $member);
    }
    
    /**
     * Set:随机移除并返回set中的一个成员
     * @param string $key
     * @return mixed
     *  - string 成功
     *  - boolean 如果set为空或不存在则返回FALSE
     */
    public function sPop ($key) {
        return $this->redis->sPop($key);
    }
    
    /**
     * Set:随机返回但不移除set中指定个数的成员
     * @param string $key
     * @param int $count
     * @return mixed
     *  - string 如果不指定$count则返回单个成员字符串
     *  - array 返回指定数量的成员数组，$count为1时也是返回数组
     *  - boolean 如果set为空或不存在则返回FALSE
     */
    public function sRandMember ($key, $count = null) {
        if(null === $count) return $this->redis->sRandMember($key);
        return $this->redis->sRandMember($key, $count);
    }
    
    /**
     * Set:移除set中的指定成员
     * @param string $key
     * @param array $members
     * @return long 完成移除的成员个数
     */
    public function sRem ($key, Array $members) {
        array_unshift($members, $key);
        $method = new ReflectionMethod($this->redis, 'sRem');
        return $method->invokeArgs($this->redis, $members);
    }
    
    /**
     * Set:返回多个set的并集
     * @param array $keys
     * @return array
     */
    public function sUnion (Array $keys) {
        $method = new ReflectionMethod($this->redis, 'sUnion');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * Set:计算$keys中全部set的并集，并将结果保存在$key中
     * @param string $key
     * @param array $keys
     * @return mixed
     *  - int 并集元素的个数
     *  - boolean key异常时返回FALSE
     */
    public function sUnionStore ($key, Array $keys) {
        array_unshift($keys, $key);
        $method = new ReflectionMethod($this->redis, 'sUnionStore');
        return $method->invokeArgs($this->redis, $keys);
    }
    
    /**
     * Set:扫描指定set的全部成员
     * @param string $key
     * @param long $iterator 游标，引用值，每次被调用之后， 都会向用户返回一个新的游标。
     *  用户在下次迭代时需要使用这个新游标作为 SCAN 命令的游标参数， 以此来延续之前的迭代过程。
     *  设置为NULL时，服务器将开始一次新的迭代，而当服务器向用户返回值为0的游标时，表示迭代已结束。
     * @param string $pattern
     * @param int $count 单次循环返回的key数量（仅为建议性）
     * @return mixed
     *  - array 单次扫描结果
     *  - boolean 没有更多结果时返回FALSE
     */
    public function sScan ($key, &$iterator, $pattern = null, $count = null) {
        if(null === $pattern && null === $count) {
            return $this->redis->sScan($key, $iterator);
        }
        if(null === $count) {
            return $this->redis->sScan($key, $iterator, $pattern);
        }
        return $this->redis->sScan($key, $iterator, $pattern, $count);
    }
    
    /**
     * SortedSet:向有序集中添加多个带评分的值
     * @param string $key 如果key对应的sorted set不存在则创建
     * @param array $values [$value => $score] 如果$value已存在，则更新对应的$score
     * @return mixed
     *  - int 成功添加或更新的元素个数
     *  - boolean 如果key不是一个sorted set则返回FALSE
     */
    public function zAdd ($key, Array $values) {
        $args = array($key);
        foreach ($values as $value => $score) {
            $args[] = $score;
            $args[] = $value;
        }
        $method = new ReflectionMethod($this->redis, 'zAdd');
        return $method->invokeArgs($this->redis, $args);
    }
    
    /**
     * SortedSet:返回有序集中元素的个数
     * @param string $key
     * @return long
     */
    public function zCard ($key) {
        return $this->redis->zCard($key);
    }
    
    /**
     * SortedSet:返回有序集中score值在 min（含）和 max（含）之间的成员数量
     * @param string $key
     * @param double $start
     * @param double $end
     * @return long
     */
    public function zCount ($key, $start, $end) {
        return $this->redis->zCount($key, $start, $end);
    }
    
    /**
     * SortedSet:为有序集成员$member的$score值加上增量$increment
     * @param string $key
     * @param double $increment
     * @param string $member 如果对应成员不存在，则先将评分设置为0，然后再累加
     * @return double 增加后的评分值
     */
    public function zIncrBy ($key, $increment, $member) {
        $this->redis->zIncrBy($key, $increment, $member);
    }
    
    /**
     * SortedSet:计算$keys中全部有序集的交集，并将结果保存在$key中
     * @param string $key
     * @param array $weightKeys [$key => $weight] 有序集$key在传递给聚合函数之前都要先乘以该有序集的因子$weight
     * @param string $aggregate 聚合时采用的计算函数
     *  - MAX 取最大值
     *  - MIN 取最小值
     *  - SUM 取加和值（默认）
     *  @return long 交集中元素的个数
     */
    public function zInterStore ($key, Array $weightKeys, $aggregate = null) {
        if(null === $aggregate) $aggregate = 'SUM';
        return $this->redis->zInter($key, array_keys($weightKeys), array_values($weightKeys), $aggregate);
    }
    
    /**
     * SortedSet:获取有序集中指定范围内的元素
     * @param string $key
     * @param long $start
     * @param long $end
     * @param boolean $withScores 是否同时获取评分
     * @return array [$value]或[$value => $score]
     */
    public function zRange ($key, $start, $end, $withScores = false) {
        return $this->redis->zRange($key, $start, $end, $withScores);
    }
    
    /**
     * SortedSet:获取指定评分范围内的元素
     * @param string $key
     * @param mixed $start
     *  - double
     *  - string "-inf"最小值不限，"+inf"最大值不限
     * @param mixed $end 参照$start
     * @param array $options
     *  - withscores true|false是否包含评分
     *  - limit array($offset, $count)
     * @return array [$value]或[$value => $score]
     */
    public function zRangeByScore ($key, $start, $end, Array $options = array()) {
        return $this->redis->zRangeByScore($key, $start, $end, $options);
    }
    
    /**
     * SortedSet:获取指定匹配值范围内的元素
     * @param string $key
     * @param string $min
     *  - "-" 最小值不限
     *  - "(Lex" 不包含Lex
     *  - "[Lex" 包含Lex
     *  - "+" 最大值不限
     * @param string $max 参照$min
     * @param long $offset 与$limit同时指定时生效
     * @param long $limit
     * @return array
     */
    public function zRangeByLex ($key, $min, $max, $offset = null, $limit = null) {
        if(null === $offset && null === $limit) {
            return $this->redis->zRangeByLex($key, $min, $max);
        }
        return $this->redis->zRangeByLex($key, $min, $max, $offset, $limit);
    }
    
    /**
     * SortedSet:获取成员在有序集中的排名，按score值递增（从小到大）
     * @param string $key
     * @param string $member
     * @return long 从0开始的排名
     */
    public function zRank ($key, $member) {
        return $this->redis->zRank($key, $member);
    }
    
    /**
     * SortedSet:获取成员在有序集中的排名，按score值递减（从大到小）
     * @param string $key
     * @param string $member
     * @return long 从0开始的排名
     */
    public function zRevRank ($key, $member) {
        return $this->redis->zRevRank($key, $member);
    }
    
    /**
     * SortedSet:移除有序集中的元素
     * @param string $key
     * @param array $members
     * @return 移除的元素个数
     */
    public function zRem ($key, Array $members) {
        array_unshift($members, $key);
        $method = new ReflectionMethod($this->redis, 'zRem');
        return $method->invokeArgs($this->redis, $members);
    }
    
    /**
     * SortedSet:移除指定排名范围内的元素
     * @param string $key
     * @param long $start
     * @param long $end
     * @return 移除的元素个数
     */
    public function zRemRangeByRank ($key, $start, $end) {
        return $this->redis->zRemRangeByRank($key, $start, $end);
    }
    
    /**
     * SortedSet:移除指定评分范围内的元素
     * @param string $key
     * @param mixed $start
     *  - double
     *  - string "-inf"最小值不限，"+inf"最大值不限
     * @param mixed $end 参照$start
     * @return 移除的元素个数
     */
    public function zRemRangeByScore ($key, $start, $end) {
        return $this->redis->zRemRangeByScore($key, $start, $end);
    }
    
    /**
     * SortedSet:获取反向有序集中指定范围内的元素
     * @param string $key
     * @param long $start
     * @param long $end
     * @param boolean $withScores 是否同时获取评分
     * @return array [$value]或[$value => $score]
     */
    public function zRevRange ($key, $start, $end, $withScores = false) {
        return $this->redis->zRevRange($key, $start, $end, $withScores);
    }
    
    /**
     * SortedSet:获取元素的评分
     * @param string $key
     * @param string $member
     * @return mixed
     *  - double
     *  - boolean 对应元素不存在时返回FALSE
     */
    public function zScore ($key, $member) {
        return $this->redis->zScore($key, $member);
    }
    
    /**
     * SortedSet:计算$keys中全部有序集的并集，并将结果保存在$key中
     * @param string $key
     * @param array $weightKeys [$key => $weight] 有序集$key在传递给聚合函数之前都要先乘以该有序集的因子$weight
     * @param string $aggregate 聚合时采用的计算函数
     *  - MAX 取最大值
     *  - MIN 取最小值
     *  - SUM 取加和值（默认）
     *  @return long 并集中元素的个数
     */
    public function zUnionStore ($key, Array $weightKeys, $aggregate = null) {
        if(null === $aggregate) $aggregate = 'SUM';
        return $this->redis->zUnionStore($key, array_keys($weightKeys), array_values($weightKeys), $aggregate);
    }
    
    /**
     * SortedSet:扫描指定有序集的全部成员
     * @param string $key
     * @param long $iterator 游标，引用值，每次被调用之后， 都会向用户返回一个新的游标。
     *  用户在下次迭代时需要使用这个新游标作为 SCAN 命令的游标参数， 以此来延续之前的迭代过程。
     *  设置为NULL时，服务器将开始一次新的迭代，而当服务器向用户返回值为0的游标时，表示迭代已结束。
     * @param string $pattern
     * @param int $count 单次循环返回的key数量（仅为建议性）
     * @return mixed
     *  - array 单次扫描结果
     *  - boolean 没有更多结果时返回FALSE
     */
    public function zScan ($key, &$iterator, $pattern = null, $count = null) {
        if(null === $pattern && null === $count) {
            return $this->redis->zScan($key, $iterator);
        }
        if(null === $count) {
            return $this->redis->zScan($key, $iterator, $pattern);
        }
        return $this->redis->zScan($key, $iterator, $pattern, $count);
    }
    
    /**
     * 阻塞式订阅给定模式的频道信息
     * @param array $patterns
     * @param callable $callback ($redis, $pattern, $channel, $message)
     * @return mixed 返回$callback的return值
     */
    public function pSubscribe (Array $patterns, Callable $callback) {
        return $this->redis->pSubscribe($patterns, $callback);
    }
    
    /**
     * 发布信息
     * @param string $channel
     * @param string $messsage
     * @return long 接收到的订阅者数量
     */
    public function publish ($channel, $messsage) {
        return $this->redis->publish($channel, $messsage);
    }
    
    /**
     * 阻塞式订阅给定的频道信息
     * @param array $patterns
     * @param callable $callback ($redis, $channel, $message)
     * @return mixed 返回$callback的return值
     */
    public function subscribe (Array $channels, Callable $callback) {
        return $this->redis->subscribe($channels, $callback);
    }
    
    /**
     * 订阅系统管理
     * @param string $command
     *  - channels 获取频道列表
     *  - numsub 获取给定频道的订阅者数量
     *  - numpat 获取采用频道匹配模式的订阅者数量
     * @param mixed $arguments
     *  - channels
     *   - null 获取全部
     *   - string 匹配模式字符串
     *  - numsub array($channel)频道字符串数组
     *  - numpat 无
     * @return mixed
     *  - array channels
     *  - array numsub [$channel => $number]
     *  - int numpat
     */
    public function pubSub ($command, $arguments = null) {
        if(null === $arguments) {
            return $this->redis->pubSub($command);
        } else {
            return $this->redis->pubSub($command, $arguments);
        }
    }
    
    /**
     * 在服务器上执行任何通用命令
     * @return mixed
     */
    public function rawCommand () {
        $method = new ReflectionMethod($this->redis, 'rawCommand');
        return $method->invokeArgs($this->redis, func_get_args());
    }
    
    /**
     * Transaction:批量执行
     * $redis->multi()->any()->exec|discard();
     * @return array 调用exec后，按执行顺序返回每条命令的返回值
     */
    public function multi () {
        return $this->redis->multi();
    }
    
    /**
     * Transaction:监视给定$keys的变化
     * 如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断
     * @param array $keys
     */
    public function watch (Array $keys) {
        $this->redis->wait($keys);
    }
    
    /**
     * Transaction:取消监视给定$keys的变化
     * @param array $keys
     */
    public function unwatch (Array $keys) {
        $this->redis->unwatch($keys);
    }
    
    /**
     * 客户端管理
     * @param string $command
     *  - LIST 获取客户端列表
     *  - GETNAME 获取当前连接名称
     *  - SETNAME 设置当前连接名称
     *  - KILL 端口指定客户端连接
     * @param mixed $arguments
     *  - LIST 无
     *  - GETNAME 无
     *  - SETNAME string
     *  - KILL string ip:port
     *  @return mixed
     */
    public function client ($command, $arguments) {
        if(null === $arguments) return $this->redis->client($command);
        return $this->redis->client($command, $arguments);
    }
    
    /**
     * 获取最后一次错误信息
     * @return string
     */
    public function getLastError () {
        return $this->redis->getLastError();
    }
    
    /**
     * 清楚错误信息
     * @return boolean
     */
    public function clearLastError () {
        return $this->redis->clearLastError();
    }
    
    /**
     * 判断客户端是否已连接
     * @return boolean
     */
    public function isConnected () {
        return $this->redis->isConnected();
    }
    
    /**
     * 获取当前连接服务器主机名称或套接字
     * @return mixed
     *  - string
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getHost () {
        return $this->redis->getHost();
    }
    
    /**
     * 获取当前连接服务器端口号
     * @return mixed
     *  - int
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getPort () {
        return $this->redis->getPort();
    }
    
    /**
     * 获取当前数据库
     * @return mixed
     *  - long
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getDbNum () {
        return $this->redis->getDBNum();
    }
    
    /**
     * 获取（写）超时时间
     * @return mixed
     *  - double
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getTimeout () {
        return $this->redis->getTimeout();
    }
    
    /**
     * 获取读超时时间
     * @return mixed
     *  - double 通过Redis::OPT_READ_TIMEOUT设置
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getReadTimeout () {
        return $this->redis->getReadTimeout();
    }
    
    /**
     * 获取通过pconnect连接时设置的持久化标识
     * @return mixed
     *  - string
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getPersistentID () {
        return $this->redis->getPersistentID();
    }
    
    /**
     * 获取认证时使用的密码
     * @return mixed
     *  - string
     *  - boolean 如果没有连接则返回FALSE
     */
    public function getAuth () {
        return $this->redis->getAuth();
    }

}
