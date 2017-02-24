<?php

namespace app\library;

class Util {

    public static function get($array, $key, $default = null) {
        return ($array && isset($array[$key])) ? $array[$key] : $default;
    }

    //mehtod 0 get, 1 post , 2 put
    public static function curl($url, $method = 0, $data = '', $headers = [], $userAgent = '', $connectTimeout=3, $timeout=3, $is_throw_exception = false, & $options = null) {
        $ch = curl_init($url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt ($ch, CURLOPT_REFERER, $url);
        curl_setopt ($ch, CURLOPT_HTTPHEADER , $headers);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, true);
        //    	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        //    	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $httpProxy = Util::get($options, 'http_proxy');
        if($httpProxy) {
            curl_setopt($ch, CURLOPT_PROXY, $httpProxy);
        }

        if ( $method == 1 ) {
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data );
        } else if ( $method == 2 ) {
            //    		curl_setopt ($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $data );
        }

        $t = microtime(true);
        $ret = curl_exec($ch);
        $d = microtime(true) - $t;
//         $file = '/tmp/curl_request_performance.log';
//         if(file_exists($file) && filesize($file) > 10485760) { // 10M
//             unlink($file);
//         }
//         file_put_contents($file, date('Y-m-d H:i:s') . "\tcurl\t" . $url . "\t" . ($ret === false ? 'ERROR' : strlen($ret)) . "\t" . ($httpProxy ?: 'DIRECT') . "\t$d\n", FILE_APPEND);

        if ($ret === false) {
            $error = curl_error($ch);
            $info =  curl_getinfo($ch);
            if($options) {
                $options['error'] = [
                    'no' => curl_errno($ch),
                    'message' => $error,
                ];
            }
            $error_msg = 'Util curl error: ' . var_export($error, true) . ' , time spent is ' . $d . 's, info is ' . var_export($info, true) . ' ,param is :' . var_export(func_get_args(), true);
            if ($is_throw_exception) {
                throw new \Exception($error_msg);
            }
        }

        curl_close ($ch);
        return $ret;
    }

    //十六进制转字符串
    public static function hexToStr($hex){
        $string = '';
        for($i=0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i].  $hex[$i+1]));
        }
        return $string;
    }

    //判断当前是不是windows环境
    public static function isWindows() {
        return strtoupper(substr(PHP_OS,0,3)) === 'WIN';
    }
}