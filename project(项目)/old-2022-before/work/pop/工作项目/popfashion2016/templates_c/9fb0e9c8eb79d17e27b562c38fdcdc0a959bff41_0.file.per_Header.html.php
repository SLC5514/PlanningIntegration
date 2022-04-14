<?php /* Smarty version 3.1.27, created on 2020-06-29 13:47:49
         compiled from "/data/htdocs/popfashion2016/views/personalCenter/per_Header.html" */ ?>
<?php
/*%%SmartyHeaderCode:3238429595ef9808503ba85_17931540%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9fb0e9c8eb79d17e27b562c38fdcdc0a959bff41' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/personalCenter/per_Header.html',
      1 => 1587872785,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3238429595ef9808503ba85_17931540',
  'variables' => 
  array (
    'avatarUri' => 0,
    'user' => 0,
    'vip_type' => 0,
    'show_btn' => 0,
    'selected_flag' => 0,
    'issetNewMessage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ef980851327e9_55374765',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ef980851327e9_55374765')) {
function content_5ef980851327e9_55374765 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '3238429595ef9808503ba85_17931540';
?>
<div class="person-header clearfix">
    <div class="section-leftimg fl">
        <form method="post" id='uploadAvatarForm' enctype="multipart/form-data">
            <img <?php ob_start();
echo $_smarty_tpl->tpl_vars['avatarUri']->value;
$_tmp1=ob_get_clean();
if ($_tmp1 != '') {?> src="<?php echo STATIC_URL2;
echo $_smarty_tpl->tpl_vars['avatarUri']->value;?>
" <?php } else { ?> src="<?php echo STATIC_URL2;?>
/global/images/member/per_center/avatar.png" <?php }?> id='Avatar'/>
            <div class="input_tx">更换头像</div>
            <input type="file" name="uploadAvatar" id='uploadAvatar' onchange='ajaxFileUpload();'>
        </form>
    </div>
    <div class="section-personinfo fl">
        <div class="personinfo-absolute">
            <p><?php echo $_smarty_tpl->tpl_vars['user']->value['account'];?>
</p>
            <div><i></i><?php echo $_smarty_tpl->tpl_vars['vip_type']->value;?>
</div>
            <?php if ($_smarty_tpl->tpl_vars['show_btn']->value) {?>
            <?php if ($_smarty_tpl->tpl_vars['show_btn']->value == 'renew') {?>
            <a class="js-contact-qq-btn" href="javascript:void(0);" data-type="after">续费</a>
            <?php } else { ?>
            <a class="js-contact-qq-btn" href="javascript:void(0);">开通</a>
            <?php }?>
            <?php }?>
        </div>
    </div>
    <div class="section-tips-tab fl">
        <ul class="clearfix">
            <li class="<?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 1) {?>on<?php }?>"><a href="/member/myHistory/4/">浏览历史</a></li>
            <li class="<?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 2) {?>on<?php }?>"><a href="/member/mycollect/">收藏</a></li>
            <li class="<?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 3) {?>on<?php }?>"><a href="/member/workbench/">工作室</a></li>
            <li class="<?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 4) {?>on<?php }?>"><a href="/member/mydownload/">下载包</a></li>
            <li class="<?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 5) {?>on<?php }?>"><a href="/member/myorderzip/">资料包</a></li>
            <li class="message-li <?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 6) {?>on<?php }?>"><a href="/member/message/">消息<?php if ($_smarty_tpl->tpl_vars['issetNewMessage']->value != 0) {?><i></i><?php }?></a></li>
            <li class="account-base <?php if ($_smarty_tpl->tpl_vars['selected_flag']->value == 7) {?>on<?php }?>"><a href="/member/base/"><i></i>账号设置<em></em></a></li>
        </ul>
    </div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
    function ajaxFileUpload() {
        var options = {
            beforeSubmit: function () {
                return true;
            },
            type: 'post',
            url: '/member/uploadavatar/?' + Math.random(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    $('img#Avatar').attr("src", '<?php echo STATIC_URL2;?>
' + data.info);
                    return true;
                } else {
                    alert(data.info + '\n上传类型：jpg gif png jpeg\n 等比例大的\n图片大小最大：1M');
                    return false;
                }
            }
        };
        $('#uploadAvatarForm').ajaxSubmit(options);
    }
<?php echo '</script'; ?>
><?php }
}
?>