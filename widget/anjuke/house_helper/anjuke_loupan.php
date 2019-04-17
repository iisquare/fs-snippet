<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
ini_set("max_execution_time", 0);
ini_set("memory_limit", "1024M");

class HouseUtil {

    /**
     * 读取页面内容
     */
	public static function loadPage($url) {
		$ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        //打印获得的数据
        return $output;
	}

	/**
	 * 读取列表
	 */
	public static function loadList($content) {
		$data = $matches = array();
		$patterns = array(
		    'id' => '/<div\s+class="item\-mod"\s+data\-link=".*?\/loupan\/(\d+)\.html"/',
		    'name' => '/<a\s+class="items\-name".*?>(.*?)<\/a>/',
		    'status' => '/<i\s+class="status-icon.*?">(.*?)<\/i>/',
		    'price' => '/<p\s+class="price(\-txt)?">(.*?)<\/p>/'
		);
		preg_match_all($patterns['id'], $content, $matches) && $data['id'] = $matches[1];
		preg_match_all($patterns['name'], $content, $matches) && $data['name'] = $matches[1];
		preg_match_all($patterns['status'], $content, $matches) && $data['status'] = $matches[1];
		preg_match_all($patterns['price'], $content, $matches) && $data['price'] = $matches[2];
		return $data;
	}

	public static function formatListData($listData) {
	    $data = array();
	    foreach ($listData['id'] as $key => $value) {
	        if('售罄' == $listData['status'][$key]) break;
	        $data[$key] = array(
	            'id' => $value,
	            'name' => $listData['name'][$key],
	            'status' => $listData['status'][$key],
	            'price' => $listData['price'][$key]
	        );
	    }
	    return $data;
	}
	
	public static function loadDetail($content) {
	    $data = $matches = array();
	    $patterns = array(
	        'openTime' => '/<label>最新开盘<\/label>\s*<span>(.*?)<\/span>/',
	        'giveTime' => '/<label>交房时间<\/label>\s*<span>(.*?)<\/span>/',
	        'status' => '/<i\s+class="lp-tag-status.*?">(.*?)<\/i>/',
	        'map' => '/Loupan\.Map\({panName:.*?lat:\s*(.*?),\s*lng:\s*(.*?)}\)/'
	    );
	    preg_match_all($patterns['openTime'], $content, $matches) && $data['openTime'] = reset($matches[1]);
	    preg_match_all($patterns['giveTime'], $content, $matches) && $data['giveTime'] = reset($matches[1]);
	    preg_match_all($patterns['status'], $content, $matches) && $data['status'] = reset($matches[1]);
	    if(preg_match_all($patterns['map'], $content, $matches)) {
	        $data['lat'] = reset($matches[1]);
	        $data['lng'] = reset($matches[2]);
	    }
	    return $data;
	}
	
	public static function loadCanshu($content) {
	    $data = $matches = array();
	    $patterns = array(
	        'developers' => '/<a\s*target="_blank"\s*soj="canshu_left_kfs".*?>(.*?)<\/a>/'
	    );
	    preg_match_all($patterns['developers'], $content, $matches) && $data['developers'] = reset($matches[1]);
	    return $data;
	}
}

class ApiUtil {

    public static function echoResult($code, $message = null, $data = null) {
        if(null === $message) {
            switch ($code) {
                case 0 :
                    $message = '操作成功';
                    break;
                case 404 :
                    $message = '信息不存在';
                    break;
                case 500 :
                    $message = '操作失败';
                    break;
            }
        }
        $json = @json_encode(array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        ));
        return self::echoCallback($json);
    }

    public static function echoCallback($json) {
        $callback = isset($_REQUEST['callback']) ? htmlspecialchars($_REQUEST['callback']) : null;
        if(!empty($callback)) $json = $callback.'('.$json.')';
        return $json;
    }

}

$urls = array(
    'list' => array(
        'lixia' => 'http://jn.fang.anjuke.com/loupan/lixia/?p16=1&p=',
        'gaoxin' => 'http://jn.fang.anjuke.com/loupan/gaoxin/?p16=1&p='
    ),
    'detail' => 'http://jn.fang.anjuke.com/loupan/{{%id%}}.html',
    'canshu' => 'http://jn.fang.anjuke.com/loupan/canshu-{{%id%}}.html'
);
$isDebug = false;
$resultData = array();
foreach ($urls['list'] as $districtName => $listUrl) {
    $resultData[$districtName] = array();
    $nextPage = 1;
    while ($nextPage > 0 && $nextPage < 5) {
        $content = HouseUtil::loadPage($listUrl.$nextPage++);
        $data = HouseUtil::loadList($content);
        $resultData[$districtName] = array_merge($resultData[$districtName], HouseUtil::formatListData($data));
        if($isDebug) break;
        if(in_array('售罄', $data['status'])) {
            $nextPage = 0;
            break;
        }
    }
    foreach ($resultData[$districtName] as $key => $value) {
        $content = HouseUtil::loadPage(str_replace('{{%id%}}', $value['id'], $urls['detail']));
        $data = HouseUtil::loadDetail($content);
        $value = array_merge($value, $data);
        $content = HouseUtil::loadPage(str_replace('{{%id%}}', $value['id'], $urls['canshu']));
        $data = HouseUtil::loadCanshu($content);
        $value = array_merge($value, $data);
        $resultData[$districtName][$key] = $value;
        if($isDebug) break;
    }
}
if($isDebug) {
    var_dump($resultData);
} else {
    echo ApiUtil::echoResult(0, null, $resultData);
}

