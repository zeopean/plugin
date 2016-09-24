<?php

require_once 'Arr.php';
require_once 'Str.php';

if( ! function_exists('is_card_id'))
{
    /**
     * @param $idcard
     * @return bool
     * 检查身份证是否正确
     */
    function is_card_id($idcard){

        // 只能是18位
        if(strlen($idcard)!=18){
            return false;
        }

        // 取出本体码
        $idcard_base = substr($idcard, 0, 17);

        // 取出校验码
        $verify_code = substr($idcard, 17, 1);

        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        // 根据前17位计算校验码
        $total = 0;
        for($i=0; $i<17; $i++){
            $total += substr($idcard_base, $i, 1)*$factor[$i];
        }

        // 取模
        $mod = $total % 11;

        // 比较校验码
        if($verify_code == $verify_code_list[$mod]){
            return true;
        }else{
            return false;
        }

    }
}

if ( ! function_exists('is_wechat'))
{
    /**
     * @return bool
     * 检查是否是微信端
     */
    function is_wechat(){
        if(empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
}


if( ! function_exists('is_phone_UA'))
{
    /**
     * 检查是否手机版
     * @return bool
     */
    function is_phone_UA()
    {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ?
            strtolower($_SERVER['HTTP_USER_AGENT']) :
            'unknown';
        $seuas = ['android', 'iphone', 'windows phone'];
        foreach ($seuas as $ua) {
            if(strpos($agent, $ua) !== false) {
                return true;
            }
        }
        return false;
    }
}


if ( ! function_exists('xml2array')){
    /**
     * 将xml转为array
     * @param string $xml
     */
    function xml2array($xml)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
}


if ( ! function_exists('array2xml')){
    /**
     * @param $data
     * @return string
     * 数组转换为xml
     */
    function array2xml($data) {
        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
}



if ( ! function_exists('is_spider'))
{
    /**
     * 检查是否爬虫
     * @return bool
     */
    function is_spider()
    {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ?
            strtolower($_SERVER['HTTP_USER_AGENT']) :
            'unknown';
        $seuas = ['spider', 'googlebot', 'slurp'];
        foreach ($seuas as $ua) {
            if(strpos($agent, $ua) !== false) {
                return true;
            }
        }
        return false;
    }
}

if( ! function_exists('format_date')){
	/**
	 * @param $date 格式为: 2016-9-21 23:00:00
	 * @return bool|string
	 * 格式化时间
	 */
	function format_date($date){
		$created = strtotime($date);
		$now = time();
		$judge = $now-$created;

		if($judge && $judge < 300){
			return '刚刚';
		}
		if($judge < 3600){
			return ceil($judge/60).'分钟前';
		}
		if($judge < 24*60*60 ){
			return ceil($judge/60/60).'小时前';
		}
		if($judge < 48*60*60){
			return '昨天';
		}
		if($judge < 365*24*60*60){
			return date('m-d',$created);
		}
		return date('Y-m-d',$created);
	}
}


if ( ! function_exists('append_config'))
{
	/**
	 * Assign high numeric IDs to a config item to force appending.
	 *
	 * @param  array  $array
	 * @return array
	 */
	function append_config(array $array)
	{
		$start = 9999;

		foreach ($array as $key => $value)
		{
			if (is_numeric($key))
			{
				$start++;

				$array[$start] = array_pull($array, $key);
			}
		}

		return $array;
	}
}

if ( ! function_exists('array_add'))
{
	/**
	 * Add an element to an array using "dot" notation if it doesn't exist.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	function array_add($array, $key, $value)
	{
		return Arr::add($array, $key, $value);
	}
}

if ( ! function_exists('array_build'))
{
	/**
	 * Build a new array using a callback.
	 *
	 * @param  array  $array
	 * @param  callable  $callback
	 * @return array
	 */
	function array_build($array, callable $callback)
	{
		return Arr::build($array, $callback);
	}
}

if ( ! function_exists('array_collapse'))
{
	/**
	 * Collapse an array of arrays into a single array.
	 *
	 * @param  array|\ArrayAccess  $array
	 * @return array
	 */
	function array_collapse($array)         //待改进
	{
        static $tmp = [] ;
        if(is_array($array)){
            foreach($array as $key => $val)
            {
                if(is_array($val))
                {
                    $arr = array_collapse($val);
                } else {
                    $tmp[]  = $val;
                }
            }

            if(!empty($arr) && !empty($tmp))
            {
                $result = array_merge($arr , $tmp);
            } else if(!empty($arr)) {
                $result = $arr;
            } else if(!empty($tmp)) {
                $result = $tmp;
            }
            return array_unique($result);
        }
	}
}


if ( ! function_exists('array_divide'))
{
	/**
	 * Divide an array into two arrays. One with keys and the other with values.
	 *
	 * @param  array  $array
	 * @return array
	 */
	function array_divide($array)
	{
		return Arr::divide($array);
	}
}

if ( ! function_exists('array_dot'))
{
	/**
	 * Flatten a multi-dimensional associative array with dots.
	 *
	 * @param  array   $array
	 * @param  string  $prepend
	 * @return array
	 */
	function array_dot($array, $prepend = '')
	{
		return Arr::dot($array, $prepend);
	}
}

if ( ! function_exists('array_except'))
{
	/**
	 * Get all of the given array except for a specified array of items.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	function array_except($array, $keys)
	{
		return Arr::except($array, $keys);
	}
}

if ( ! function_exists('array_fetch'))
{
	/**
	 * Fetch a flattened array of a nested array element.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @return array
	 */
	function array_fetch($array, $key)
	{
		return Arr::fetch($array, $key);
	}
}

if ( ! function_exists('array_first'))
{
	/**
	 * Return the first element in an array passing a given truth test.
	 *
	 * @param  array  $array
	 * @param  callable  $callback
	 * @param  mixed  $default
	 * @return mixed
	 */
	function array_first($array, callable $callback, $default = null)
	{
		return Arr::first($array, $callback, $default);
	}
}

if ( ! function_exists('array_last'))
{
	/**
	 * Return the last element in an array passing a given truth test.
	 *
	 * @param  array  $array
	 * @param  callable  $callback
	 * @param  mixed  $default
	 * @return mixed
	 */
	function array_last($array, $callback, $default = null)
	{
		return Arr::last($array, $callback, $default);
	}
}

if ( ! function_exists('array_flatten'))
{
	/**
	 * Flatten a multi-dimensional array into a single level.
	 *
	 * @param  array  $array
	 * @return array
	 */
	function array_flatten($array)
	{
		return Arr::flatten($array);
	}
}

if ( ! function_exists('array_forget'))
{
	/**
	 * Remove one or many array items from a given array using "dot" notation.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return void
	 */
	function array_forget(&$array, $keys)
	{
		return Arr::forget($array, $keys);
	}
}

if ( ! function_exists('array_get'))
{
	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	function array_get($array, $key, $default = null)
	{
		return Arr::get($array, $key, $default);
	}
}

if ( ! function_exists('array_has'))
{
	/**
	 * Check if an item exists in an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @return bool
	 */
	function array_has($array, $key)
	{
		return Arr::has($array, $key);
	}
}

if ( ! function_exists('array_only'))
{
	/**
	 * Get a subset of the items from the given array.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	function array_only($array, $keys)
	{
		return Arr::only($array, $keys);
	}
}

if ( ! function_exists('array_pull'))
{
	/**
	 * Get a value from the array, and remove it.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	function array_pull(&$array, $key, $default = null)
	{
		return Arr::pull($array, $key, $default);
	}
}

if ( ! function_exists('array_where'))
{
	/**
	 * Filter the array using the given callback.
	 *
	 * @param  array  $array
	 * @param  callable  $callback
	 * @return array
	 */
	function array_where($array, callable $callback)
	{
		return Arr::where($array, $callback);
	}
}

if ( ! function_exists('camel_case'))
{
	/**
	 * Convert a value to camel case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	function camel_case($value)
	{
		return Str::camel($value);
	}
}


if ( ! function_exists('e'))
{
	/**
	 * Escape HTML entities in a string.
	 *
	 * @param  string  $value
	 * @return string
	 */
	function e($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}
}

if ( ! function_exists('ends_with'))
{
	/**
	 * Determine if a given string ends with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	function ends_with($haystack, $needles)
	{
		return Str::endsWith($haystack, $needles);
	}
}

if ( ! function_exists('head'))
{
	/**
	 * Get the first element of an array. Useful for method chaining.
	 *
	 * @param  array  $array
	 * @return mixed
	 */
	function head($array)
	{
		return reset($array);
	}
}

if ( ! function_exists('last'))
{
	/**
	 * Get the last element from an array.
	 *
	 * @param  array  $array
	 * @return mixed
	 */
	function last($array)
	{
		return end($array);
	}
}


if ( ! function_exists('snake_case'))
{
	/**
	 * Convert a string to snake case.
	 *
	 * @param  string  $value
	 * @param  string  $delimiter
	 * @return string
	 */
	function snake_case($value, $delimiter = '_')
	{
		return Str::snake($value, $delimiter);
	}
}

if ( ! function_exists('starts_with'))
{
	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	function starts_with($haystack, $needles)
	{
		return Str::startsWith($haystack, $needles);
	}
}

if ( ! function_exists('str_contains'))
{
	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	function str_contains($haystack, $needles)
	{
		return Str::contains($haystack, $needles);
	}
}

if ( ! function_exists('str_finish'))
{
	/**
	 * Cap a string with a single instance of a given value.
	 *
	 * @param  string  $value
	 * @param  string  $cap
	 * @return string
	 */
	function str_finish($value, $cap)
	{
		return Str::finish($value, $cap);
	}
}

if ( ! function_exists('str_is'))
{
	/**
	 * Determine if a given string matches a given pattern.
	 *
	 * @param  string  $pattern
	 * @param  string  $value
	 * @return bool
	 */
	function str_is($pattern, $value)
	{
		return Str::is($pattern, $value);
	}
}

if ( ! function_exists('str_limit'))
{
	/**
	 * Limit the number of characters in a string.
	 *
	 * @param  string  $value
	 * @param  int     $limit
	 * @param  string  $end
	 * @return string
	 */
	function str_limit($value, $limit = 100, $end = '...')
	{
		return Str::limit($value, $limit, $end);
	}
}


if ( ! function_exists('str_random'))
{
	/**
	 * Generate a more truly "random" alpha-numeric string.
	 *
	 * @param  int  $length
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	function str_random($length = 16)
	{
		return Str::random($length);
	}
}

if ( ! function_exists('str_replace_array'))
{
	/**
	 * Replace a given value in the string sequentially with an array.
	 *
	 * @param  string  $search
	 * @param  array   $replace
	 * @param  string  $subject
	 * @return string
	 */
	function str_replace_array($search, array $replace, $subject)
	{
		foreach ($replace as $value)
		{
			$subject = preg_replace('/'.$search.'/', $value, $subject, 1);
		}

		return $subject;
	}
}


if ( ! function_exists('str_slug'))
{
	/**
	 * Generate a URL friendly "slug" from a given string.
	 *
	 * @param  string  $title
	 * @param  string  $separator
	 * @return string
	 */
	function str_slug($title, $separator = '-')
	{
		return Str::slug($title, $separator);
	}
}

if ( ! function_exists('studly_case'))
{
	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	function studly_case($value)
	{
		return Str::studly($value);
	}
}



