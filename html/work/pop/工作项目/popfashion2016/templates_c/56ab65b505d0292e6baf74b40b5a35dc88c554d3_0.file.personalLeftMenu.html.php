<?php /* Smarty version 3.1.27, created on 2020-06-29 13:47:49
         compiled from "/data/htdocs/popfashion2016/views/personalCenter/personalLeftMenu.html" */ ?>
<?php
/*%%SmartyHeaderCode:9798024325ef980851b0869_70448826%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '56ab65b505d0292e6baf74b40b5a35dc88c554d3' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/personalCenter/personalLeftMenu.html',
      1 => 1587872785,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9798024325ef980851b0869_70448826',
  'variables' => 
  array (
    'LeftManageMenuHtml' => 0,
    'accountType' => 0,
    'canChangLoginAccount' => 0,
    'vipflag' => 0,
    'invoiceLink' => 0,
    'vipflagShare' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ef98085234410_56834935',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ef98085234410_56834935')) {
function content_5ef98085234410_56834935 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '9798024325ef980851b0869_70448826';
?>
<div class="col-sub  fl">
    <!-- item-topic -->
    <div class="item-topic">
        <h3 class="hd"><strong><i></i>账号管理</strong></h3>
        <div class="bd clearfix">
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp2=ob_get_clean();
if ($_tmp2 == 0) {?> class="cur" <?php }?> title="修改账号资料" href="/member/base/"><i></i>基本信息<span></span></a>
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp3=ob_get_clean();
if ($_tmp3 == 1) {?> class="cur" <?php }?> title="修改密码" href="/member/pwd/"><i></i>修改密码<span></span></a>
            <?php ob_start();
echo $_smarty_tpl->tpl_vars['accountType']->value;
$_tmp4=ob_get_clean();
if ($_tmp4 == 1) {?>
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp5=ob_get_clean();
if ($_tmp5 == 2) {?> class="cur" <?php }?> title="绑定手机" href="/member/mobile/"><i></i>绑定手机号<span></span></a>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['canChangLoginAccount']->value) {?>
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp6=ob_get_clean();
if ($_tmp6 == 10) {?> class="cur" <?php }?> title="修改登录账号" href="/member/moddefaultlogin/"><i></i>修改默认登录账号<span></span></a>
            <?php }?>
            <?php ob_start();
echo $_smarty_tpl->tpl_vars['vipflag']->value;
$_tmp7=ob_get_clean();
if ($_tmp7 == 1) {?>
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp8=ob_get_clean();
if ($_tmp8 == 3) {?> class="cur" <?php }?> title="关联账号管理" href="/member/associate/"><i></i>关联账号管理<span></span></a>
            <?php }?>
            <!--a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp9=ob_get_clean();
if ($_tmp9 == 4) {?> class="cur" <?php }?> title="帐户积分明细" href="/member/integral/"><i></i>帐户积分<span></span></a-->
            <?php ob_start();
echo $_smarty_tpl->tpl_vars['vipflag']->value;
$_tmp10=ob_get_clean();
if ($_tmp10 == 1) {?>
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp11=ob_get_clean();
if ($_tmp11 == 5) {?> class="cur" <?php }?> title="索取发票" href="<?php echo $_smarty_tpl->tpl_vars['invoiceLink']->value;?>
" target="_blank"> <i></i>索取发票<span></span></a>
            <?php }?>
            <!--<?php ob_start();
echo $_smarty_tpl->tpl_vars['vipflagShare']->value;
$_tmp12=ob_get_clean();
if ($_tmp12 == 3) {?>-->
            <!--<a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp13=ob_get_clean();
if ($_tmp13 == 6) {?> class="cur" <?php }?> title="共享资料" href="/member/sharing/"><i></i>共享资料<span></span></a>-->
            <!--<?php }?>-->
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp14=ob_get_clean();
if ($_tmp14 == 8) {?> class="cur" <?php }?> title="常用软件下载" href="/member/software/"><i></i>常用软件下载<span></span></a>
            <a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp15=ob_get_clean();
if ($_tmp15 == 7) {?> class="cur" <?php }?> title="我要分享" href="/member/share/"><i></i>我要分享<span></span></a>
			<?php ob_start();
echo $_smarty_tpl->tpl_vars['vipflag']->value;
$_tmp16=ob_get_clean();
if ($_tmp16 == 2) {?>
			<a <?php ob_start();
echo $_smarty_tpl->tpl_vars['LeftManageMenuHtml']->value;
$_tmp17=ob_get_clean();
if ($_tmp17 == 9) {?> class="cur" <?php }?> title="解除绑定" href="/member/unbind/"><i></i>解除绑定<span></span></a>
			<?php }?>
        </div>
    </div>
    <!-- /item-topic -->
</div><?php }
}
?>