<?php
function tpl_function_qishi_subsite($params, &$smarty)
{
$subsite=get_cache('subsite');
$list =array();
foreach ($subsite as $key => $value) {
	$list[] = $value;
}
$smarty->assign('list',$list);
}
?>