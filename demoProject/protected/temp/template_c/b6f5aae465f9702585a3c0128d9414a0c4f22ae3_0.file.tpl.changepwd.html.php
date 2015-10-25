<?php /* Smarty version 3.1.24, created on 2015-10-25 11:40:09
         compiled from "/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/template/tpl.changepwd.html" */ ?>
<?php
/*%%SmartyHeaderCode:1334171328562c4f1930ae52_76938894%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6f5aae465f9702585a3c0128d9414a0c4f22ae3' => 
    array (
      0 => '/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/template/tpl.changepwd.html',
      1 => 1445660055,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1334171328562c4f1930ae52_76938894',
  'variables' => 
  array (
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_562c4f193a5236_78523606',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_562c4f193a5236_78523606')) {
function content_562c4f193a5236_78523606 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1334171328562c4f1930ae52_76938894';
?>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>修改密码</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <style>
    .header {
      text-align: center;
    }
    .header h1 {
      font-size: 200%;
      color: #333;
      margin-top: 30px;
    }
    .header p {
      font-size: 14px;
    }
  </style>
</head>
<body>
<div class="header">
  <div class="am-g">
    <h1>修改密码</h1>
    <p>
        <span style="color:red;"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</span>
    <p>
  </div>
  <hr />
</div>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">

    <form method="post" class="am-form" action="/changepasswd.php">
      <label for="email">原密码:</label>
      <input type="password" name="oldpwd" id="oldpwd" value="">
      <br>
      <label for="password">密码:</label>
      <input type="password" name="pwd1" id="pwd1" value="">
      <br>
       <label for="password">重复密码:</label>
      <input type="password" name="pwd2" id="pwd2" value="">
      <br>
      <div class="am-cf">
        <input type="submit" name="" value="修 改" class="am-btn am-btn-primary am-btn-sm am-fl">
      </div>
    </form>
    <hr>
    <p>© 2015 demo.17house.com.</p>
  </div>
</div>
</body>
</html><?php }
}
?>