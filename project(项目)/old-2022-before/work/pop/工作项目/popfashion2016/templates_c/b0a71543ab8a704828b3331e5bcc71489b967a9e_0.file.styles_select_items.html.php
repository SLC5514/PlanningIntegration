<?php /* Smarty version 3.1.27, created on 2020-05-29 09:38:47
         compiled from "/data/htdocs/popfashion2016/views/lists/styles_select_items.html" */ ?>
<?php
/*%%SmartyHeaderCode:8641927435ed067a7128fa1_26952502%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b0a71543ab8a704828b3331e5bcc71489b967a9e' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/lists/styles_select_items.html',
      1 => 1589427809,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8641927435ed067a7128fa1_26952502',
  'variables' => 
  array (
    'HotBrandIdsStr' => 0,
    'sGender' => 0,
    'paramsArr' => 0,
    'key' => 0,
    'gender' => 0,
    'iSeason' => 0,
    'val' => 0,
    'iStarInsData' => 0,
    'columnId' => 0,
    'region' => 0,
    'sExhibitionName' => 0,
    'v' => 0,
    'sMarketType' => 0,
    'sMarketHotPosition' => 0,
    'star' => 0,
    'brands' => 0,
    'categorys' => 0,
    'category' => 0,
    'subcategory' => 0,
    'sAssortColor' => 0,
    'sAgeLayer' => 0,
    'sManner' => 0,
    'sWearing' => 0,
    'sRegionalStyle' => 0,
    'sCharacterStyle' => 0,
    'sFashionWeek' => 0,
    'sStreetBeatType' => 0,
    'iRegion' => 0,
    'labels' => 0,
    'option' => 0,
    'sShape' => 0,
    'sSpecifics' => 0,
    'sTechnologys' => 0,
    'sPattern' => 0,
    'sFabric' => 0,
    'sAccessory' => 0,
    'bpos' => 0,
    'sStarLabel' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ed067a797c748_30768805',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ed067a797c748_30768805')) {
function content_5ed067a797c748_30768805 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '8641927435ed067a7128fa1_26952502';
?>
<div>
	<div>
        <ul class="select_items paixu fl clearfix">
            <div id="HotBrandIds" style="display:none"><?php echo $_smarty_tpl->tpl_vars['HotBrandIdsStr']->value;?>
</div>
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
			<?php if (isset($_smarty_tpl->tpl_vars['iSeason']->value) && !empty($_smarty_tpl->tpl_vars['iSeason']->value)) {?>
			<li class="pli">
				<span class="aspan">季节<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
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
							<a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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

            <!-- 57 明星/ins（潮流领袖）-->
			<?php if (isset($_smarty_tpl->tpl_vars['iStarInsData']->value) && !empty($_smarty_tpl->tpl_vars['iStarInsData']->value) && $_smarty_tpl->tpl_vars['columnId']->value == 57) {?>
			<li class="pli new-pli-labels">
				<span class="aspan">明星/ins<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['iStarInsData']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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

			<?php if ($_smarty_tpl->tpl_vars['region']->value && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['reg'])) {?>
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
			<?php if (!empty($_smarty_tpl->tpl_vars['sExhibitionName']->value)) {?>
			<li class="pli">
				<span  class="aspan">展会名称<i class="nav-icon"></i></span>
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
								<dt><a href="javascript:void(0);" class="default_cur" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
            <?php if (!empty($_smarty_tpl->tpl_vars['sMarketType']->value)) {?>
			<li class="pli">
				<span class="aspan">市场类型<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					 <div class="dateHeight contentHolder ps-container">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sMarketType']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
			<?php if (!empty($_smarty_tpl->tpl_vars['sMarketHotPosition']->value)) {?>
			<li class="pli">
				<span class="aspan">品牌定位<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sMarketHotPosition']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
			<?php if (isset($_smarty_tpl->tpl_vars['star']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['sta'])) {?>
			<li class="pli">
				<span class="aspan">人名<i class="nav-icon"></i></span>
				<div class="category_box showPro showbox">
					<div class="label_ca" style="min-width:670px;">
						<?php
$_from = $_smarty_tpl->tpl_vars['star']->value;
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
						<div class="labellist" style="margin-top:10px;">
							<div class="inputBox"><input type="text" class="searInputbox fl" value="搜索人名" name="A" style="color: rgb(148, 147, 147);"><span class="fr neiyeList"></span>
							</div>
							<div class="brand_result contentHolder js-page-select">
								<div class="content">
									<ul class="clearfix" id="popBrand_0-9"></ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['brands']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['bra']) && $_smarty_tpl->tpl_vars['columnId']->value != "56" && $_smarty_tpl->tpl_vars['columnId']->value != "57") {?>
			<li class="pli">
				<span class="aspan">品牌<i class="nav-icon"></i></span>
				<div class="category_box showPro showbox">
                    <div class="inputBox"><input type="text" class="searInputbox fl" value="搜索热门品牌名" name="HOT" style="color: rgb(148, 147, 147);"><span class="fr neiyeList"></span>
                    </div>
					<div class="brands_ca">
						<div class="items">
							<div class="letter" name="STAR"><i></i></div>
						</div>
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
									<ul class="clearfix" id="popBrand_0-9"></ul>
								</div>
							</div>
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
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
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
" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['subcategory']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['subcategory']->value['name'];?>
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
            <?php if (!empty($_smarty_tpl->tpl_vars['sAssortColor']->value)) {?>
            <li class="pli">
                <span class="aspan">色系<i class="nav-icon"></i></span>
                <div class="showbox short-sum sum_color">
                    <div class="dateHeight contentHolder">
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
			<?php if (!empty($_smarty_tpl->tpl_vars['sAgeLayer']->value)) {?>
			<li class="pli">
				<span class="aspan">年龄层<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sAgeLayer']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
            <?php if ($_smarty_tpl->tpl_vars['sManner']->value) {?>
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
			<?php if (!empty($_smarty_tpl->tpl_vars['sWearing']->value)) {?>
			<li class="pli">
				<span class="aspan">穿着<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sWearing']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
			<?php if (!empty($_smarty_tpl->tpl_vars['sRegionalStyle']->value)) {?>
			<li class="pli">
				<span class="aspan">地域风格<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sRegionalStyle']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
			<?php if (!empty($_smarty_tpl->tpl_vars['sCharacterStyle']->value)) {?>
			<li class="pli">
				<span class="aspan">性格风格<i class="nav-icon"></i></span>
				<div class="showbox short-sum">
					<div class="dateHeight contentHolder ">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sCharacterStyle']->value;
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
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" onclick="return oCommon.clickBeforeFlicker(this);" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
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
			<!-- 暂时隐藏
			<?php if (!empty($_smarty_tpl->tpl_vars['sFashionWeek']->value)) {?>
			<li class="pli">
				<span class="aspan">时装周专题<em></em></span>
				<div class="showbox category_box">
					 <div class="category_div contentHolder ps-container">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sFashionWeek']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
							<dl class="category_dl clearfix">
								<dt><a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a></dt>
								<?php if (isset($_smarty_tpl->tpl_vars['val']->value['attrs'])) {?>
								<dd class="<?php if ($_smarty_tpl->tpl_vars['key']->value != count($_smarty_tpl->tpl_vars['sFashionWeek']->value)-1) {?> border-bottom <?php }?>">
								<?php
$_from = $_smarty_tpl->tpl_vars['val']->value['attrs'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$foreach_v_Sav = $_smarty_tpl->tpl_vars['v'];
?>
								<a class="clickme <?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value['link'];
$_tmp1=ob_get_clean();
if (empty($_tmp1)) {?> default_cur <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</a>
								<?php
$_smarty_tpl->tpl_vars['v'] = $foreach_v_Sav;
}
?>
								</dd>
								<?php }?>
							</dl>
							<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?>-->
			<!-- 暂时隐藏
			<?php if (!empty($_smarty_tpl->tpl_vars['sStreetBeatType']->value)) {?>
			<li class="pli">
				<span  class="aspan">街拍类型<em></em></span>
				<div class="showbox sum">
					<div class="dateHeight contentHolder">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['sStreetBeatType']->value;
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
							<a class="clickme  <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
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
			<?php }?>-->
			<!-- <?php if (!empty($_smarty_tpl->tpl_vars['iRegion']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['reg'])) {?>
			<li class="pli">
				<span class="aspan">城市<em></em></span>
				<div class="showbox category_box">
					 <div class="category_div contentHolder ps-container">
						<div class="content">
							<?php
$_from = $_smarty_tpl->tpl_vars['iRegion']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
							<dl class="category_dl clearfix">
								<dt><a class="clickme" href="<?php echo $_smarty_tpl->tpl_vars['val']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a></dt>
								<?php if (isset($_smarty_tpl->tpl_vars['val']->value['attrs'])) {?>
								<dd class="<?php if ($_smarty_tpl->tpl_vars['key']->value != count($_smarty_tpl->tpl_vars['iRegion']->value)-1) {?> border-bottom <?php }?>">
								<?php
$_from = $_smarty_tpl->tpl_vars['val']->value['attrs'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$foreach_v_Sav = $_smarty_tpl->tpl_vars['v'];
?>
								<a class="clickme <?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value['link'];
$_tmp2=ob_get_clean();
if (empty($_tmp2)) {?> default_cur <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</a>
								<?php
$_smarty_tpl->tpl_vars['v'] = $foreach_v_Sav;
}
?>
								</dd>
								<?php }?>
							</dl>
							<?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
						</div>
					</div>
				</div>
			</li>
			<?php }?> -->
		    <!--<?php if (!empty($_smarty_tpl->tpl_vars['labels']->value) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['hot'])) {?>
		    <li class="pli">
		        <span  class="aspan">热门元素<em></em></span>
		        <div class="showbox showDate sum">
		            <div class="dateHeight contentHolder">
		                <div class="content">
		                    <?php
$_from = $_smarty_tpl->tpl_vars['labels']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['option'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['option']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->_loop = true;
$foreach_option_Sav = $_smarty_tpl->tpl_vars['option'];
?>
		                    <a class="clickme <?php if ($_smarty_tpl->tpl_vars['key']->value != 0) {?>borderTop<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['option']->value['link'];?>
" onclick="return oCommon.clickBeforeFlicker(this);"><?php echo $_smarty_tpl->tpl_vars['option']->value['name'];?>
</a>
		                    <?php
$_smarty_tpl->tpl_vars['option'] = $foreach_option_Sav;
}
?>
		                </div>
		            </div>
		        </div>
		    </li>
		    <?php }?>-->
		    <!-- 暂时隐藏 -->
            <?php if ((!isset($_smarty_tpl->tpl_vars['paramsArr']->value['shap']) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['spe']) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['tech']) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['pat']) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['fab']) && !isset($_smarty_tpl->tpl_vars['paramsArr']->value['acc'])) && ($_smarty_tpl->tpl_vars['sShape']->value || $_smarty_tpl->tpl_vars['sSpecifics']->value || $_smarty_tpl->tpl_vars['sTechnologys']->value || $_smarty_tpl->tpl_vars['sPattern']->value || $_smarty_tpl->tpl_vars['sFabric']->value || $_smarty_tpl->tpl_vars['sAccessory']->value)) {?>
            <li class="pli">
                <span  class="aspan">元素<i class="nav-icon"></i></span>
                <div class="showbox showdiv1">
                    <div class="zhcontent contentHolder">
                        <div class="content">
                            <?php if ($_smarty_tpl->tpl_vars['sShape']->value) {?>
                            <dl class="clearfix">
                                <dt><a href="javascript:void(0);" class="default_cur">廓形<i></i></a></dt>
                                <dd class="border-bottom">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['sShape']->value;
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
                                </dd>
                            </dl>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['sSpecifics']->value) {?>
                            <dl class="clearfix">
                                <dt><a href="javascript:void(0);" class="default_cur">细节<i></i></a></dt>
                                <dd class="border-bottom">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['sSpecifics']->value;
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
                                </dd>
                            </dl>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['sTechnologys']->value) {?>
                            <dl class="clearfix">
                                <dt><a href="javascript:void(0);" class="default_cur">工艺<i></i></a></dt>
                                <dd class="border-bottom">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['sTechnologys']->value;
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
                                </dd>
                            </dl>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['sPattern']->value) {?>
                            <dl class="clearfix">
                                <dt><a href="javascript:void(0);" class="default_cur">图案<i></i></a></dt>
                                <dd class="border-bottom">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['sPattern']->value;
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
                                </dd>
                            </dl>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['sFabric']->value) {?>
                            <dl class="clearfix">
                                <dt><a href="javascript:void(0);" class="default_cur">面料<i></i></a></dt>
                                <dd class="border-bottom">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['sFabric']->value;
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
                                </dd>
                            </dl>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['sAccessory']->value) {?>
                            <dl class="clearfix">
                                <dt><a href="javascript:void(0);" class="default_cur">辅料<i></i></a></dt>
                                <dd class="border-bottom">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['sAccessory']->value;
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
                                </dd>
                            </dl>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </li>
            <?php }?>
		</ul>
	</div>
	<div class="other">
		<input type="hidden" name="hidd" data-bpos="<?php echo $_smarty_tpl->tpl_vars['bpos']->value;?>
" data-star='<?php echo $_smarty_tpl->tpl_vars['sStarLabel']->value;?>
' />
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