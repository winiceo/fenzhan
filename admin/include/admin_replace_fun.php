<?php
 /*
 * 74cms 计划任务
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
function get_list($offset, $perpage, $get_sql= '')
{
	global $db;
	$row_arr = array();
	$limit=" LIMIT ".$offset.','.$perpage;
	$result = $db->query("SELECT * FROM ".table('relation').$get_sql.$limit);
	while($row = $db->fetch_array($result))
	{

	
	    $row_arr[] = $row;
	}
	return $row_arr;	
}
function del_replace($id)
{
	global $db;
	if(!is_array($id)) $id=array($id);
	$return=0;
	$sqlin=implode(",",$id);
	if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
	{
		if (!$db->query("Delete from ".table('relation')." WHERE  id  IN (".$sqlin.")   ")) return false;
		$return=$return+$db->affected_rows();
	}
	return $return;
}
function get_replace_one($id)
{
	global $db;
	$sql = "select * from ".table('relation')." where id=".intval($id)."";
	return $db->getone($sql);
}
function get_title_list()
{
    global $db;
    $row_arr = array();
    $result = $db->query("SELECT id,name FROM ".table('relation')." where type=1 group by name");
    while($row = $db->fetch_array($result))
    {


        $row_arr[] = $row;
    }
    return $row_arr;
}
function get_source_list()
{
    global $db;
    $row_arr = array();
    $result = $db->query("SELECT  source FROM ".table('relation')." where type=1 group by source ");
	 $tmp=array();
    while($row = $db->fetch_array($result))
    {
 		$cc=explode(",",$row["source"]);
		//var_dump($cc);
		foreach($cc as $key=>$v){
			if($v){
				$tmp[]=$v;
			}

		}

    }
	$tmp=array_unique($tmp);
	$row_arr=[];
	foreach($tmp as $k=>$v){
		$row_arr[]=array("source"=>$v);
	}

	 //var_dump(array_unique($row_arr));

    return ($row_arr);
}

//获取字段标题
function get_title_field($order="id asc ")
{
	global $db;
	$row_arr = array();

	$result = $db->query("SELECT * FROM " . table('resume_field') . " order by ".$order." ");
	while ($row = $db->fetch_array($result)) {

		$row_arr[] = $row;
	}
	return $row_arr;
}
function get_title_key($key)
{
	global $db;
	$sql = "select * from ".table('resume_field')." where `key`='".($key)."'";
	return $db->getone($sql);
}
?>