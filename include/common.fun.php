<?php
 /*
 * 74cms 共用函数
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_QISHI')) die('Access Denied!');
function table($table)
{
 	global $pre;
    return $pre .$table ;
}
function showmsg($msg_detail, $msg_type = 0, $links = array(), $auto_redirect = true,$seconds=3)
{
	global $smarty;
    if (count($links) == 0)
    {
        $links[0]['text'] = '返回上一页';
        $links[0]['href'] = 'javascript:history.go(-1)';
    }
   $smarty->assign('ur_here',     '系统提示');
   $smarty->assign('msg_detail',  $msg_detail);
   $smarty->assign('msg_type',    $msg_type);
   $smarty->assign('links',       $links);
   $smarty->assign('default_url', $links[0]['href']);           //意思是在规定的时间内如果不进行操作，默认跳转的地方
   $smarty->assign('auto_redirect', $auto_redirect);
   $smarty->assign('seconds', $seconds);
   $smarty->display('showmsg.htm');
exit;
}
function get_smarty_request($str)
{
$str=rawurldecode($str);
$strtrim=rtrim($str,']');
	if (substr($strtrim,0,4)=='GET[')
	{
	$getkey=substr($strtrim,4);
	return $_GET[$getkey];
	}
	elseif (substr($strtrim,0,5)=='POST[')
	{
	$getkey=substr($strtrim,5);
	return $_POST[$getkey];
	}
	else
	{
	return $str;
	}
}
function get_cache($cachename)
{
	$cache_file_path =QISHI_ROOT_PATH. "data/cache_".$cachename.".php";
	@include($cache_file_path);
	return $data;
}
function exectime(){ 
	$time = explode(" ", microtime());
	$usec = (double)$time[0]; 
	$sec = (double)$time[1]; 
	return $sec + $usec; 
}
function check_word($noword,$content)
{
	$word=explode('|',$noword);
	if (!empty($word) && !empty($content))
	{
		foreach($word as $str)
		{
			if(!empty($str) && strstr($content,$str))
			{
			return true;
			}

		}
	}
	return false;
}
function getip()
{
	if (getenv('HTTP_CLIENT_IP') and strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {
		$onlineip=getenv('HTTP_CLIENT_IP');
	}elseif (getenv('HTTP_X_FORWARDED_FOR') and strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {
		$onlineip=getenv('HTTP_X_FORWARDED_FOR');
	}elseif (getenv('REMOTE_ADDR') and strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {
		$onlineip=getenv('REMOTE_ADDR');
	}elseif (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] and strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
		$onlineip=$_SERVER['REMOTE_ADDR'];
	}
	preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/",$onlineip,$match);
	return $onlineip = $match[0] ? $match[0] : 'unknown';
}
function convertip($ip) {
	if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
		$iparray = explode('.', $ip);
		if($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
			$return = '- LAN';
		} elseif($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
			$return = '- Invalid IP Address';
		} else {
			$tinyipfile = QISHI_ROOT_PATH.'data/tinyipdata.dat';
			if(@file_exists($tinyipfile)) {
				$return = convertip_tiny($ip, $tinyipfile);
			} 
		}
	}
	return $return;
}
function convertip_tiny($ip, $ipdatafile) {
	static $fp = NULL, $offset = array(), $index = NULL;
	$ipdot = explode('.', $ip);
	$ip    = pack('N', ip2long($ip));
	$ipdot[0] = (int)$ipdot[0];
	$ipdot[1] = (int)$ipdot[1];
	if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
		$offset = @unpack('Nlen', @fread($fp, 4));
		$index  = @fread($fp, $offset['len'] - 4);
	} elseif($fp == FALSE) {
		return  '- Invalid IP data file';
	}
	$length = $offset['len'] - 1028;
	$start  = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

	for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

		if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
			$index_offset = @unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
			$index_length = @unpack('Clen', $index{$start + 7});
			break;
		}
	}
	@fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
	if($index_length['len']) {
		return '- '.@fread($fp, $index_length['len']);
	} else {
		return '- Unknown';
	}
}
function wheresql($wherearr='')
{
	$wheresql="";
	if (is_array($wherearr))
		{
		$where_set=' WHERE ';
			foreach ($wherearr as $key => $value)
			{
			$wheresql .=$where_set. $comma.$key."='".$value."'";
			$comma = ' AND ';
			$where_set=' ';
			}
		}
	return $wheresql;
}
function convert_datefm ($date,$format,$separator="-")
{
	 if ($format=="1")
	 {
	 return date("Y-m-d", $date);
	 }
	 else
	 {
		if (!preg_match("/^[0-9]{4}(\\".$separator.")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$date))  return false;
		$date=explode($separator,$date);
		return mktime(0,0,0,$date[1],$date[2],$date[0]);
	 }
}
function sub_day($endday,$staday,$range='')
{
	$value = $endday - $staday;
	if($value < 0)
	{
		return '';
	}
	elseif($value >= 0 && $value < 59)
	{
		return ($value+1)."秒";
	}
	elseif($value >= 60 && $value < 3600)
	{
		$min = intval($value / 60);
		return $min."分钟";
	}
	elseif($value >=3600 && $value < 86400)
	{
		$h = intval($value / 3600);
		return $h."小时";
	}
	elseif($value >= 86400 && $value < 86400*30)
	{
		$d = intval($value / 86400);
		return intval($d)."天";
	}
	elseif($value >= 86400*30 && $value < 86400*30*12)
	{
		$mon  = intval($value / (86400*30));
		return $mon."月";
	}
	else{	
		$y = intval($value / (86400*30*12));
		return $y."年";
	}
}
function daterange($endday,$staday,$format='Y-m-d',$color='',$range=3)
{
	$value = $endday - $staday;
	if($value < 0)
	{
		return '';
	}
	elseif($value >= 0 && $value < 59)
	{
		$return=($value+1)."秒前";
	}
	elseif($value >= 60 && $value < 3600)
	{
		$min = intval($value / 60);
		$return=$min."分钟前";
	}
	elseif($value >=3600 && $value < 86400)
	{
		$h = intval($value / 3600);
		$return=$h."小时前";
	}
	elseif($value >= 86400)
	{
		$d = intval($value / 86400);
		if ($d>$range)
		{
		return date($format,$staday);
		}
		else
		{
		$return=$d."天前";
		}
	}
	if ($color)
	{
	$return="<span id=\"r_time\" style=\"color:{$color}\">".$return."</span>";
	}
	return $return;	 
}
function distancerange($distance)
{
	$distance  = intval($distance);
	if($distance >= 0 && $distance < 1000)
	{
		$return=$distance."m";
	}
	elseif($distance >= 1000)
	{
		$return = round($distance/1000,2)."km";
	}
	return $return;	 
}
function cut_str($string, $length, $start=0,$dot='') 
{
		$length=$length*2;
		if(strlen($string) <= $length) {
			return $string;
		}
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
		$strcut = '';	 
			for($i = 0; $i < $length; $i++) {
				$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			}
		$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
		return $strcut.$dot;
}
function smtp_mail($sendto_email,$subject,$body,$From='',$FromName='')
{	
	global $_CFG;
	require_once(QISHI_ROOT_PATH.'phpmailer/class.phpmailer.php');
	$mail = new PHPMailer();
	$mailconfig=get_cache('mailconfig');
	$mailconfig['smtpservers']=explode('|-_-|',$mailconfig['smtpservers']);
	$mailconfig['smtpusername']=explode('|-_-|',$mailconfig['smtpusername']);
	$mailconfig['smtppassword']=explode('|-_-|',$mailconfig['smtppassword']);
	$mailconfig['smtpfrom']=explode('|-_-|',$mailconfig['smtpfrom']);
	$mailconfig['smtpport']=explode('|-_-|',$mailconfig['smtpport']);
	for ($i=0; $i<count($mailconfig['smtpservers']); $i++)
	{
	$mailconfigarray[]=array('smtpservers'=>$mailconfig['smtpservers'][$i],'smtpusername'=>$mailconfig['smtpusername'][$i],'smtppassword'=>$mailconfig['smtppassword'][$i],'smtpfrom'=>$mailconfig['smtpfrom'][$i],'smtpport'=>$mailconfig['smtpport'][$i]);
	}
	$mc=array_rand($mailconfigarray,1);
	$mc=$mailconfigarray[$mc];
	$mailconfig['smtpservers']=$mc['smtpservers'];
	$mailconfig['smtpusername']=$mc['smtpusername'];
	$mailconfig['smtppassword']=$mc['smtppassword'];
	$mailconfig['smtpfrom']=$mc['smtpfrom'];
	$mailconfig['smtpport']=$mc['smtpport'];
	$From=$From?$From:$mailconfig['smtpfrom'];
	$FromName=$FromName?$FromName:$_CFG['site_name'];
	if ($mailconfig['method']=="1")
	{
		if (empty($mailconfig['smtpservers']) || empty($mailconfig['smtpusername']) || empty($mailconfig['smtppassword']) || empty($mailconfig['smtpfrom']))
		{
		write_syslog(2,'MAIL',"邮件配置信息不完整");
		return false;
		}
	$mail->IsSMTP();
	$mail->Host = $mailconfig['smtpservers'];
	$mail->SMTPDebug= 0; 
	$mail->SMTPAuth = true;
	$mail->Username = $mailconfig['smtpusername']; 
	$mail->Password = $mailconfig['smtppassword']; 
	$mail->Port =$mailconfig['smtpport'];
	$mail->From =$mailconfig['smtpfrom']; 
	$mail->FromName =$FromName;
	}
	elseif($mailconfig['method']=="2")
	{
	$mail->IsSendmail();
	}
	elseif($mailconfig['method']=="3")
	{
	$mail->IsMail();
	}
	$mail->CharSet = QISHI_CHARSET;
	$mail->Encoding = "base64";
	$mail->AddReplyTo($From,$FromName);
	$mail->AddAddress($sendto_email,"");
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body =$body;
	$mail->AltBody ="text/html";
	if($mail->Send())
	{
	write_sys_email_log($mailconfig['smtpusername'],$sendto_email,$subject,$body,"1");
	return true;
	}
	else
	{
	write_syslog(2,'MAIL',$mail->ErrorInfo);
	write_sys_email_log($mailconfig['smtpusername'],$sendto_email,$subject,$body,"2");
	return false;
	}
}
function write_sys_email_log($send_from,$send_to,$subject,$body,$state)
{
	global $db;
	$setarr['send_from']=$send_from;
	$setarr['send_to']=$send_to;
	$setarr['subject']=$subject;
	$setarr['body']=addslashes($body);
	$setarr['state']=intval($state);
	$setarr['sendtime']=time();
	$db->inserttable(table('sys_email_log'),$setarr);
}
function dfopen($url,$limit = 0, $post = '', $cookie = '', $bysocket = FALSE	, $ip = '', $timeout = 15, $block = TRUE, $encodetype  = 'URLENCOD')
{
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;

		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$boundary = $encodetype == 'URLENCODE' ? '' : ';'.substr($post, 0, trim(strpos($post, "\n")));
			$out .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data$boundary\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}

		if(function_exists('fsockopen')) {
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} elseif (function_exists('pfsockopen')) {
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} else {
			$fp = false;
		}
		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}

				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
}
//通知类短信接口
function send_sms($mobile,$content)
{
	global $db;
	$sms=get_cache('sms_config');
	if ($sms['open']!="1" || empty($sms['notice_sms_name']) || empty($sms['notice_sms_key']) || empty($mobile) || empty($content))
	{
	return false;
	}
	else
	{
		return https_request("http://www.74cms.com/SMSsend.php?sms_name={$sms['notice_sms_name']}&sms_key={$sms['notice_sms_key']}&mobile={$mobile}&content={$content}");
	
	}	
}
//验证码类短信接口
function captcha_send_sms($mobile,$content)
{
	global $db;
	$sms=get_cache('sms_config');
	if ($sms['open']!="1" || empty($sms['captcha_sms_name']) || empty($sms['captcha_sms_key']) || empty($mobile) || empty($content))
	{
	return false;
	}
	else
	{
		return https_request("http://www.74cms.com/SMSsend.php?sms_name={$sms['captcha_sms_name']}&sms_key={$sms['captcha_sms_key']}&mobile={$mobile}&content={$content}");
	}	
}
//其他类短信接口
function free_send_sms($mobile,$content)
{
	global $db;
	$sms=get_cache('sms_config');
	if ($sms['open']!="1" || empty($sms['free_sms_name']) || empty($sms['free_sms_key']) || empty($mobile) || empty($content))
	{
	return false;
	}
	else
	{
	return https_request("http://www.74cms.com/SMSsend.php?sms_name={$sms['free_sms_name']}&sms_key={$sms['free_sms_key']}&mobile={$mobile}&content={$content}");
	}	
}
function execution_crons()
{
	global $db;
	$crons=$db->getone("select * from ".table('crons')." WHERE (nextrun<".time()." OR nextrun=0) AND available=1 LIMIT 1  ");
	if (!empty($crons))
	{
		require_once(QISHI_ROOT_PATH."include/crons/".$crons['filename']);
	}
}
function get_tpl($type,$id)
{
	global $db,$_CFG,$smarty;
	$id=intval($id);
	$tarr=array("jobs","company_profile","resume","companycommentshow","companynewsshow",'course','train_profile','shop');
	if (!in_array($type,$tarr)) exit();
	if ($type=='companynewsshow')
	{
	$utpl=$db->getone("SELECT p.tpl FROM ".table('company_news')."  AS c LEFT JOIN ".table('company_profile')."  AS p ON c.company_id=p.id WHERE c.id='{$id}' limit 1");
	}
	elseif($type=='shop')
	{
		$utpl="";
	}
	elseif($type=='jobs')
	{
		$utpl=$db->getone("SELECT tpl FROM ".table($type)." WHERE id='{$id}' limit 1");
		if(empty($utpl))
		{
			$utpl=$db->getone("SELECT tpl FROM ".table('jobs_tmp')." WHERE id='{$id}' limit 1");
		}
	}
	else
	{
	$utpl=$db->getone("SELECT tpl FROM ".table($type)." WHERE id='{$id}' limit 1");
	}
	$thistpl=$utpl['tpl'];
	if (!empty($_GET['style']))
	{
	$thistpl=$_GET['style'];
	}
 	if (empty($thistpl))
	{
		if ($type=='resume')
		{
		$thistpl="../tpl_resume/{$_CFG['tpl_personal']}/";
		$smarty->assign('user_tpl',$_CFG['site_dir']."templates/tpl_resume/{$_CFG['tpl_personal']}/");
		return $thistpl;
		}
		elseif ($type=='course' || $type=='train_profile' || $type=='trainnewsshow')
		{
		$thistpl="../tpl_train/{$_CFG['tpl_train']}/";
		$smarty->assign('user_tpl',$_CFG['site_dir']."templates/tpl_train/{$_CFG['tpl_train']}/");
		return $thistpl;
		}
		elseif($type=='shop')
		{
			$thistpl="../tpl_shop/default/";
			$smarty->assign('user_tpl',$_CFG['site_dir']."templates/tpl_shop/default/");
			return $thistpl;
		}
		else
		{
		$thistpl="../tpl_company/{$_CFG['tpl_company']}/";
		$smarty->assign('user_tpl',$_CFG['site_dir']."templates/tpl_company/{$_CFG['tpl_company']}/");
		return $thistpl;
		}
	}
	else
	{
		if ($type=='resume')
		{
		$smarty->assign('user_tpl',$_CFG['site_dir']."templates/tpl_resume/{$thistpl}/");
		return "../tpl_resume/{$thistpl}/";
		}
		else
		{
		$smarty->assign('user_tpl',$_CFG['site_dir']."templates/tpl_company/{$thistpl}/");
		return "../tpl_company/{$thistpl}/";
		}		
	}	
}
function url_rewrite($alias=NULL,$get=NULL,$rewrite=true,$subsite_id=0)
{
	global $_CFG,$_PAGE;
	$url ='';
	if ($_PAGE[$alias]['url']=='0' || $rewrite==false)//原始链接
	{
			if (!empty($get))
			{
				foreach($get as $k=>$v)
				{
				$url .="{$k}={$v}&";
				}
			}
			$url=!empty($url)?"?".rtrim($url,'&'):'';
			$url = $_CFG['site_domain'].$_CFG['site_dir'].$_PAGE[$alias]['file'].$url;
	}
	else 
	{
			$url =$_CFG['site_domain'].$_CFG['site_dir'].$_PAGE[$alias]['rewrite'];
			if ($_PAGE[$alias]['pagetpye']=='2' && empty($get['page']))
			{
			$get['page']=1;
			}
			foreach($get as $k=>$v)
			{
			$url=str_replace('($'.$k.')',$v,$url);
			}
			
			$url=preg_replace('/\(\$(.+?)\)/','',$url);
			if(substr($url,-5)=='?key=')
			{
			$url=rtrim($url,'?key=');
			}
	}
	$url = replace_special_url($url,$alias,$subsite_id);
	return $url;
}
function get_member_url($type,$dirname=false)
{
	global $_CFG;
	$type=intval($type);
	if ($type===0) 
	{
	return "";
	}
	elseif ($type===1)
	{
	$return=$_CFG['site_dir']."user/company/company_index.php";
	}
	elseif ($type===2) 
	{
	$return=$_CFG['site_dir']."user/personal/personal_index.php";
	}
	elseif ($type===3) 
	{
	$return=$_CFG['site_dir']."user/hunter/hunter_index.php";
	}
	elseif ($type===4) 
	{
	$return=$_CFG['site_dir']."user/train/train_index.php";
	}
	if ($dirname)
	{
	return dirname($return).'/';
	}
	else
	{
	return $return;
	}
}
function fulltextpad($str)
{
	if (empty($str))
	{
	return '';
	}
	$leng=strlen($str);
	if ($leng>=8)
		{
		return $str;
	}
	else
	{
		$l=4-($leng/2);
		return str_pad($str,$leng+$l,'0');
	}
}
function asyn_userkey($uid)
{
	global $db;
	$sql = "select * from ".table('members')." where uid = '".intval($uid)."' LIMIT 1";
	$user=$db->getone($sql);
	return md5($user['username'].$user['pwd_hash'].$user['password']);
}

function write_syslog($type,$type_name,$str)
{
 	global $db,$online_ip,$ip_address;
	$setarr["l_type"]=$type;
	$setarr["l_type_name"]=$type_name;
	$setarr["l_time"]=time();
	$setarr["l_ip"]=$online_ip;
	$setarr["l_address"]=$ip_address;
	$setarr["l_page"]=request_url();
	$setarr["l_str"]=$str;
	return $db->inserttable(table("syslog"),$setarr);
}
function write_memberslog($uid,$utype,$type,$username,$str,$mode,$op_type,$op_type_cn,$op_used,$op_leave)
{
 	global $db,$online_ip,$ip_address;
 	$setarr["log_uid"]=$uid;
 	$setarr["log_username"]=$username;
 	$setarr["log_utype"]=$utype;
 	$setarr["log_type"]=$type;
 	$setarr["log_addtime"]=time();
 	$setarr["log_ip"]=$online_ip;
 	$setarr["log_address"]=$ip_address;
 	$setarr["log_value"]=$str;
 	$setarr["log_mode"]=$mode;
 	$setarr["log_op_type"]=$op_type;
 	$setarr["log_op_type_cn"]=$op_type_cn;
 	$setarr["log_op_used"]=$op_used;
 	$setarr["log_op_leave"]=$op_leave;
 	return $db->inserttable(table("members_log"),$setarr);
}
function write_setmeallog($uid,$username,$value,$type,$amount='0.00',$is_money='1',$log_mode='1',$log_utype='1')
{
 	global $db;
 	$setarr["log_uid"]=$uid;
 	$setarr["log_username"]=$username;
 	$setarr["log_type"]=$type;
 	$setarr["log_addtime"]=time();
 	$setarr["log_value"]=$value;
 	$setarr["log_amount"]=$amount;
 	$setarr["log_ismoney"]=$is_money;
 	$setarr["log_mode"]=$log_mode;
 	$setarr["log_utype"]=$log_utype;
 	return $db->inserttable(table("members_charge_log"),$setarr);
}

function request_url()
{     
  	if (isset($_SERVER['REQUEST_URI']))     
    {        
   	 $url = $_SERVER['REQUEST_URI'];    
    }
	else
	{    
		  if (isset($_SERVER['argv']))        
			{           
			$url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];      
			}         
		  else        
			{          
			$url = $_SERVER['PHP_SELF'] .'?'.$_SERVER['QUERY_STRING'];
			}  
    }    
    return urlencode($url); 
}
function label_replace($templates)
{
	global $_CFG;
	$templates=str_replace('{sitename}',$_CFG['site_name'],$templates);
	$templates=str_replace('{sitedomain}',rtrim($_CFG['site_domain'].$_CFG['site_dir'],'/'),$templates);
	$templates=str_replace('{username}',$_GET['sendusername'],$templates);
	$templates=str_replace('{password}',$_GET['sendpassword'],$templates);
	$templates=str_replace('{utype}',$_GET['utype'],$templates);
	$templates=str_replace('{newpassword}',$_GET['newpassword'],$templates);
	$templates=str_replace('{personalfullname}',$_GET['personal_fullname'],$templates);
	$templates=str_replace('{jobsname}',$_GET['jobs_name'],$templates);
	$templates=str_replace('{companyname}',$_GET['companyname'],$templates);
	$templates=str_replace('{paymenttpye}',$_GET['paymenttpye'],$templates);
	$templates=str_replace('{amount}',$_GET['amount'],$templates);
	$templates=str_replace('{oid}',$_GET['oid'],$templates);
	$templates=str_replace('{trainname}',$_GET['trainname'],$templates);
	$templates=str_replace('{coursename}',$_GET['coursename'],$templates);
	$templates=str_replace('{teachername}',$_GET['teachername'],$templates);
	$templates=str_replace('{huntername}',$_GET['huntername'],$templates);
	return $templates;
}
function make_dir($path)
{ 
	if(!file_exists($path))
	{
	make_dir(dirname($path));
	@mkdir($path,0777);
	@chmod($path,0777);
	}
}

function write_pmsnotice($touid,$toname,$message){
	global $db;
	$setsqlarr['message']=trim($message);
	$setsqlarr['msgtype']=1;
	$setsqlarr['msgtouid']=intval($touid);
	$setsqlarr['msgtoname']=trim($toname);
	$setsqlarr['dateline']=time();
	$setsqlarr['replytime']=time();
	$setsqlarr['new']=1;
	$db->inserttable(table('pms'),$setsqlarr);
}
//查看会员的日志
function get_last_refresh_date($uid,$type,$mode=0)
{
	global $db;
	$sql = "select max(addtime) from ".table('refresh_log')." where uid=".intval($uid).' and ' . "`type`='".$type."' and mode = ".$mode;
	return $db->getone($sql);
}
//统计今天刷新次数
function get_today_refresh_times($uid,$type,$mode=0)
{
	global $db;
	$today = strtotime(date('Y-m-d'));
	$tomorrow = $today+3600*24;
	$sql = "select count(*) from ".table('refresh_log')." where uid=".intval($uid).' and ' . "`type`='".$type."' and addtime>".$today." and addtime<".$tomorrow." and mode = ".$mode;
	return $db->getone($sql);
}
function write_refresh_log($uid,$type,$mode=0)
{
	global $db;
	$setsqlarr['uid'] = $uid;
	$setsqlarr['mode'] = $mode;
	$setsqlarr['type'] = $type;
	$setsqlarr['addtime'] = time();
	$db->inserttable(table('refresh_log'),$setsqlarr);
}
/**
 * utf8转gbk
 * @param $utfstr
 */
