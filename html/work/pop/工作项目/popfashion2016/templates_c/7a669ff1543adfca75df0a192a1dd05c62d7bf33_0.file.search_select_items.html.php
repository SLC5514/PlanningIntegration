<?php /* Smarty version 3.1.27, created on 2020-06-15 09:59:14
         compiled from "/data/htdocs/popfashion2016/views/lists/search_select_items.html" */ ?>
<?php
/*%%SmartyHeaderCode:11731983665ee6d5f22075d0_58299590%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a669ff1543adfca75df0a192a1dd05c62d7bf33' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/lists/search_select_items.html',
      1 => 1589427809,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11731983665ee6d5f22075d0_58299590',
  'variables' => 
  array (
    'column_id' => 0,
    'matchs' => 0,
    'filter' => 0,
    'id' => 0,
    'name' => 0,
    'tips' => 0,
    'item' => 0,
    'iSeason' => 0,
    'val' => 0,
    'columnArr' => 0,
    'keyword' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ee6d5f2523c62_61484373',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ee6d5f2523c62_61484373')) {
function content_5ee6d5f2523c62_61484373 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '11731983665ee6d5f22075d0_58299590';
if (in_array($_smarty_tpl->tpl_vars['column_id']->value,array('all',1,2))) {?>
<div class="item">
    <?php
$_from = $_smarty_tpl->tpl_vars['matchs']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['name']->_loop = false;
$_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['id']->value => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
$foreach_name_Sav = $_smarty_tpl->tpl_vars['name'];
?>
        <span class="<?php if ($_smarty_tpl->tpl_vars['filter']->value['match_id'] == $_smarty_tpl->tpl_vars['id']->value) {?>action<?php }?> js-jump" data-name="match" data-id="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</span>
    <?php
$_smarty_tpl->tpl_vars['name'] = $foreach_name_Sav;
}
?>
</div>
<?php }?>

<div class="select_items">
    <?php if ($_smarty_tpl->tpl_vars['tips']->value) {?>
        <?php if ($_smarty_tpl->tpl_vars['tips']->value['gen']['sub']) {?>
            <div class="select_item <?php if ($_smarty_tpl->tpl_vars['filter']->value['gen_id']) {?>action_item<?php }?>">
                <span data-id="<?php echo $_smarty_tpl->tpl_vars['filter']->value['gen_id'];?>
">性别 ：<?php echo $_smarty_tpl->tpl_vars['filter']->value['gen'];?>
</span>
                <i></i>
                <div class="list">
                    <div class="contentHolder">
                        <ul class="content">
                            <?php
$_from = $_smarty_tpl->tpl_vars['tips']->value['gen']['sub'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <li class="js-jump" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['key'];?>
" data-name="gen"><?php echo $_smarty_tpl->tpl_vars['item']->value['value'];?>
</li>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php if (in_array($_smarty_tpl->tpl_vars['column_id']->value,array(82,120))) {?>
                <span class="<?php if ($_smarty_tpl->tpl_vars['column_id']->value == 120) {?>filter<?php }?> js-jump"   data-id="<?php if ($_smarty_tpl->tpl_vars['column_id']->value == 120) {?>82<?php } else { ?>120<?php }?>" data-name="col">只看大牌花型</span>
            <?php }?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['tips']->value['ind']['sub']) {?>
        <div class="select_item <?php if ($_smarty_tpl->tpl_vars['filter']->value['ind_id']) {?>action_item<?php }?>">
            <span data-id="<?php echo $_smarty_tpl->tpl_vars['filter']->value['ind_id'];?>
">行业 ：<?php echo $_smarty_tpl->tpl_vars['filter']->value['ind'];?>
</span>
            <i></i>
            <div class="list">
                <div class="contentHolder">
                    <ul class="content">
                        <?php
$_from = $_smarty_tpl->tpl_vars['tips']->value['ind']['sub'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                        <li class="js-jump" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['key'];?>
" data-name="ind"><?php echo $_smarty_tpl->tpl_vars['item']->value['value'];?>
</li>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </ul>
                </div>
            </div>
        </div>
        <?php }?>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['filter']->value['sea_id'] == '') {?>
        <?php if ($_smarty_tpl->tpl_vars['iSeason']->value) {?>
        <div class="select_item sea">
            <span data-id="<?php echo $_smarty_tpl->tpl_vars['filter']->value['sea_id'];?>
">季节 ：<?php echo $_smarty_tpl->tpl_vars['filter']->value['sea'];?>
</span>
            <i></i>
            <div class="list">
                <div class="contentHolder">
                    <ul class="content"> 
                        <?php
$_from = $_smarty_tpl->tpl_vars['iSeason']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                        <li class="js-jump" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" data-name="sea" data-url="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</li>
                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                    </ul>
                </div>
            </div>
        </div>
        <?php }?>
    <?php } else { ?>
        <?php if (!in_array($_smarty_tpl->tpl_vars['column_id']->value,array(82,120))) {?>
        <div class="select_item sea active1">
            <span class="js-jump" data-name="sea" data-id="all">季节 ：<?php echo $_smarty_tpl->tpl_vars['filter']->value['sea'];?>
</span>
            <i class="js-jump" data-name="sea" data-id="all"></i>
        </div>
        <?php }?>
    <?php }?>
</div>

<!-- 下拉 -->
<div class="head_slide js-head-slide head-top-static">
    <div class="slide_main">
        <div class="fl logo-box-slide head-logo js-logo-box-slide">
            <a href="https://www.pop-fashion.com/" rel="nofollow" title="POP服装趋势"><img src="<?php echo STATIC_URL2;?>
/global/images/new_common/nav_logo.png" alt="POP服装趋势"><i class="common-icon"></i></a>
            <div class="allWeb">
                <ul class="leftlists">
                    <li><a href="https://www.pop-fashion.com/" target="_blank" rel="nofollow" title="服装"><em class="common-icon fashion"></em>服装</a></li>
                    <li><a href="http://www.pop-bags.com/" target="_blank" rel="nofollow" title="箱包"><em class="common-icon bag"></em>箱包</a></li>
                    <li><a href="http://www.pop-shoe.com/" target="_blank" rel="nofollow" title="鞋子"><em class="common-icon shoe"></em>鞋子</a></li>
                    <li><a href="http://www.51shoushi.com/" target="_blank" rel="nofollow" title="首饰"><em class="common-icon dec"></em>首饰</a></li>
                    <li><a href="http://www.91jiafang.com/" target="_blank" rel="nofollow" title="家纺"><em class="common-icon home"></em>家纺</a></li>
                    <li><a href="http://yuntu.pop136.com/" target="_blank" rel="nofollow" title="云图"><em class="common-icon yuntu"></em>云图</a></li>
                    <li><a href="http://www.pop136.com/" target="_blank" rel="nofollow" title="官网"><em class="common-icon guanw"></em>官网</a></li>
                </ul>
            </div>
        </div>
        <div class="head-slide-content">
            <div class="item_box fl">
                <span><?php echo $_smarty_tpl->tpl_vars['filter']->value['column_name'];?>
</span>
                <span data-id="<?php echo $_smarty_tpl->tpl_vars['column_id']->value;?>
" class="js-all-num"></span>
                <i></i>
                <div class="serach_box">
                    <ul>
                        <?php
$_from = $_smarty_tpl->tpl_vars['columnArr']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['name']->_loop = false;
$_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['id']->value => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
$foreach_name_Sav = $_smarty_tpl->tpl_vars['name'];
?>
                        <li class="js-jump" data-name="col" data-id="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</li>
                        <?php
$_smarty_tpl->tpl_vars['name'] = $foreach_name_Sav;
}
?>
                    </ul>
                </div>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['tips']->value) {?>
                <?php if ($_smarty_tpl->tpl_vars['tips']->value['gen']['sub']) {?>
                <div class="item_list <?php if ($_smarty_tpl->tpl_vars['filter']->value['gen_id']) {?>action<?php }?>">
                    <span data-id="<?php echo $_smarty_tpl->tpl_vars['filter']->value['gen_id'];?>
">性别 ：<label><?php echo $_smarty_tpl->tpl_vars['filter']->value['gen'];?>
</label></span>
                    <i></i>
                    <div class="drop">
                        <div class="contentHolder">
                            <ul class="content">
                                <?php
$_from = $_smarty_tpl->tpl_vars['tips']->value['gen']['sub'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                                <li class="js-jump" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['key'];?>
" data-name="gen"><?php echo $_smarty_tpl->tpl_vars['item']->value['value'];?>
</li>
                                <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php }?>

                <?php if ($_smarty_tpl->tpl_vars['tips']->value['ind']['sub']) {?>
                <div class="seg"></div>
                    <div class="item_list <?php if ($_smarty_tpl->tpl_vars['filter']->value['ind_id']) {?>action<?php }?>">
                        <span data-id="<?php echo $_smarty_tpl->tpl_vars['filter']->value['ind_id'];?>
">行业 ：<label><?php echo $_smarty_tpl->tpl_vars['filter']->value['ind'];?>
</label></span>
                        <i></i>
                        <div class="drop">
                            <div class="contentHolder">
                                <ul class="content">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['tips']->value['ind']['sub'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                                    <li class="js-jump" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['key'];?>
" data-name="ind"><?php echo $_smarty_tpl->tpl_vars['item']->value['value'];?>
</li>
                                    <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php }?>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['filter']->value['sea_id'] == '') {?>
                <?php if ($_smarty_tpl->tpl_vars['iSeason']->value) {?>
                <div class="seg"></div>
                <div class="item_list">
                    <span data-id="<?php echo $_smarty_tpl->tpl_vars['filter']->value['sea_id'];?>
">季节 ：<label><?php echo $_smarty_tpl->tpl_vars['filter']->value['sea'];?>
</label></span>
                    <i></i>
                    <div class="drop">
                        <div class="contentHolder">
                            <ul class="content">
                                <?php
$_from = $_smarty_tpl->tpl_vars['iSeason']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                <li class="js-jump" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" data-name="sea" data-url="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</li>
                                <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php }?>
            <?php } else { ?>
                <?php if (!in_array($_smarty_tpl->tpl_vars['column_id']->value,array(82,120))) {?>
                <div class="seg"></div>
                <div class="item_list item_active">
                    <span class="js-jump" data-name="sea" data-id="all">季节 ：<label><?php echo $_smarty_tpl->tpl_vars['filter']->value['sea'];?>
</label></span>
                    <i class="js-jump" data-name="sea" data-id="all"></i>
                </div>
                <?php }?>
            <?php }?>
            <div class="f-find-box">
                <a class="find-color" href="/Colorsearch/" title="色彩搜图"><i class="common-icon"></i></a>
                <span></span>
                <a class="find-camera js-figure-btn1" href="javascript:void(0);" title="以图搜图"><i class="common-icon"></i></a>
            </div>
            <div class="serach_text">
                <input id="search_box1" class="js-search-ipt" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" growing-track="true" placeholder="搜索你想要的" type="text" οnfοcus="this.placeholder=''"
                    οnblur="this.placeholder='搜索你想要的'" autocomplete='off'>
                <div class="search_listDown search_page_listDown">
                    <div class="menu"></div>
                    <div class="hot-menu">
                        <div class="menu-hot-list">
                            <div class="label js-history-label">历史记录<div class="fr"><span class="operat js-clear-operat">清除记录</span></div></div>
                            <ul class="history-list"></ul>
                            <div class="label">热门搜索</div>
                            <ul class="hot-list"></ul>
                        </div>
                    </div>
                </div>
                <!-- 上传图片 -->
                <div class="files files-sea js-figure-btn1"></div>
                <div class="btn_icon js-search-btn"></div>
            </div>            
        </div>
    </div>
</div><?php }
}
?>