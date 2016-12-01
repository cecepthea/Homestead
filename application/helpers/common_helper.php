<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method'))
{
    function test_method()
    {
        echo 'hello world';
    }
}

if ( ! function_exists('curl_download_file'))
{
    /**
     * curl下载文件
     *
     * @param string $source 下载路径
     * @param string $dist 目的路径
     * @param object $curl curl实例
     * @param string $cookie_prefix cookie文件前缀
     * @param int $file_permission 文件权限
     * @param int $size_limit 最小大小
     * @param int $retry_times 失败重试次数
     * @return bool
     */
    function curl_download_file($source, $dist, $curl, $cookie_prefix = '', $file_permission = 0777, $size_limit = 150, $retry_times = 1)
    {
        clearstatcache();

        $options = array(
	        CURLOPT_URL     => $source,
	        // CURLOPT_FILE    => $dist,
	        CURLOPT_HEADER  => 0,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_BINARYTRANSFER => 1,
	        CURLOPT_COOKIEJAR => '/tmp/'.$cookie_prefix.'cookie.txt',
	        CURLOPT_FOLLOWLOCATION => TRUE,
	        CURLOPT_TIMEOUT => 30, // 30 seconds
	        // CURLOPT_VERBOSE true 启用时会汇报所有的信息，存放在STDERR或指定的CURLOPT_STDERR中。
	    );

        curl_setopt_array($curl, $options);
        $raw = curl_exec($curl);
        $file = fopen($dist, "w+");
        fwrite($file, $raw);
	    fclose($file);

	    // check file size
	    $fsize = filesize($dist);

	    if($fsize === false || $fsize < $size_limit){
	    	// 重下
	    	for($i = 0; $i < $retry_times; $i++){

	    		usleep(200000); // 每次延时0.2秒

	    		$raw = curl_exec($curl);

	    		$file = fopen($dist, "w+");
        		fwrite($file, $raw);
	    		fclose($file);

	    		clearstatcache();

	    		$fsize = filesize($dist);
	    		if($fsize !== false && $fsize > $size_limit){
	    			break;
	    		}
	    	}
	    }

	    if (is_resource($file)){ // php bug
	        fclose($file);
	    }

	    chmod($dist, $file_permission);

        return true;
    }
}

if ( ! function_exists('curl_get_url'))
{
    /**
     * curl get访问
     *
     * @param string $url 访问url
     * @param object $curl curl实例
     * @param bool $json 是否json格式返回
     * @param string $ca ca目的路径
     * @return mixed
     */
    function curl_get_url($url, $curl, $json = true, $ca = '/etc/pki/tls/certs/cacert.pem')
    {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CAINFO, $ca);
        $raw = curl_exec($curl);
        return $json ? json_decode($raw, true) : $raw;
    }
}

if ( ! function_exists('curl_post_url'))
{
    /**
     * curl post数据
     *
     * @param string $url 访问url
     * @param mixed $data 提交数据
     * @param object $curl curl实例
     * @param array $header http_header
     * @param bool $json 是否json格式返回
     * @param string $ca ca目的路径
     * @return mixed
     */
    function curl_post_url($url, $data, $curl, $header = ['Content-Type: application/json'], $json = true, $ca = '/etc/pki/tls/certs/cacert.pem')
    {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CAINFO, $ca);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $raw = curl_exec($curl);
        return $json ? json_decode($raw, true) : $raw;
    }
}

if ( ! function_exists('random_string'))
{
    /**
     * 产生随机字符串
     *
     * @param int $length 字符串长度
     * @param string $chars 字符库
     * @return string
     */
    function random_string( $length, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {

        /* initialize generator ... */
        for ( $max = strlen( $chars ) - 1, $ret = '',
            $i = 0; $i < $length; ++ $i ) $ret .=
                $chars[mt_rand( 0, $max )];

        return $ret;
    }
}

if ( ! function_exists('filter_emoji'))
{
    /**
     * 过滤emoji字符
     *
     * @param $text
     * @return mixed
     */
    function filter_emoji($text)
    {
        $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text); // Match Emoticons
        $text = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $text); // Match Miscellaneous Symbols and Pictographs
        $text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $text); // Match Transport And Map Symbols
        $text = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $text);   // Match Miscellaneous Symbols
        $text = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $text);   // Match Dingbats
        $text = preg_replace_callback(
                '/./u',
                function (array $match) {
                    return strlen($match[0]) >= 4 ? '' : $match[0];
                },
                $text);
        return $text;
    }
}

