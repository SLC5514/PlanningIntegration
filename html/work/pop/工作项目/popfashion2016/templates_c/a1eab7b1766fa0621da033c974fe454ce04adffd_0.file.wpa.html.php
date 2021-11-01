<?php /* Smarty version 3.1.27, created on 2020-06-29 13:51:02
         compiled from "/data/htdocs/popfashion2016/views/wpa.html" */ ?>
<?php
/*%%SmartyHeaderCode:1942800705ef9814633a962_76721536%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a1eab7b1766fa0621da033c974fe454ce04adffd' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/wpa.html',
      1 => 1588929191,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1942800705ef9814633a962_76721536',
  'variables' => 
  array (
    'title' => 0,
    'type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ef981463d1686_06160726',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ef981463d1686_06160726')) {
function content_5ef981463d1686_06160726 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1942800705ef9814633a962_76721536';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>在线客服 - <?php ob_start();
echo $_smarty_tpl->tpl_vars['title']->value;
$_tmp1=ob_get_clean();
echo $_tmp1;?>
</title>
    <style>
        .qq-btn {
            border: none;
            color: #fff;
            visibility: hidden;
        }
    </style>
</head>
<body>
<button id="QQ_Consult" class="qq-btn"></button>

<?php echo '<script'; ?>
 src="<?php echo STATIC_URL2;?>
/global/js/common/jquery-1.9.1.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 charset="utf-8" src="//wpa.b.qq.com/cgi/wpa.php"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    if (parent === self) {
        // 不在 iframe 中打开则跳转至首页
        location.href = '/';
    }
    // 售前:0 或不传 售后:1   鞋子：2    箱包：3    家纺：4
    var type = parseInt('<?php ob_start();
echo $_smarty_tpl->tpl_vars['type']->value;
$_tmp2=ob_get_clean();
echo $_tmp2;?>
');
    var nameAccount;
    if(type == '1'){
        nameAccount = '800020016';
    }else if(type == '2'){
        nameAccount = '800036829';
    }else if(type == '3'){
        nameAccount = '800036829';
    }else if(type == '4'){
        nameAccount = '800067136';
    }else{
        nameAccount = '800030036';
    }
    $(function () {
        BizQQWPA.addCustom({
            aty: '0',
            nameAccount: nameAccount,
            selector: 'QQ_Consult'
        });
        setTimeout(function () {
            $('#QQ_Consult').click();
        }, 300);
    });

    /**
     * #WPA3-SELECT-PANEL-CLOSE 右上角关闭按钮
     * #WPA3-SELECT-PANEL-AIO-CHAT QQ帐号聊天按钮
     * #WPA3-SELECT-PANEL-ANONY-CHAT 匿名聊天按钮
     */
    $(document).on('click', '#WPA3-SELECT-PANEL-CLOSE, #WPA3-SELECT-PANEL-AIO-CHAT, #WPA3-SELECT-PANEL-ANONY-CHAT', function (e) {
        parent.hideWPA && parent.hideWPA();
    });
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
?>