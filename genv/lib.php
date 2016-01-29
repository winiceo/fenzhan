<?php
/**
 * Created by PhpStorm.
 * User: leven
 * Date: 16/1/13
 * Time: …œŒÁ9:17
 */

//require_once(dirname(__FILE__).'/../vendor/autoload.php');
require (QISHI_ROOT_PATH . 'data/config.php');
require  'idiorm.php';

ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES gbk'));

\ORM::configure(sprintf('mysql:host=%s;dbname=%s', $dbhost,$dbname));

\ORM::configure('username', $dbuser);
\ORM::configure('password', $dbpass);
\ORM::configure('return_result_sets', true);
\ORM::configure('logging', true);

\ORM::configure('logger', function ($log_string)  {
    $logpath="/data/logs/74cms/";
    error_log($log_string . "\n", 3, $logpath . '74cms.log');
});

class Ggven{
    public static function trim($str){
        $pattern = "/[,£¨]/i";
        $str= preg_replace($pattern, ',', trim($str));

        return trim($str);
    }
    public static function db(){
        global $db;
        return $db;
    }
    public static function log($log_string){
        $logpath="/data/logs/74cms/";
        error_log($log_string . "\n", 3, $logpath . '74cms.log');
    }
    public static function  birthdate($value){
        $value=str_replace("/","-",$value);
        $is_date=strtotime($value)?strtotime($value):false;
        if($is_date){
            return date("Y",$is_date);
        }
        return date("Y")-intval($value);
    }
    public static function utf8_gbk($value){
        return iconv("UTF-8", "GB2312//IGNORE",$value);

    }
}


function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {

            $output = print_r($var, true);
            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    } else {

        ob_start();
        var_dump($var);
        $output = ob_get_clean();


         if (!extension_loaded('xdebug')) {

            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);

            $output = '<pre>'
                . $label
                . htmlspecialchars($output,ENT_QUOTES,"GB2312")
                . '</pre>';
       }
    }
    if ($echo) {

        echo($output);
        return null;
    } else {
        return $output;
    }
}

function escape($str) {
    $sublen = strlen ( $str );
    $retrunString = "";
    for($i = 0; $i < $sublen; $i ++) {
        if (ord ( $str [$i] ) >= 127) {
            $tmpString = bin2hex ( iconv ( "gb2312", "ucs-2", substr ( $str, $i, 2 ) ) );
            $retrunString .= "%u" . $tmpString;
            $i ++;
        } else {
            $retrunString .= "%" . dechex ( ord ( $str [$i] ) );
        }
    }
    return $retrunString;
}
function unescape($str) {
    $str = rawurldecode ( $str );
    preg_match_all ( "/%u.{4}|&#x.{4};|&#\d+;|.+/U", $str, $r );
    $ar = $r [0];
    foreach ( $ar as $k => $v ) {
        if (substr ( $v, 0, 2 ) == "%u")
            $ar [$k] = iconv ( "UCS-2", "GBK", pack ( "H4", substr ( $v, - 4 ) ) );
        elseif (substr ( $v, 0, 3 ) == "&#x")
            $ar [$k] = iconv ( "UCS-2", "GBK", pack ( "H4", substr ( $v, 3, - 1 ) ) );
        elseif (substr ( $v, 0, 2 ) == "&#") {
            $ar [$k] = iconv ( "UCS-2", "GBK", pack ( "n", substr ( $v, 2, - 1 ) ) );
        }
    }
    return join ( "", $ar );
}

function isChineseName($str){
    if (preg_match("/^[\x7f-\xff]{4,20}$/", $str)) { //ºÊ»›gb2312,utf-8
        return true;
    } else {
        return false;
    }
}