if ( ! function_exists('strpos_array'))
{
    /**
     * strpos 数组版
     *
     * @param $haystack 数组
     * @param $needle 需要匹配的字符
     * @return bool
     */
    function strpos_array($haystack, $needle)
    {
        foreach($needle as $query) {
            if(stripos($haystack, $query) !== false) return $query;
        }
        return false;
    }
}

if ( ! function_exists('check_image_valid'))
{
    /**
     * 检测图片是否能够生成PDF
     *
     * @param string $identify ImageMagick命令
     * @param string $target_name 目标文件
     * @param array $check_bytes 允许绕过的特殊头字节
     * @param array $check_string ImageMagick错误信息
     * @return bool
     */
    function check_image_valid($identify, $target_name, $check_bytes = [], $check_string = [])
    {
        $imageTypes = array(
            IMAGETYPE_JPEG,
            IMAGETYPE_PNG,
        );

        $default_check_bytes = array(
            '52494646',
            'ffd8ffdb',
            'ffd8ffee',
            'ffd8fffe',
            'ffd8ffe2',
            'ffd8ffe0',
            'ffd8ffe1',
            'ffd8ffe8',
            'ffd8ffe9',
            '424d3684',
            '89504e47',
            '424d3610'
        );
        if(!empty($check_bytes)){
            $default_check_bytes = array_merge($default_check_bytes, $check_bytes);
        }

        $default_check_string = array(
            'premature end of',
            'Bogus marker length',
            'Corrupt JPEG'
        );
        if(!empty($check_string)){
            $default_check_string = array_merge($default_check_string, $check_string);
        }

        $fsize = filesize($target_name);
	    if($fsize == 0){
	    	return false;  // 坏图
	    }

        try{
            $check_type = getimagesize($target_name);
        } catch (Exception $e) {
            $type = NULL;
        }
        if(empty($check_type)){
            $type = NULL;
        }
        if(is_null($type)){
            return false;
        }

        $type = $check_type[2];

        $f_handle = fopen($target_name, "r");
        $f_contents = fread($f_handle, 4);
        $f_contents = strtolower(bin2hex($f_contents));

        if( in_array($f_contents, $default_check_bytes) || in_array($type, $imageTypes) ){
            $check_cmd = $identify . $target_name . ' 2>&1';
            $check_res = array();
            exec($check_cmd, $check_res);
            $check_res = implode($check_res, ' ');
            if (strposa($check_res, $default_check_string) !== false) {
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}

if ( ! function_exists('delete_directory'))
{
    /**
     * 递归删除目录
     *
     * @param string $dir
     * @return bool
     */
    function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}

if ( ! function_exists('is_weixin'))
{
    /**
     * 判断是否微信环境
     *
     * @return bool
     */
    function is_weixin()
    {
	    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
	        return true;
	    }
	    return false;
    }
}

if ( ! function_exists('is_phone'))
{
    /**
     * 判断是否合法电话号码
     *
     * @param string $phone
     * @return bool
     */
    function is_phone($phone)
    {
        return preg_match( '/^1[34578]{1}\d{9}$/', $phone ) ? true : false;
    }
}

if ( ! function_exists('auth_code'))
{
    /**
     * Discuz 加解密函数
     *
     * @param $string
     * @param string $operation 'ENCODE'或'DECODE'
     * @param string $key key_name
     * @param int $expiry 过期时间戳
     * @return string
     */
    function auth_code($string, $operation = 'DECODE', $key = 'key_name', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
}