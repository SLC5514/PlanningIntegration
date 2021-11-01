<?php /* Smarty version 3.1.27, created on 2020-06-18 14:11:47
         compiled from "/data/htdocs/popfashion2016/views/lists/trends_select_items.html" */ ?>
<?php
/*%%SmartyHeaderCode:12109901575eeb05a37a2860_10029638%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c782523c5b39c90e7ef73c6cea2a8def1c25ce2' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/lists/trends_select_items.html',
      1 => 1592204263,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12109901575eeb05a37a2860_10029638',
  'variables' => 
  array (
    'iSeason' => 0,
    'season' => 0,
    'key' => 0,
    'iNo' => 0,
    'val' => 0,
    'iRelationTheme' => 0,
    'v' => 0,
    'categorys' => 0,
    'category' => 0,
    'subcategory' => 0,
    'topicTypes' => 0,
    'topicType' => 0,
    'iTechnologyType' => 0,
    'sRegionalStyle' => 0,
    'regionalStyle' => 0,
    'sGender' => 0,
    'paramsArr' => 0,
    'gender' => 0,
    'sAgeLayer' => 0,
    'ageLayer' => 0,
    'sManner' => 0,
    'sVisualAngle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5eeb05a3c749b1_50769407',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5eeb05a3c749b1_50769407')) {
function content_5eeb05a3c749b1_50769407 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '12109901575eeb05a37a2860_10029638';
?>
<div>
	<div class="">
		<ul class="select_items paixu fl clearfix">
			<?php if (isset($_smarty_tpl->tpl_vars['iSeason']->value) && !empty($_smarty_tpl->tpl_vars['iSeason']->value)) {?>
			<li class="pli">
				<span class="aspan">季节<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['iSeason']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['season'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['season']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['season']->value) {
$_smarty_tpl->tpl_vars['season']->_loop = true;
$foreach_season_Sav = $_smarty_tpl->tpl_vars['season'];
?>
							<a href="<?php echo $_smarty_tpl->tpl_vars['season']->value['link'];?>
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" title="<?php echo $_smarty_tpl->tpl_vars['season']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['season']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['season'] = $foreach_season_Sav;
}
?>
						</div>
					</div>	
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['iNo']->value) && !empty($_smarty_tpl->tpl_vars['iNo']->value)) {?>
			<li class="pli">
				<span  class="aspan">期数<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['iNo']->value;
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
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['iRelationTheme']->value)) {?>
			<li class="pli trends-hot-icon">
				<span  class="aspan" style="max-width: 95px;">趋势专题<i class="nav-icon"></i><em></em></span>
				<div class="showbox category_box">
					<div class="category_div contentHolder ps-container">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['iRelationTheme']->value;
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
							<dl class="category_dl active_dl clearfix">
								<dt><a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
<i></i></a></dt>
								<dd class="<?php if ($_smarty_tpl->tpl_vars['key']->value != count($_smarty_tpl->tpl_vars['iRelationTheme']->value)-1) {?> border-bottom <?php }?>">
									<?php if (is_array($_smarty_tpl->tpl_vars['val']->value['attrs'])) {?>
									<?php
$_from = $_smarty_tpl->tpl_vars['val']->value['attrs'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['v']->_loop = false;
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$foreach_v_Sav = $_smarty_tpl->tpl_vars['v'];
?>
									<a class="clickme <?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value['link'];
$_tmp1=ob_get_clean();
if (empty($_tmp1)) {?> default_cur <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</a>
									<?php
$_smarty_tpl->tpl_vars['v'] = $foreach_v_Sav;
}
?>
									<?php }?>
								</dd>
							</dl>
							<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['categorys']->value)) {?>
			<li class="pli">
				<span class="aspan">单品<i class="nav-icon"></i></span>
				<div class="showbox category_box">
					<div class="category_div contentHolder ps-container">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['categorys']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['category'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['category']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
$foreach_category_Sav = $_smarty_tpl->tpl_vars['category'];
?>
							<dl class="category_dl clearfix <?php if ($_smarty_tpl->tpl_vars['key']->value < count($_smarty_tpl->tpl_vars['categorys']->value)-1) {?>border-bottom<?php }?>">
								<dt><a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['category']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</a></dt>
								<?php if (isset($_smarty_tpl->tpl_vars['category']->value['subcategorys'])) {?>
								<dd>
								<?php
$_from = $_smarty_tpl->tpl_vars['category']->value['subcategorys'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['subcategory'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['subcategory']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['subcategory']->value) {
$_smarty_tpl->tpl_vars['subcategory']->_loop = true;
$foreach_subcategory_Sav = $_smarty_tpl->tpl_vars['subcategory'];
?>
								<a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['subcategory']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['subcategory']->value['name'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['subcategory']->value['name'];?>
</a>
								<?php
$_smarty_tpl->tpl_vars['subcategory'] = $foreach_subcategory_Sav;
}
?>
								</dd>
								<?php }?>
							</dl>
							<?php
$_smarty_tpl->tpl_vars['category'] = $foreach_category_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['topicTypes']->value) && !empty($_smarty_tpl->tpl_vars['topicTypes']->value)) {?>
			<li class="pli">
				<span  class="aspan">主题类型<i class="nav-icon"></i></span>
				<div class="showbox showDate short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['topicTypes']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['topicType'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['topicType']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['topicType']->value) {
$_smarty_tpl->tpl_vars['topicType']->_loop = true;
$foreach_topicType_Sav = $_smarty_tpl->tpl_vars['topicType'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['topicType']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['topicType']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['topicType']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['topicType'] = $foreach_topicType_Sav;
}
?>
						</div>
					</div> 
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['iTechnologyType']->value) && !empty($_smarty_tpl->tpl_vars['iTechnologyType']->value)) {?>
			<li class="pli">
				<span  class="aspan">工艺类型<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['iTechnologyType']->value;
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
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['sRegionalStyle']->value) && !empty($_smarty_tpl->tpl_vars['sRegionalStyle']->value)) {?>
			<li class="pli">
				<span  class="aspan">地域风格<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sRegionalStyle']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['regionalStyle'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['regionalStyle']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['regionalStyle']->value) {
$_smarty_tpl->tpl_vars['regionalStyle']->_loop = true;
$foreach_regionalStyle_Sav = $_smarty_tpl->tpl_vars['regionalStyle'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['regionalStyle']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['regionalStyle']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['regionalStyle']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['regionalStyle'] = $foreach_regionalStyle_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['sGender']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['gcen'])) {?>
			<li class="pli">
			    <span  class="aspan">男童/女童<i class="nav-icon"></i></span>
			    <div class="showbox showDate short-sum">
			        <div class="dateHeight contentHolder">
			            <div class="content">
			                <?php
$_from = $_smarty_tpl->tpl_vars['sGender']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['gender'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['gender']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['gender']->value) {
$_smarty_tpl->tpl_vars['gender']->_loop = true;
$foreach_gender_Sav = $_smarty_tpl->tpl_vars['gender'];
?>
			                <a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['gender']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['gender']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['gender']->value['name'];?>
</a>
			                <?php
$_smarty_tpl->tpl_vars['gender'] = $foreach_gender_Sav;
}
?>
			            </div>
			        </div>
			    </div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['sAgeLayer']->value) && !empty($_smarty_tpl->tpl_vars['sAgeLayer']->value)) {?>
			<li class="pli">
				<span  class="aspan">年龄层<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sAgeLayer']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['ageLayer'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['ageLayer']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['ageLayer']->value) {
$_smarty_tpl->tpl_vars['ageLayer']->_loop = true;
$foreach_ageLayer_Sav = $_smarty_tpl->tpl_vars['ageLayer'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['ageLayer']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['ageLayer']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['ageLayer']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['ageLayer'] = $foreach_ageLayer_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['sManner']->value) && !empty($_smarty_tpl->tpl_vars['sManner']->value)) {?>
			<li class="pli">
				<span  class="aspan">风格<i class="nav-icon"></i></span>
				<div class="showbox short-sum sum-style">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sManner']->value;
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
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
		</ul>
	</div>
	<div class="vis">
		<h3>视角筛选</h3>
		<ul>
			<?php
$_from = $_smarty_tpl->tpl_vars['sVisualAngle']->value;
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
			<li class="li_click <?php if (isset($_smarty_tpl->tpl_vars['paramsArr']->value['vis']) && $_smarty_tpl->tpl_vars['paramsArr']->value['vis'] == $_smarty_tpl->tpl_vars['val']->value['id'] || (!isset($_smarty_tpl->tpl_vars['paramsArr']->value['vis']) && $_smarty_tpl->tpl_vars['val']->value['id'] == 0)) {?>cur<?php }?>">
			<div class="next-btn-hover"></div>
			<a  class="click_a" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><span><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</span><i></i></a>
			</li>
			<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
		</ul>
	</div>
	<div class="other">
		<input type="hidden" name="hidd"/>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/select_items.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
	</div>
</div><?php }
}
?>