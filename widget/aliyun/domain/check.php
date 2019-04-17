<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
class CurlUtil {
	private $resource = null;
	private $headers = null;
	private $timeout = 300;

	public function setHeaders(array $headers) {
		$this->headers = $headers;
		return $this;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
		return $this;
	}

	public function getTimeout() {
		return $this->timeout;
	}

	public function __construct() {
		$this->resource = curl_init();
	}

	public function reset() {
		if(null != $this->resource) curl_close($this->resource);
		$this->resource = curl_init();
	}

	public function httpsGet($url) {
		curl_setopt($this->resource, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		curl_setopt($this->resource, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
		curl_setopt($this->resource, CURLOPT_URL, $url);
		if($this->headers) {
			curl_setopt($this->resource, CURLOPT_HTTPHEADER, $this->headers);
		}
		curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->resource, CURLOPT_TIMEOUT, $this->timeout);
		$response = curl_exec($this->resource);
		$error = curl_error($this->resource);
		if($error) $response = null;
		return $response;
	}

	public function obCallback($buffer) {
    	return $buffer . str_repeat(' ', max(0, 4097 - strlen($buffer)));
    }
	
	public function flushMessage($message, $bWithBR = false, $bFlush = false) {
        if($bWithBR) {
            echo '<br>';
        }
        echo $message;
        if($bFlush) {
            ob_end_flush();
            ob_flush();
            flush();
            ob_start(array($this, 'obCallback'));
        }
        return true;
    }

    public function __destruct() {
		if(null != $this->resource) curl_close($this->resource);
	}
}

$pinyins = array(
	'a',
    'ai',
    'an',
    'ang',
    'ao',
    'ba',
    'bai',
    'ban',
    'bang',
    'bao',
    'bei',
    'ben',
    'beng',
    'bi',
    'bian',
    'biao',
    'bie',
    'bin',
    'bing',
    'bo',
    'bu',
    'ca',
    'cai',
    'can',
    'cang',
    'cao',
    'ce',
    'cen',
    'ceng',
    'cha',
    'chai',
    'chan',
    'chang',
    'chao',
    'che',
    'chen',
    'cheng',
    'chi',
    'chong',
    'chou',
    'chu',
    'chuai',
    'chuan',
    'chuang',
    'chui',
    'chun',
    'chuo',
    'ci',
    'cong',
    'cou',
    'cu',
    'cuan',
    'cui',
    'cun',
    'cuo',
    'da',
    'dai',
    'dan',
    'dang',
    'dao',
    'de',
    'dei',
    'deng',
    'di',
    'dia',
    'dian',
    'diao',
    'die',
    'ding',
    'diu',
    'dong',
    'dou',
    'du',
    'duan',
    'dui',
    'dun',
    'duo',
    'e',
    'ei',
    'en',
    'er',
    'fa',
    'fan',
    'fang',
    'fei',
    'fen',
    'feng',
    'fo',
    'fou',
    'fu',
    'ga',
    'gai',
    'gan',
    'gang',
    'gao',
    'ge',
    'gei',
    'gen',
    'geng',
    'gong',
    'gou',
    'gu',
    'gua',
    'guai',
    'guan',
    'guang',
    'gui',
    'gun',
    'guo',
    'ha',
    'hai',
    'han',
    'hang',
    'hao',
    'he',
    'hei',
    'hen',
    'heng',
    'hong',
    'hou',
    'hu',
    'hua',
    'huai',
    'huan',
    'huang',
    'hui',
    'hun',
    'huo',
    'ji',
    'jia',
    'jian',
    'jiang',
    'jiao',
    'jie',
    'jin',
    'jing',
    'jiong',
    'jiu',
    'ju',
    'juan',
    'jue',
    'jun',
    'ka',
    'kai',
    'kan',
    'kang',
    'kao',
    'ke',
    'ken',
    'keng',
    'kong',
    'kou',
    'ku',
    'kua',
    'kuai',
    'kuan',
    'kuang',
    'kui',
    'kun',
    'kuo',
    'la',
    'lai',
    'lan',
    'lang',
    'lao',
    'le',
    'lei',
    'leng',
    'li',
    'lia',
    'lian',
    'liang',
    'liao',
    'lie',
    'lin',
    'ling',
    'liu',
    'lo',
    'long',
    'lou',
    'lu',
    'luan',
    'lue',
    'lun',
    'luo',
    'lv',
    'lve',
    'm',
    'ma',
    'mai',
    'man',
    'mang',
    'mao',
    'me',
    'mei',
    'men',
    'meng',
    'mi',
    'mian',
    'miao',
    'mie',
    'min',
    'ming',
    'miu',
    'mo',
    'mou',
    'mu',
    'na',
    'nai',
    'nan',
    'nang',
    'nao',
    'ne',
    'nei',
    'nen',
    'neng',
    'ng',
    'ni',
    'nian',
    'niang',
    'niao',
    'nie',
    'nin',
    'ning',
    'niu',
    'nong',
    'nou',
    'nu',
    'nuan',
    'nue',
    'nuo',
    'nv',
    'nve',
    'o',
    'ou',
    'pa',
    'pai',
    'pan',
    'pang',
    'pao',
    'pei',
    'pen',
    'peng',
    'pi',
    'pian',
    'piao',
    'pie',
    'pin',
    'ping',
    'po',
    'pou',
    'pu',
    'qi',
    'qia',
    'qian',
    'qiang',
    'qiao',
    'qie',
    'qin',
    'qing',
    'qiong',
    'qiu',
    'qu',
    'quan',
    'que',
    'qun',
    'ran',
    'rang',
    'rao',
    're',
    'ren',
    'reng',
    'ri',
    'rong',
    'rou',
    'ru',
    'ruan',
    'rui',
    'run',
    'ruo',
    'sa',
    'sai',
    'san',
    'sang',
    'sao',
    'se',
    'sen',
    'seng',
    'sha',
    'shai',
    'shan',
    'shang',
    'shao',
    'she',
    'shei',
    'shen',
    'sheng',
    'shi',
    'shou',
    'shu',
    'shua',
    'shuai',
    'shuan',
    'shuang',
    'shui',
    'shun',
    'shuo',
    'si',
    'song',
    'sou',
    'su',
    'suan',
    'sui',
    'sun',
    'suo',
    'ta',
    'tai',
    'tan',
    'tang',
    'tao',
    'te',
    'teng',
    'ti',
    'tian',
    'tiao',
    'tie',
    'ting',
    'tong',
    'tou',
    'tu',
    'tuan',
    'tui',
    'tun',
    'tuo',
    'wa',
    'wai',
    'wan',
    'wang',
    'wei',
    'wen',
    'weng',
    'wo',
    'wu',
    'xi',
    'xia',
    'xian',
    'xiang',
    'xiao',
    'xie',
    'xin',
    'xing',
    'xiong',
    'xiu',
    'xu',
    'xuan',
    'xue',
    'xun',
    'ya',
    'yan',
    'yang',
    'yao',
    'ye',
    'yi',
    'yin',
    'ying',
    'yo',
    'yong',
    'you',
    'yu',
    'yuan',
    'yue',
    'yun',
    'za',
    'zai',
    'zan',
    'zang',
    'zao',
    'ze',
    'zei',
    'zen',
    'zeng',
    'zha',
    'zhai',
    'zhan',
    'zhang',
    'zhao',
    'zhe',
    'zhen',
    'zheng',
    'zhi',
    'zhong',
    'zhou',
    'zhu',
    'zhua',
    'zhuai',
    'zhuan',
    'zhuang',
    'zhui',
    'zhun',
    'zhuo',
    'zi',
    'zong',
    'zou',
    'zu',
    'zuan',
    'zui',
    'zun',
    'zuo'
);
/**
 * Tocken生成JS算法
 * view-source:https://wanwang.aliyun.com/domain/searchresult/?keyword=a&suffix=.com
 * function s32(){
    var data=["0","1","2","3","4","5","6","7","8","9"
      ,"a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];
    var result=[];
    var length = data.length;
    for(var i=0;i<32;i++){
      var r=Math.floor(Math.random()*length);
      result.push(data[r]);
    }
    return result.join('');
  }
 */
function requestWithCollisionDetection($curlUtil, $pinyin, $tocken, $count = 0) {
    if($count > 150) { // 判断尝试次数，可能是IP被封或Tocken失效
        $count = 0; // 清空计数
        $url = 'https://ynuf.alipay.com/service/clear.png?xt='.$tocken.'&xa=check-web-hichina-com';
        $response = $curlUtil->httpsGet($url); // 重新激活tocken
        $curlUtil->flushMessage('* '.$pinyin.' => tocken may be exhausted, reenable - '.$response, true, true);
    } else {
        $count += 30; // 单次请求计数器
    }
    $url = 'https://checkapi.aliyun.com/check/checkdomain?callback=jQuery&domain='.$pinyin.'&token='.$tocken;
    $response = $curlUtil->httpsGet($url);
    if($response) {
        $response = str_replace('jQuery', '', $response);
        $response = trim($response, '();');
    }
    $resule = json_decode($response, true);
    if(($resule && $resule['success'] == 'true')) { // 请求成功
        $curlUtil->flushMessage($pinyin.' => '.($resule['module'][0]['avail'] ? 'ok' : 'registed'), true, true);
    } else {
        $time = ceil($count * rand(1, 20) / 10);
        $curlUtil->flushMessage('* '.$pinyin.' => faild, sleep '.$time.'s, retry - '.$response, true, true);
        sleep($time);
        requestWithCollisionDetection($curlUtil, $pinyin, $tocken, $count);
    }
}

//$pinyins = array('bugcat');
$tocken = 'check-web-hichina-com%3Aeyxv9tyxuyyuc9vybg2w8usktdb755nm';
$curlUtil = (new CurlUtil());
$curlUtil->flushMessage('* start request', false, true);
/* 单拼
foreach ($pinyins as $pinyin) {
	$pinyin .= '.com';
	requestWithCollisionDetection($curlUtil, $pinyin, $tocken);
}*/
// 双拼
$total = 0; // 累计请求次数
foreach ($pinyins as $pinyin1) {
	foreach ($pinyins as $pinyin2) {
		$total++;
		$pinyin = $pinyin1.$pinyin2.'.com';
		if(0 == $total % 80) {
			$count = rand(0, 150);
			$curlUtil->flushMessage('* '.$pinyin.' => intermittent sleep '.$count.'s', true, true);
			sleep($count);
		} else {
			$count = 0;
		}
		requestWithCollisionDetection($curlUtil, $pinyin, $tocken, $count);
	}
}
$curlUtil->flushMessage('* request done!', true, true);
