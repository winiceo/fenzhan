<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-01-29 13:45 CST */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="templates/css/css.css" rel="stylesheet" type="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<link rel="shortcut icon" href="<?php echo $this->_vars['QISHI']['site_dir']; ?>
favicon.ico" />
<script src="templates/js/jquery.js" type="text/javascript" language="javascript"></script>
<title>ϵͳ��ʾ - ��ʿPHP�˲�ϵͳ(www.74cms.com)</title>
  <script>
    $(function(){
      $(".step li:eq(3)").css("margin-right", 0);
      $(".setting div:last").css("border", 0);
    });
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
        <li class="complete">�������</li>
        <li class="complete">��������</li>
        <li>��ʼ��װ</li>
        <li>�ɹ���װ</li>
        <div class="clear"></div>
      </ul>
    </div>
    <div class="install_error">
      <div class="error"><?php echo $this->_vars['msg']; ?>
</div>
      <div class="error_but">
        <input type="button" value="��һ��" class="up" onclick="javascript:history.back();"/>
        <input type="button" value="ȡ����װ" class="down" onclick="javascript:self.close()"/>
      </div>
    </div>
    
    
    <div class="copyright">
      Copyright @ 2015 74cms.com All Right Reserved
    </div>
  </div>
</body>
</html>