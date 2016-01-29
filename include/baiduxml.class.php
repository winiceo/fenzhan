<?php
 /*
 * 74cms 百度开放平台
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
class BaiduXML
{
	var $XML_head = '';
	var $XML_foot = '';
	var $XML = '';
	var $encoding = 'GBK';
	function BaiduXML()
	{
	$this->__construct();
	}
	function __construct()
	{
		$this->XML_head  = "<?xml version=\"1.0\" encoding=\"{$this->encoding}\"?>\n";
		$this->XML_head .= "<urlset>\n";
		$this->XML_foot  = "</urlset>";
	}	
	function XML_url($str)
	{
	$this->XML.="<url>\n";
	$this->XML.="<loc>{$str[0]}</loc>\n";
	$this->XML.="<lastmod>{$str[1]}</lastmod>\n";
	$this->XML.="<changefreq>daily</changefreq>\n";
	$this->XML.="<priority>0.8</priority>\n";
	$this->XML.="<data>\n";
	$this->XML.="<display>\n";
	$this->XML.="<title>{$str[2]}</title>\n";
	$this->XML.="<jobfirstclass>{$str[3]}</jobfirstclass>\n";
	$this->XML.="<jobsecondclass>{$str[4]}</jobsecondclass>\n";
	$this->XML.="<number>{$str[5]}</number>\n";
	$this->XML.="<age>{$str[6]}</age>\n";
	$this->XML.="<sex>{$str[7]}</sex>\n";
	$this->XML.="<description>{$str[8]}</description>\n";
	$this->XML.="<education>{$str[9]}</education>\n";
	$this->XML.="<experience>{$str[10]}</experience>\n";
	$this->XML.="<startdate>{$str[11]}</startdate>\n";
	$this->XML.="<enddate>{$str[12]}</enddate>\n";;
	$this->XML.="<salary>{$str[13]}</salary>\n";
	$this->XML.="<city>{$str[14]}</city>\n";
	$this->XML.="<district>{$str[15]}</district>\n";
	$this->XML.="<location>{$str[16]}</location>\n";
	$this->XML.="<type>{$str[17]}</type>\n";
	$this->XML.="<officialname>{$str[18]}</officialname>\n";
	$this->XML.="<commonname>{$str[19]}</commonname>\n";
	$this->XML.="<employerurl>{$str[20]}</employerurl>\n";
	$this->XML.="<companyaddress>{$str[21]}</companyaddress>\n";
	$this->XML.="<employertype>{$str[22]}</employertype>\n";
	$this->XML.="<size>{$str[23]}</size>\n";
	$this->XML.="<companydescription>{$str[24]}</companydescription>\n";
	$this->XML.="<email>{$str[25]}</email>\n";
	$this->XML.="<industry>{$str[26]}</industry>\n";
	$this->XML.="<secondindustry>{$str[27]}</secondindustry>\n";
	$this->XML.="<companyID>{$str[28]}</companyID>\n";
	$this->XML.="<source>{$str[29]}</source>\n";
	$this->XML.="<sourcelink>{$str[30]}</sourcelink>\n";
	$this->XML.="<joblink>{$str[31]}</joblink>\n";
	$this->XML.="<jobwapurl>{$str[32]}</jobwapurl>\n";
	$this->XML.="</display>\n";
	$this->XML.="</data>\n";
	$this->XML.="</url>\n";
	}
	function XML_index_put($path,$index=array())
	{
	$this->XML="<?xml version=\"1.0\" encoding=\"{$this->encoding}\"?>\n"; 
	$this->XML.="<sitemapindex>\n";
	foreach ($index as $a)
	{
	$this->XML.="<sitemap>\n"; 
	$this->XML.="<loc>{$a[0]}</loc> \n"; 
	$this->XML.="<lastmod>{$a[1]}</lastmod> \n"; 
	$this->XML.="</sitemap>\n";
	} 
	$this->XML.="</sitemapindex>\n"; 
	return file_put_contents($path,$this->XML, LOCK_EX);
	}
	function XML_put($path)
	{
		$return=file_put_contents($path,$this->XML_head.$this->XML.$this->XML_foot, LOCK_EX);
		$this->XML='';
		return $return;
	}
}
?>