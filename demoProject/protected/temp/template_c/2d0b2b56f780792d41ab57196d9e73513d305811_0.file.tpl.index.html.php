<?php /* Smarty version 3.1.24, created on 2015-10-24 18:28:00
         compiled from "/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/template/tpl.index.html" */ ?>
<?php
/*%%SmartyHeaderCode:430391368562b5d30092f43_18511105%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d0b2b56f780792d41ab57196d9e73513d305811' => 
    array (
      0 => '/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/template/tpl.index.html',
      1 => 1445660055,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '430391368562b5d30092f43_18511105',
  'variables' => 
  array (
    'unReadMsgCnt' => 0,
    'currentUser' => 0,
    'orderList' => 0,
    'order' => 0,
    'msgList' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_562b5d30217c24_04505748',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_562b5d30217c24_04505748')) {
function content_562b5d30217c24_04505748 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '430391368562b5d30092f43_18511105';
?>
<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Management Page</title>
  <meta name="description" content="这是一个 index 页面">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
  
  <?php echo '<script'; ?>
>
  function removeOrder(id) {
	  $.get('/ajax_order.php?action=remove&orderid='+id, function(result){
		  var r = JSON.parse(result);
		  if(r.error == null) {
			  var obj = $('#order_tr_'+id);
			  obj.remove();
		  }
	  });
  }
  
  function add_new_order() {
	  var desc = $('#order_desc').val();
	  var prd_id = $('#prd_id').val();
	  var prd_cnt = $('#prd_cnt').val();
	  
	  $.post('/ajax_order.php?action=add', {orderdesc:desc, prd_id:prd_id, prd_cnt:prd_cnt}, function(result){
		  alert(result);
	  });
  }
  <?php echo '</script'; ?>
>
  
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <strong>Amaze UI</strong> <small>后台管理模板</small>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning"><?php echo $_smarty_tpl->tpl_vars['unReadMsgCnt']->value;?>
</span></a></li>
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span> <?php echo $_smarty_tpl->tpl_vars['currentUser']->value->mName;?>
 <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
          <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>
          <li><a href="#"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
      <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
    </ul>
  </div>
</header>

<div class="am-cf admin-main">
  <!-- sidebar start -->
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
        <li><a href="/index.php"><span class="am-icon-home"></span> 首页</a></li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span> 页面模块 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
            <li><a href="/changepasswd.php" class="am-cf"><span class="am-icon-check"></span> 修改密码<span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="#"><span class="am-icon-puzzle-piece"></span> 帮助页</a></li>
            <li><a href="#"><span class="am-icon-th"></span> 相册页面<span class="am-badge am-badge-secondary am-margin-right am-fr">24</span></a></li>
            <li><a href="#"><span class="am-icon-calendar"></span> 系统日志</a></li>
            <li><a href="#"><span class="am-icon-bug"></span> 404</a></li>
          </ul>
        </li>
        <li><a href="admin-table.html"><span class="am-icon-table"></span> 表格</a></li>
        <li><a href="admin-form.html"><span class="am-icon-pencil-square-o"></span> 表单</a></li>
        <li><a href="/logout.php"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>


    </div>
  </div>
  <!-- sidebar end -->

  <!-- content start -->
  <div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>一些常用模块</small></div>
    </div>

    <ul class="am-avg-sm-1 am-avg-md-4 am-margin am-padding am-text-center admin-content-list ">
      <li><a href="#" class="am-text-success"><span class="am-icon-btn am-icon-file-text"></span><br/>新增页面<br/>2300</a></li>
      <li><a href="#" class="am-text-warning"><span class="am-icon-btn am-icon-briefcase"></span><br/>成交订单<br/>308</a></li>
      <li><a href="#" class="am-text-danger"><span class="am-icon-btn am-icon-recycle"></span><br/>昨日访问<br/>80082</a></li>
      <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-user-md"></span><br/>在线用户<br/>3000</a></li>
    </ul>

    <div class="am-g">
    <div class="am-tab-panel" id="tab2">
        
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
             订单描述
            </div>
            <div class="am-u-sm-8 am-u-md-4">
              <input id = 'order_desc' type="text" class="am-input-sm">
            </div>
            <div class="am-hide-sm-only am-u-md-6">*必填</div>
          </div>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              商品ID
            </div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
              <input id = "prd_id" type="text" value= "1" class="am-input-sm">
            </div>
          </div>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              商品数量
            </div>
            <div class="am-u-sm-8 am-u-md-4">
              <input id = "prd_cnt" value = "0" type="text" class="am-input-sm">
            </div>
            <div class="am-hide-sm-only am-u-md-6">选填</div>
          </div>
            
      </div>
        <div class="am-margin">
            <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "add_new_order()">提交保存</button>
        </div>
  <hr>
      
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th><th>用户名</th><th>订单描述</th><th>商品数量</th><th>管理</th>
          </tr>
          </thead>
          <tbody>
          <?php
$_from = $_smarty_tpl->tpl_vars['orderList']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['order'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['order']->_loop = false;
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['order']->value) {
$_smarty_tpl->tpl_vars['order']->_loop = true;
$foreach_order_Sav = $_smarty_tpl->tpl_vars['order'];
?>
          <tr id="order_tr_<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
"><td><?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['order']->value['name'];?>
</td><td><a href="#"><?php echo $_smarty_tpl->tpl_vars['order']->value['order_desc'];?>
</a></td> <td><span class="am-badge am-badge-success">+<?php ob_start();
echo $_smarty_tpl->tpl_vars['order']->value['product_cnt'];
$_tmp1=ob_get_clean();
echo $_tmp1;?>
</span></td>
            <td>
              <div class="am-dropdown" data-am-dropdown>
                <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                <ul class="am-dropdown-content">
                  <li><a href="#" onclick="removeOrder(<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
);">删除</a></li>
                </ul>
              </div>
            </td>
          </tr>
          <?php
$_smarty_tpl->tpl_vars['order'] = $foreach_order_Sav;
}
?>
          
          </tbody>
        </table>
      </div>
    </div>

    <div class="am-g">
      <div class="am-u-md-6">
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">浏览器统计<span class="am-icon-chevron-down am-fr" ></span></div>
          <div id="collapse-panel-2" class="am-in">
          </div>
        </div>
      </div>

      <div class="am-u-md-6">
   
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-3'}">最近留言<span class="am-icon-chevron-down am-fr" ></span></div>
          <div class="am-panel-bd am-collapse am-in am-cf" id="collapse-panel-3">
            <ul class="am-comments-list admin-content-comment">

              
              <?php
$_from = $_smarty_tpl->tpl_vars['msgList']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['msg'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['msg']->_loop = false;
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['msg']->value) {
$_smarty_tpl->tpl_vars['msg']->_loop = true;
$foreach_msg_Sav = $_smarty_tpl->tpl_vars['msg'];
?>
              <li class="am-comment">
                <a href="#"><img src="http://s.amazeui.org/media/i/demos/bw-2014-06-19.jpg?imageView/1/w/96/h/96" alt="" class="am-comment-avatar" width="48" height="48"></a>
                <div class="am-comment-main">
                  <header class="am-comment-hd">
                    <div class="am-comment-meta"><a href="#" class="am-comment-author"><?php echo $_smarty_tpl->tpl_vars['msg']->value['from_user_name'];?>
</a> 评论于 <time><?php echo $_smarty_tpl->tpl_vars['msg']->value['create_time'];?>
</time></div>
                  </header>
                  <div class="am-comment-bd"><p><?php echo $_smarty_tpl->tpl_vars['msg']->value['content'];?>
</p>
                  </div>
                </div>
              </li>
              <?php
$_smarty_tpl->tpl_vars['msg'] = $foreach_msg_Sav;
}
?>

            </ul>
            <ul class="am-pagination am-fr admin-content-pagination">
              <li class="am-disabled"><a href="#">&laquo;</a></li>
              <li class="am-active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content end -->

</div>

<a href="#" class="am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}">
  <span class="am-icon-btn am-icon-th-list"></span>
</a>

<footer>
  <hr>
  <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
</footer>

<!--[if lt IE 9]>
<?php echo '<script'; ?>
 src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="assets/js/amazeui.ie8polyfill.min.js"><?php echo '</script'; ?>
>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<?php echo '<script'; ?>
 src="assets/js/jquery.min.js"><?php echo '</script'; ?>
>
<!--<![endif]-->
<?php echo '<script'; ?>
 src="assets/js/amazeui.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="assets/js/app.js"><?php echo '</script'; ?>
>
</body>
</html><?php }
}
?>