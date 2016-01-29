<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-01-29 13:44 CST */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="templates/css/css.css" rel="stylesheet" type="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<link rel="shortcut icon" href="<?php echo $this->_vars['QISHI']['site_dir']; ?>
favicon.ico" />
<script language="javascript" type="text/javascript" src="templates/js/jquery.js"></script>
<title>安装向导 - 骑士PHP人才系统(www.74cms.com)</title>
<script>
	$(function(){
		$(".step li:eq(3)").css("margin-right", 0);
		$(".setting div:last").css("border", 0);
	})
</script>
</head>
<body>
	<div class="install_box">
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		<div class="step">
			<div class="step_show step_2"></div>
			<ul>
				<li class="complete">环境检查</li>
				<li class="complete">参数配置</li>
				<li>开始安装</li>
				<li>成功安装</li>
				<div class="clear"></div>
			</ul>
		</div>
		<form action="index.php?act=4" method="post">
		<div class="setting">
			<div class="setting_check">
				<h3>填写数据库信息</h3>
				<table>
					<tbody>
						<tr height="40">
							<td width="95" align="right">数据库主机：</td>
							<td width="269"><input name="dbhost" id="dbhost" value="localhost" type="text" class="install_input_text" /></td>
							<td style="color:#999999;">数据库服务器地址，一般为localhost</td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库用户名：</td>
							<td width="269"><input name="dbuser" id="dbuser" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库密码：</td>
							<td width="269"><input name="dbpass" id="dbpass" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库名称：</td>
							<td width="269"><input name="dbname" id="dbname" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据表前缀：</td>
							<td width="269"><input name="pre" id="pre" value="qs_" type="text" class="install_input_text" /></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="menu_check">
				<h3>管理员账号</h3>
				<table>
					<tbody>
						<tr height="40">
							<td width="95" align="right">管理员姓名：</td>
							<td width="269"><input name="admin_name" id="admin_name" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">登录密码：</td>
							<td width="269"><input name="admin_pwd" id="admin_pwd" type="password" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">密码确认：</td>
							<td width="269"><input name="admin_pwd1" id="admin_pwd1" type="password" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">电子邮箱：</td>
							<td width="269"><input name="admin_email" id="admin_email" type="text" class="install_input_text" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="next">
			<input type="button" value="上一步" class="up" onclick="window.location.href='index.php?act=2';"/>
			<input type="submit" value="下一步" class="down" onclick="openLayer('op1','tis');"/>
		</div>
		</form>
		<div class="copyright">
			Copyright @ 2015 74cms.com All Right Reserved
		</div>
		<span id="op1"></span>
	</div>
<!--弹出层的内容-->

<!--弹出层的内容结束-->
</body>
</html>