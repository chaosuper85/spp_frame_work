<?php /* Smarty version 3.1.24, created on 2015-10-24 18:28:33
         compiled from "/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/template/tpl.prompt.html" */ ?>
<?php
/*%%SmartyHeaderCode:1362884731562b5d512daeb4_95487791%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d9e5b08ec260e9a9f4f667841e6de00f4674895' => 
    array (
      0 => '/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/template/tpl.prompt.html',
      1 => 1445660055,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1362884731562b5d512daeb4_95487791',
  'variables' => 
  array (
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_562b5d5130e927_36686327',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_562b5d5130e927_36686327')) {
function content_562b5d5130e927_36686327 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1362884731562b5d512daeb4_95487791';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php echo $_smarty_tpl->tpl_vars['message']->value;?>

</body>
</html><?php }
}
?>