function utf8_to_gbk($utfstr) {
	if(is_numeric($utfstr)){
		return $utfstr;
	}
	global $UC2GBTABLE;
	$okstr = '';
	if(empty($UC2GBTABLE)) {
		define('CODETABLEDIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'encoding'.DIRECTORY_SEPARATOR);
		$filename = CODETABLEDIR.'gb-unicode.table';
		$fp = fopen($filename, 'rb');
		while($l = fgets($fp,15)) {        
			$UC2GBTABLE[hexdec(substr($l, 7, 6))] = hexdec(substr($l, 0, 6));
		}
		fclose($fp);
	}
	$okstr = '';
	$ulen = strlen($utfstr);
	for($i=0; $i<$ulen; $i++) {
		$c = $utfstr[$i];
		$cb = decbin(ord($utfstr[$i]));
		if(strlen($cb)==8) { 
			$csize = strpos(decbin(ord($cb)),'0');
			for($j = 0; $j < $csize; $j++) {
				$i++; 
				$c .= $utfstr[$i];
			}
			$c = utf8_to_unicode($c);
			if(isset($UC2GBTABLE[$c])) {
				$c = dechex($UC2GBTABLE[$c]+0x8080);
				$okstr .= chr(hexdec($c[0].$c[1])).chr(hexdec($c[2].$c[3]));
			} else {
				$okstr .= '&#'.$c.';';
			}
		} else {
			$okstr .= $c;
		}
	}
	$okstr = trim($okstr);
	return $okstr;
}
/**
 * gbk转utf8
 * @param $gbstr
 */
function gbk_to_utf8($gbstr) {
	if(is_numeric($gbstr)){
		return $gbstr;
	}
	global $CODETABLE;
	if(empty($CODETABLE)) {
		define('CODETABLEDIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'encoding'.DIRECTORY_SEPARATOR);
		$filename = CODETABLEDIR.'gb-unicode.table';
		$fp = fopen($filename, 'rb');
		while($l = fgets($fp,15)) { 
			$CODETABLE[hexdec(substr($l, 0, 6))] = substr($l, 7, 6); 
		}
		fclose($fp);
	}
	$ret = '';
	$utf8 = '';
	while($gbstr) {
		if(ord(substr($gbstr, 0, 1)) > 0x80) {
			$thisW = substr($gbstr, 0, 2);
			$gbstr = substr($gbstr, 2, strlen($gbstr));
			$utf8 = '';
			@$utf8 = unicode_to_utf8(hexdec($CODETABLE[hexdec(bin2hex($thisW)) - 0x8080]));
			if($utf8 != '') {
				for($i = 0; $i < strlen($utf8); $i += 3) $ret .= chr(substr($utf8, $i, 3));
			}
		} else {
			$ret .= substr($gbstr, 0, 1);
			$gbstr = substr($gbstr, 1, strlen($gbstr));
		}
	}
	return $ret;
}
/**
 * utf8转unicode
 * @param  $c
 */
function utf8_to_unicode($c) {
	switch(strlen($c)) {
		case 1:
		  return ord($c);
		case 2:
		  $n = (ord($c[0]) & 0x3f) << 6;
		  $n += ord($c[1]) & 0x3f;
		  return $n;
		case 3:
		  $n = (ord($c[0]) & 0x1f) << 12;
		  $n += (ord($c[1]) & 0x3f) << 6;
		  $n += ord($c[2]) & 0x3f;
		  return $n;
		case 4:
		  $n = (ord($c[0]) & 0x0f) << 18;
		  $n += (ord($c[1]) & 0x3f) << 12;
		  $n += (ord($c[2]) & 0x3f) << 6;
		  $n += ord($c[3]) & 0x3f;
		  return $n;
	}
}
/**
 * unicode转utf8
 * @param  $c
 */
function unicode_to_utf8($c) {
	$str = '';
	if($c < 0x80) {
		$str .= $c;
	} elseif($c < 0x800) {
		$str .= (0xC0 | $c >> 6);
		$str .= (0x80 | $c & 0x3F);
	} elseif($c < 0x10000) {
		$str .= (0xE0 | $c >> 12);
		$str .= (0x80 | $c >> 6 & 0x3F);
		$str .= (0x80 | $c & 0x3F);
	} elseif($c < 0x200000) {
		$str .= (0xF0 | $c >> 18);
		$str .= (0x80 | $c >> 12 & 0x3F);
		$str .= (0x80 | $c >> 6 & 0x3F);
		$str .= (0x80 | $c & 0x3F);
	}
	return $str;
}
function browser()
{
	switch(TRUE)
	{
	// Apple/iPhone browser renders as mobile
	case (preg_match('/(apple|iphone|ipod)/i', $_SERVER['HTTP_USER_AGENT']) && preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT'])):
	$browser = "mobile";
	break; 
	// Other mobile browsers render as mobile
	case (preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|
	treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])):
	$browser = "mobile";
	break; 
	// Wap browser
	case (((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'text/vnd.wap.wml') > 0) || (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0)) || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])))):
	$browser = "mobile";
	break; 
	// Shortend user agents
	case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,3)),array('lg '=>'lg ','lg-'=>'lg-','lg_'=>'lg_','lge'=>'lge'))); 
	$browser = "mobile";
	break; 
	// More shortend user agents
	case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)),array('acs-'=>'acs-','amoi'=>'amoi','doco'=>'doco','eric'=>'eric','huaw'=>'huaw','lct_'=>'lct_','leno'=>'leno','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','nec-'=>'nec-','phil'=>'phil','sams'=>'sams','sch-'=>'sch-','shar'=>'shar','sie-'=>'sie-','wap_'=>'wap_','zte-'=>'zte-')));
	$browser = "mobile";
	break; 
	// Render mobile site for mobile search engines
	case (preg_match('/Googlebot-Mobile/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/YahooSeeker\/M1A1-R2D2/i', $_SERVER['HTTP_USER_AGENT'])):
	$browser = "mobile";
	break;
	}
	return $browser;
}
function https_request($url,$data = null){
	if(function_exists('curl_init')){
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}else{
		return false;
	}
}
function get_access_token($refresh=false){
	global $_CFG,$db;
	//判断之前的token是否在生命周期范围内
	if(!empty($_CFG['access_token']) && !empty($_CFG['expires_addtime']) && (time()-$_CFG['expires_addtime'] <= 7200) && $refresh===false)
	{
		return $_CFG['access_token'];
	}
	else
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$_CFG['weixin_appid']."&secret=".$_CFG['weixin_appsecret'];
		$result = https_request($url);
		$jsoninfo = json_decode($result, true);
		$access_token = $jsoninfo["access_token"];
		//更新数据库
		$db->query("UPDATE ".table('config')." SET value='".$access_token."' WHERE name='access_token'");
		$db->query("UPDATE ".table('config')." SET value='".time()."' WHERE name='expires_addtime'");
		//更新缓存
		$config_arr = array();
		$cache_file_path =QISHI_ROOT_PATH. "data/cache_config.php";
		$sql = "SELECT * FROM ".table('config');
		$arr = $db->getall($sql);
		foreach($arr as $key=> $val)
		{
		$config_arr[$val['name']] = $val['value'];
		}
		$content = "<?php\r\n";
		$content .= "\$data = " . var_export($config_arr, true) . ";\r\n";
		$content .= "?>";
		if (!file_put_contents($cache_file_path, $content, LOCK_EX))
		{
			$fp = @fopen($cache_file_path, 'wb+');
			if (!$fp)
			{
				return $access_token;
			}
			if (!@fwrite($fp, trim($content)))
			{
				return $access_token;
			}
			@fclose($fp);
		}
		
		return $access_token;
	}
}
function send_template_message($data){
	$access_token = get_access_token();
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
	$result = https_request($url, $data);
	return json_decode($result,true);
}
//检查缓存
function check_cache($cache,$dir,$days=1)
{
	$cachename=QISHI_ROOT_PATH.'data/'.$dir."/".$cache;
	if (!is_writable(QISHI_ROOT_PATH.'data/'.$dir.'/'))
	{
	exit("请先将“".$dir."”目录设置可读写！");
	}
	if (file_exists($cachename))
	{
		$filemtime=filemtime($cachename);
		if ($filemtime>strtotime("-".$days." day"))
		{
			return file_get_contents($cachename);
		}
	}
	return false;
}
//写入缓存
function write_cache($cache, $json, $dir)
{
	$content = $json;
	$cachename=QISHI_ROOT_PATH.'data/'.$dir."/".$cache;
	if (!file_put_contents($cachename, $content, LOCK_EX))
	{
		$fp = @fopen($cachename, 'wb+');
		if (!$fp)
		{
			exit('生cache文件失败，请设置“'.$dir.'”的读写权限');
		}
		if (!@fwrite($fp, trim($content)))
		{
			exit('生cache文件失败，请设置“'.$dir.'”的读写权限');
		}
		@fclose($fp);
	}
}
function baidu_submiturl($urls,$param){
	global $_CFG;
	$_SUBURL=get_cache('baidu_submiturl');
	if($_SUBURL['token'] && $_SUBURL[$param]=='1' && function_exists('curl_init')){
		if(!is_array($urls)){
			$urls = array($urls);
		}
		$site_domain = str_replace('http://','',$_CFG['site_domain']);
		$api = 'http://data.zz.baidu.com/urls?site='.$site_domain.'&token='.$_SUBURL['token'];
		$ch = curl_init();
		$options =  array(
		    CURLOPT_URL => $api,
		    CURLOPT_POST => true,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POSTFIELDS => implode("\n", $urls),
		    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
	}
}
function get_all_subsite(){
	global $db;
	return $db->getall("select * from ".table('subsite')." where s_effective=1");
}
function replace_special_url($url,$alias,$subsite_id){
	global $_CFG;
	$company_page = array('QS_companylist','QS_companyshow','QS_companyinfo','QS_companyjobs','QS_companycomment','QS_companynews','QS_companynewsshow');
	$job_page = array('QS_jobsshow');
	$resume_page = array('QS_resumeshow');
	$news_page = array('QS_newsshow');
	$explain_page = array('QS_explainshow');
	$notice_page = array('QS_noticeshow');
	$jobfair_page = array('QS_jobfairshow','QS_jobfairexhibitors','QS_industry_jobfair','QS_industrys_jobfair');
	$train_page = array('QS_train_agencyshow','QS_train_agency_curriculum','QS_train_agency_lecturer','QS_train_agency_news','QS_train_newsshow');
	$hunter_page = array('QS_hunter_jobsshow','QS_hunter_show','QS_hunter_newsshow');
	if(in_array($alias,$company_page))
	{
		$url = $_CFG['company_url']?str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['company_url'],$url):$url;
	}
	elseif(in_array($alias,$job_page))
	{
		if($_CFG['job_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['job_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$resume_page))
	{
		if($_CFG['resume_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['resume_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$news_page))
	{
		if($_CFG['news_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['news_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$explain_page))
	{
		if($_CFG['explain_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['explain_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$notice_page))
	{
		if($_CFG['notice_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['notice_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$jobfair_page))
	{
		if($_CFG['jobfair_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['jobfair_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$train_page))
	{
		if($_CFG['train_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['train_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	elseif(in_array($alias,$hunter_page))
	{
		if($_CFG['hunter_url']){
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$_CFG['hunter_url'],$url);
		}elseif($subsite_id>0){
			$sub_url = get_subsiteurl_by_id($subsite_id);
			$url = str_replace($_CFG['site_domain'].$_CFG['site_dir'],$sub_url,$url);
		}
	}
	return $url;
}
function get_subsiteurl_by_id($subsite_id){
	global $_SUBSITE;
	$subsiteinfo = array();
	foreach ($_SUBSITE as $key => $value) {
		$subsiteinfo[$value['id']] = $value['domain'];
	}
	return $subsiteinfo[$subsite_id];
}
function get_m_subsiteurl_by_id($subsite_id){
	global $_SUBSITE;
	$subsiteinfo = array();
	foreach ($_SUBSITE as $key => $value) {
		$subsiteinfo[$value['id']] = $value['m_domain'];
	}
	return $subsiteinfo[$subsite_id];
}
function check_url($subsite_id,&$smarty,$page_url){
	global $_CFG;
	$current_host = 'http://'.$_SERVER['HTTP_HOST'];//取得当前域名
	$current_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';//判断地址后面部分
	$url = $current_host.$current_url;
	if($page_url){
		if($current_host!=rtrim($page_url,'/')){
			if($_CFG['subsite_method']==1){
				$url = str_replace($current_host,rtrim($page_url,'/'),$url);
				redirect301($url);
			}else{
				header("HTTP/1.1 404 Not Found");
				$smarty->display("404.htm");
				exit();
			}
		}
	}else{
		if($_CFG['subsite_id']!=$subsite_id){
			if($_CFG['subsite_method']==1){
				$suburl = get_subsiteurl_by_id($subsite_id);
				$url = str_replace($current_host,rtrim($suburl,'/'),$url);
				redirect301($url);
			}else{
				header("HTTP/1.1 404 Not Found");
				$smarty->display("404.htm");
				exit();
			}
		}
	}
}
function check_m_url($subsite_id,&$smarty,$page_url){
	global $_CFG;
	$current_host = 'http://'.$_SERVER['HTTP_HOST'];//取得当前域名
	$current_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';//判断地址后面部分
	$url = $current_host.$current_url;
	if($page_url){
		if($current_host!=rtrim($page_url,'/')){
			if($_CFG['subsite_method']==1){
				$url = str_replace($current_host,rtrim($page_url,'/'),$url);
				redirect301($url);
			}else{
				header("HTTP/1.1 404 Not Found");
				$smarty->display("404.htm");
				exit();
			}
		}
	}else{
		if($_CFG['subsite_id']!=$subsite_id){
			if($_CFG['subsite_method']==1){
				$suburl = get_m_subsiteurl_by_id($subsite_id);
				$url = str_replace($current_host,rtrim($suburl,'/'),$url);
				redirect301($url);
			}else{
				header("HTTP/1.1 404 Not Found");
				$smarty->display("404.htm");
				exit();
			}
		}
	}
}
function redirect301($url){
	header('HTTP/1.1 301 Moved Permanently');//发出301头部
	header('Location:'.$url);//跳转
}
function subsiteinfo(&$_CFG)
{
	global $_SUBSITE;
		foreach($_SUBSITE as $key=> $sub)
		{
		$_CFG['district_array'][]=array('subsite'=>$sub['districtname'],'url'=>"http://".$key);
		}
	$host=$_SERVER['HTTP_HOST'];
	if (array_key_exists($host,$_SUBSITE))
	{
		$subsite=$_SUBSITE[$host];
		$_CFG['site_domain']="http://".$host;
		$_CFG['subsite_districtname']=str_replace('市','',$subsite['districtname']);
		$_CFG['subsite_districtname']=str_replace('省','',$_CFG['subsite_districtname']);
		$_CFG['site_name']=$subsite['sitename'];
		$_CFG['districtid']=$subsite['district'];
		if (empty($_GET['district_cn']))
		{
		$_GET['district_cn']=$_CFG['subsite_districtname'];
		}
		$_CFG['subsite_id']=$subsite['id'];
		$subsite['logo']?$_CFG['web_logo']=$subsite['logo']:'';
		$subsite['tpl']?$_CFG['template_dir']=$subsite['tpl'].'/':'';
		$subsite['title']?$_CFG['title']=$subsite['title']:'';
		$subsite['keywords']?$_CFG['keywords']=$subsite['keywords']:'';
		$subsite['description']?$_CFG['description']=$subsite['description']:'';
	}
}
function mobile_subsiteinfo(&$_CFG)
{
	global $_M_SUBSITE;
		foreach($_M_SUBSITE as $key=> $sub)
		{
		$_CFG['m_district_array'][]=array('subsite'=>$sub['districtname'],'url'=>"http://".$key);
		}
	$host=$_SERVER['HTTP_HOST'];
	if (array_key_exists($host,$_M_SUBSITE))
	{
		$subsite=$_M_SUBSITE[$host];
		$_CFG['site_domain']="http://".$host;
		$_CFG['wap_domain']=rtrim($subsite['m_domain'],'/');
		$_CFG['subsite_districtname']=str_replace('市','',$subsite['districtname']);
		$_CFG['subsite_districtname']=str_replace('省','',$_CFG['subsite_districtname']);
		$_CFG['site_name']=$subsite['sitename'];
		$_CFG['districtid']=$subsite['district'];
		if (empty($_GET['district_cn']))
		{
		$_GET['district_cn']=$_CFG['subsite_districtname'];
		}
		$_CFG['subsite_id']=$subsite['id'];
		$subsite['logo']?$_CFG['web_logo']=$subsite['logo']:'';
		$subsite['tpl']?$_CFG['template_dir']=$subsite['tpl'].'/':'';
		$subsite['title']?$_CFG['title']=$subsite['title']:'';
		$subsite['keywords']?$_CFG['keywords']=$subsite['keywords']:'';
		$subsite['description']?$_CFG['description']=$subsite['description']:'';
	}
}
// 获取分站 地区列表
function get_subsite_district($pid)
{
	global $db;
	$pid=intval($pid);
	return $db->getall("select * from ".table("category_district")." where parentid=$pid ");
}
function getIpLookup($ip = ''){  
    if(empty($ip)){  
        $ip = getip();  
    }
    if($ip===false){
    	return false;
    }  
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
    if(empty($res)){ return false; }  
    $jsonMatches = array();  
    preg_match('#\{.+?\}#', $res, $jsonMatches);  
    if(!isset($jsonMatches[0])){ return false; }  
    $json = json_decode($jsonMatches[0], true);  
    if(isset($json['ret']) && $json['ret'] == 1){  
        $json['ip'] = $ip;  
        unset($json['ret']);  
    }else{  
        return false;  
    }  
    return $json;  
}
function check_subsite_url(){
	global $dbhost,$dbuser,$dbpass,$dbname;
	require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
	$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
	unset($dbhost,$dbuser,$dbpass,$dbname);
	$districtinfo = getIpLookup();
	if($districtinfo===false){
		return false;
	}
	$province = utf8_to_gbk($districtinfo['province']);
	$city = utf8_to_gbk($districtinfo['city']);
	$subinfo = $db->getone("select * from ".table('subsite')." where s_effective=1 and (s_districtname like '%".$province."%' or s_districtname like '%".$city."%') order by s_id desc limit 1");
	if($subinfo){
		return array('disname'=>$subinfo['s_districtname'],'sitename'=>$subinfo['s_sitename'],'url'=>'http://'.$subinfo['s_domain']);
	}else{
		return false;
	}
}
function check_m_subsite_url(){
	global $dbhost,$dbuser,$dbpass,$dbname;
	require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
	$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
	unset($dbhost,$dbuser,$dbpass,$dbname);
	$districtinfo = getIpLookup();
	if($districtinfo===false){
		return false;
	}
	$province = utf8_to_gbk($districtinfo['province']);
	$city = utf8_to_gbk($districtinfo['city']);
	$subinfo = $db->getone("select * from ".table('subsite')." where s_effective=1 and (s_districtname like '%".$province."%' or s_districtname like '%".$city."%') order by s_id desc limit 1");
	if($subinfo){
		return array('disname'=>$subinfo['s_districtname'],'sitename'=>$subinfo['s_sitename'],'url'=>'http://'.($subinfo['s_m_domain']?$subinfo['s_m_domain']:($subinfo['s_domain'].'/m/')));
	}else{
		return false;
	}
}
//拼装cookiedomain
function get_cookiedomain(){
	global $_CFG,$QS_cookiedomain;
	$cookiedomain = explode('.',str_replace('http://','',$_CFG['site_domain']));
	$cookiedomain_length = count($cookiedomain);
	return $cookiedomain[$cookiedomain_length-2].'.'.$cookiedomain[$cookiedomain_length-1];
}
?>