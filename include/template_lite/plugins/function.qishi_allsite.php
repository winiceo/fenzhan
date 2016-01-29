<?php
/*********************************************
*导航栏目
* *******************************************/
function tpl_function_qishi_allsite($params, &$smarty)
{
	global $db,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "调用名称":
			$aset['alias'] = $a[1];
			break;
		case "列表名":
			$aset['listname'] = $a[1];
			break;
		case "字母":
			$aset['wordcut'] = $a[1];
			break;
		case "分批":
			$aset['cutposition'] = $a[1];
			break;
		case "数量":
			$aset['num'] = $a[1];
			break;
		}
	}
	if(intval($aset['num'])>0){
		$limitsql = ' limit '.intval($aset['num']);
	}else{
		$limitsql = '';
	}
	$list1 = array();
	$list2 = array();
	$list = array();
	$subsite = $db->getall("select * from ".table('subsite')." where s_effective=1 order by s_index asc ".$limitsql);
	if($aset['wordcut']==1){
		foreach ($subsite as $key => $value) {
			$value['s_domain'] = 'http://'.$value['s_domain'];
			$value['s_index'] = strtoupper($value['s_index']);
			if(intval($aset['cutposition'])>0){
				$count = count($list1);
				if($count>=$aset['cutposition']){
					$list2[$value['s_index']][] = $value;
				}else{
					$list1[$value['s_index']][] = $value;
				}
			}else{
				$list1[$value['s_index']][] = $value;
			}
			$list = array($list1,$list2);
		}
	}else{
		foreach ($subsite as $key => $value) {
			$value['s_domain'] = 'http://'.$value['s_domain'];
			$list[] = $value;
		}
	}

	$aset['listname']=$aset['listname']?$aset['listname']:"list";
	$smarty->assign($aset['listname'],$list);
}
?>