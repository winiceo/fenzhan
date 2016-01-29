<?php
 /*
 * 74cms 管理中心分站函数
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
 if(!defined('IN_QISHI'))
 {
 	die('Access Denied!');
 }

function get_subsite_list($subsiteid=0)
{
	global $db;
	if(intval($subsiteid)>0){
		$wheresql = ' where s_id='.intval($subsiteid).' ';
	}else{
		$wheresql = '';
	}
	$sql = "select * from ".table('subsite').$wheresql." ORDER BY s_order desc,s_id asc ";
	return $db->getall($sql);
}

function get_subsite_one($id)
{
	global $db;
	$sql = "select * from ".table('subsite')." where s_id=".intval($id)." LIMIT 1";
	return $db->getone($sql);
}
function del_subsite($id)
{
	global $db;
	if(!is_array($id)) $id=array($id);
	$return=0;
	$sqlin=implode(",",$id);
	if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
	{
		if (!$db->query("Delete from ".table('subsite')." WHERE s_id IN (".$sqlin.")")) return false;
		$return=$return+$db->affected_rows();
	}
	return $return;
}
function refresh_subsite_cache()
{
	global $db,$_CFG;
	$cache_file_path =QISHI_ROOT_PATH. "data/cache_subsite.php";
	$cache_file_path_m =QISHI_ROOT_PATH. "data/cache_m_subsite.php";
		$sql = "SELECT * FROM ".table('subsite')." WHERE s_effective=1 ORDER BY s_order desc,s_id desc";
		$arr = $db->getall($sql);
			foreach($arr as $key=> $val)
			{
			$s_m_domain_key = $val['s_m_domain']?$val['s_m_domain']:$val['s_domain'];
			$config_arr[$val['s_domain']] =array("id"=>$val['s_id'],"sitename"=>$val['s_sitename'],"domain"=>'http://'.$val['s_domain'].'/',"m_domain"=>$val['s_m_domain']?('http://'.$val['s_m_domain'].'/'):('http://'.$val['s_domain'].'/m/'),"district"=>$val['s_district'],"districtname"=>$val['s_districtname'],"tpl"=>$val['s_tpl'],"logo"=>$val['s_logo'],"title"=>$val['s_title'],"keywords"=>$val['s_keywords'],"description"=>$val['s_description']);
			$config_m_arr[$s_m_domain_key] =array("id"=>$val['s_id'],"sitename"=>$val['s_sitename'],"domain"=>'http://'.$val['s_domain'].'/',"m_domain"=>$val['s_m_domain']?('http://'.$val['s_m_domain'].'/'):('http://'.$val['s_domain'].'/m/'),"district"=>$val['s_district'],"districtname"=>$val['s_districtname'],"tpl"=>$val['s_tpl'],"logo"=>$val['s_logo'],"title"=>$val['s_title'],"keywords"=>$val['s_keywords'],"description"=>$val['s_description']);
			}
		write_static_cache($cache_file_path,$config_arr);
		write_static_cache($cache_file_path_m,$config_m_arr);
}
?>