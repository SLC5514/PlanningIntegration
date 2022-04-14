<?php /* Smarty version 3.1.27, created on 2020-06-15 14:54:34
         compiled from "/data/htdocs/popfashion2016/views/lists/references_select_items.html" */ ?>
<?php
/*%%SmartyHeaderCode:20325118465ee71b2a2962d4_04558190%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2222426395207e360636bf1d2176148b1ad91869' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/lists/references_select_items.html',
      1 => 1588929192,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20325118465ee71b2a2962d4_04558190',
  'variables' => 
  array (
    'seasons' => 0,
    'key' => 0,
    'season' => 0,
    'regions' => 0,
    'paramsArr' => 0,
    'sCategory' => 0,
    'category' => 0,
    'patternUses' => 0,
    'patternUse' => 0,
    'patternFormats' => 0,
    'patternFormat' => 0,
    'sExhibitionName' => 0,
    'val' => 0,
    'v' => 0,
    'sMaterial' => 0,
    'patternTechnologys' => 0,
    'patternTechnology' => 0,
    'processSources' => 0,
    'processSource' => 0,
    'sFabricCraft' => 0,
    'patternContents' => 0,
    'columnId' => 0,
    'patternContent' => 0,
    'sAssortColor' => 0,
    'sStylePart' => 0,
    'stylePart' => 0,
    'sGender' => 0,
    'gender' => 0,
    'brands' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ee71b2a5ef230_25984815',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ee71b2a5ef230_25984815')) {
function content_5ee71b2a5ef230_25984815 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '20325118465ee71b2a2962d4_04558190';
?>
<div>
	<div class="">
		<ul class="select_items paixu fl clearfix">
			<?php if (isset($_smarty_tpl->tpl_vars['seasons']->value) && !empty($_smarty_tpl->tpl_vars['seasons']->value)) {?>
			<li class="pli">
				<span  class="aspan">季节<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['seasons']->value;
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
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['season']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['season']->value['name'];?>
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
			<?php if (isset($_smarty_tpl->tpl_vars['regions']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['reg'])) {?>
			<li class="pli">
				<span class="aspan">地区<i class="nav-icon"></i></span>
				<div class="showbox region_box category_box">
                    <div class="search-area-box clearfix">
                        <input class="fl searInput" type="text" value="请输入国家或城市的关键字">
                        <input class="fr button neiyeList" type="button">
                    </div>
					<div class="allcc clearfix">
						<div class="area_qh"><span>地区：</span><a href="javascript:void(0);" class="on"><span>按区域</span><i></i></a><a href="javascript:void(0);"><span>按大洲</span><i></i></a></div>
					</div>
					<div class="mingc">
						<div class="fl">
							<span>大洲名称：</span>
							<a href="javascript:void(0);">美洲</a>
                        </div>
                        <div class="allred fl"><a href="javascript:void(0);" >全部亚洲国家>></a></div>						
					</div>					
					 <div class="category_div contentHolder ps-container">
						<div class="content">
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['sCategory']->value) && !empty($_smarty_tpl->tpl_vars['sCategory']->value)) {?>
			<li class="pli">
				<span  class="aspan">单品<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sCategory']->value;
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
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['category']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['category'] = $foreach_category_Sav;
}
?>
						</div>
					</div> 
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['patternUses']->value) && !empty($_smarty_tpl->tpl_vars['patternUses']->value)) {?>
			<li class="pli">
				<span  class="aspan">局部/满身<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['patternUses']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['patternUse'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['patternUse']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['patternUse']->value) {
$_smarty_tpl->tpl_vars['patternUse']->_loop = true;
$foreach_patternUse_Sav = $_smarty_tpl->tpl_vars['patternUse'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['patternUse']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['patternUse']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['patternUse']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['patternUse'] = $foreach_patternUse_Sav;
}
?>
						</div>
					</div>        
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['patternFormats']->value) && !empty($_smarty_tpl->tpl_vars['patternFormats']->value)) {?>
			<li class="pli">
				<span  class="aspan">矢量/位图<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['patternFormats']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['patternFormat'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['patternFormat']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['patternFormat']->value) {
$_smarty_tpl->tpl_vars['patternFormat']->_loop = true;
$foreach_patternFormat_Sav = $_smarty_tpl->tpl_vars['patternFormat'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['patternFormat']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['patternFormat']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['patternFormat']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['patternFormat'] = $foreach_patternFormat_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['sExhibitionName']->value)) {?>
			<li class="pli">
				<span  class="aspan">面料展<i class="nav-icon"></i></span>
				<div class="showbox showdiv1">
					<div class="zhcontent contentHolder">
						<div class="content">
							<!-- 内衣/家居服/泳装展加class="long_dl" -->
							<?php
$_from = $_smarty_tpl->tpl_vars['sExhibitionName']->value;
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
							<dl class="clearfix <?php if ($_smarty_tpl->tpl_vars['val']->value['name'] == '内衣家居服泳装') {?> long_dl <?php }?>">
								<dt><a class="default_cur" href="javascript:void(0);"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
<i></i></a></dt>
								<dd class="<?php if ($_smarty_tpl->tpl_vars['key']->value != count($_smarty_tpl->tpl_vars['sExhibitionName']->value)-1) {?> border-bottom <?php }?>">
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
									<a class="ex_a" href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
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
			<?php if (isset($_smarty_tpl->tpl_vars['sMaterial']->value) && !empty($_smarty_tpl->tpl_vars['sMaterial']->value)) {?>
			<li class="pli <?php if (empty($_smarty_tpl->tpl_vars['sMaterial']->value)) {?> no_click <?php }?>">
				<span  class="aspan">面料材质<i class="nav-icon"></i></span>
				<div class="showbox short-sum img_content">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sMaterial']->value;
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
							<a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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

			<?php if (isset($_smarty_tpl->tpl_vars['patternTechnologys']->value) && !empty($_smarty_tpl->tpl_vars['patternTechnologys']->value)) {?>
			<li class="pli">
				<span  class="aspan">图案工艺<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['patternTechnologys']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['patternTechnology'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['patternTechnology']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['patternTechnology']->value) {
$_smarty_tpl->tpl_vars['patternTechnology']->_loop = true;
$foreach_patternTechnology_Sav = $_smarty_tpl->tpl_vars['patternTechnology'];
?>
							<?php if ($_smarty_tpl->tpl_vars['patternTechnology']->value['name'] != '毛衫图案') {?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['patternTechnology']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['patternTechnology']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['patternTechnology']->value['name'];?>
</a>
							<?php }?>
							<?php
$_smarty_tpl->tpl_vars['patternTechnology'] = $foreach_patternTechnology_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>

			<?php if (isset($_smarty_tpl->tpl_vars['processSources']->value) && !empty($_smarty_tpl->tpl_vars['processSources']->value)) {?>
			<li class="pli">
				<span  class="aspan">工艺来源<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['processSources']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['processSource'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['processSource']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['processSource']->value) {
$_smarty_tpl->tpl_vars['processSource']->_loop = true;
$foreach_processSource_Sav = $_smarty_tpl->tpl_vars['processSource'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['processSource']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['processSource']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['processSource']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['processSource'] = $foreach_processSource_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>

			<?php if (isset($_smarty_tpl->tpl_vars['sFabricCraft']->value) && !empty($_smarty_tpl->tpl_vars['sFabricCraft']->value)) {?>
			<li class="pli <?php if (empty($_smarty_tpl->tpl_vars['sFabricCraft']->value)) {?> no_click <?php }?>">
				<span  class="aspan">面料工艺<i class="nav-icon"></i></span>
				<div class="showbox short-sum img_content">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sFabricCraft']->value;
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
							<a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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

			<?php if (isset($_smarty_tpl->tpl_vars['patternContents']->value) && !empty($_smarty_tpl->tpl_vars['patternContents']->value)) {?>
			<li class="pli <?php if (empty($_smarty_tpl->tpl_vars['patternContents']->value)) {?> no_click <?php }?>">
				<span  class="aspan">图案内容<i class="nav-icon"></i></span>
				
				<!--图案素材的图案内容-->
				<?php if ($_smarty_tpl->tpl_vars['columnId']->value == 117 || $_smarty_tpl->tpl_vars['columnId']->value == 121 || $_smarty_tpl->tpl_vars['columnId']->value == 120) {?>
				<div class="showbox short-sum img_content">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['patternContents']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['patternContent'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['patternContent']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['patternContent']->value) {
$_smarty_tpl->tpl_vars['patternContent']->_loop = true;
$foreach_patternContent_Sav = $_smarty_tpl->tpl_vars['patternContent'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['patternContent']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['patternContent']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['patternContent']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['patternContent'] = $foreach_patternContent_Sav;
}
?>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<div class="showbox category_box category-img">
					<div class="category_div contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['patternContents']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['patternContent'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['patternContent']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['patternContent']->value) {
$_smarty_tpl->tpl_vars['patternContent']->_loop = true;
$foreach_patternContent_Sav = $_smarty_tpl->tpl_vars['patternContent'];
?>
							<dl class="category_dl clearfix border-bottom">
								<dt><a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['patternContent']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['patternContent']->value['name'];?>
</a></dt>
								<dd>
									<?php
$_from = $_smarty_tpl->tpl_vars['patternContent']->value['attrs'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
									<a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a>
									<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
								</dd>
							</dl>
							<?php
$_smarty_tpl->tpl_vars['patternContent'] = $foreach_patternContent_Sav;
}
?>
						</div>
					</div>
				</div>				
				<?php }?>
			</li>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['sAssortColor']->value)) {?>
			<li class="pli">
				<span class="aspan">色系<i class="nav-icon"></i></span>
				<div class="showbox short-sum sum_color">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sAssortColor']->value;
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
							<a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
"  onclick="return oCommon.clickBeforeFlicker(this);">
								<div class="box_color" style="background-color:<?php echo $_smarty_tpl->tpl_vars['val']->value['sAlias'];?>
"><span><?php echo $_smarty_tpl->tpl_vars['val']->value['sName'];?>
</span></div>
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
			<?php if (isset($_smarty_tpl->tpl_vars['sStylePart']->value) && !empty($_smarty_tpl->tpl_vars['sStylePart']->value)) {?>
			<li class="pli">
				<span  class="aspan">款式部位<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sStylePart']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['stylePart'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['stylePart']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['stylePart']->value) {
$_smarty_tpl->tpl_vars['stylePart']->_loop = true;
$foreach_stylePart_Sav = $_smarty_tpl->tpl_vars['stylePart'];
?>
							<a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['stylePart']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['stylePart']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['stylePart']->value['name'];?>
</a>
							<?php
$_smarty_tpl->tpl_vars['stylePart'] = $foreach_stylePart_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>

            <?php if (!empty($_smarty_tpl->tpl_vars['sGender']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['gcen']) && !in_array($_smarty_tpl->tpl_vars['columnId']->value,array(82,120))) {?>
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

			<?php if (isset($_smarty_tpl->tpl_vars['brands']->value) && !empty($_smarty_tpl->tpl_vars['brands']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['bra'])) {?>

			<li class="pli">
				<span class="aspan">品牌<i class="nav-icon"></i></span>
				<div class="category_box showPro showbox">
					<div class="inputBox">
						<input type="text" class="searInputbox fl" value="搜索热门品牌名" name="HOT" style="color: rgb(148, 147, 147);">
						<span class="fr neiyeList"></span>
					</div>
					<div class="brands_ca">
						<div class="items">
							<div class="letter" name="STAR"><i></i></div>
						</div>
						<!--品牌开始-->
						<?php
$_from = $_smarty_tpl->tpl_vars['brands']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
						<div class="items">
							<div class="letter" name="<?php echo $_smarty_tpl->tpl_vars['val']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</div>
						</div>
						<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						<div class="brandslist">
							<div class="brand_result contentHolder">
								<div class="content">
									<ul class="clearfix" id="popBrand_<?php echo $_smarty_tpl->tpl_vars['val']->value;?>
">
									</ul>
								</div>
							</div>
						</div>
                        <!--品牌结束-->
					</div>
					
				</div>
			</li>
			<?php }?>
		</ul>
	</div>
	<div class="other">
		<input type="hidden" name="hidd" />
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/select_items.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
	</div>
</div>
<?php }
}
?>