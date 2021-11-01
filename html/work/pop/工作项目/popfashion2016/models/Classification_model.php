﻿<?php

/**
 * Class Classification_Model
 * @property-read common_model $common_model
 */
class Classification_Model extends POP_Model
{
    //================================分类信息集合[start]=================================//
    public function __construct()
    {
        parent::__construct();
    }

    //向XML中追加 栏目
    public function appendColumnXml($Columns, $body, &$Dom, $sBelongSite)
    {
        $columnsXml = $Dom->createElement('columns');
        $body->appendChild($columnsXml);
        foreach ($Columns as $key => $val) {
            $picCutWay = 1; //切图方式默认为1
            //服装站
            if ($sBelongSite == 1) {
                switch ($key) {
                    case 'perform':    //秀场提炼
                        $picCutWay = 0;
                        $iWidth = 240;
                        $iHeight = 320;
                        $selects = ['genders' => "性别", 'regions' => "地区", 'seasons' => "季节", 'industrys' => "行业", 'categorys' => "单品品名"];
                        break;
                    case 'stylegraphic'://款式流行
                        $iWidth = 240;
                        $iHeight = 320;
                        //$picCutWay = 2;
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'industrys' => "行业", 'categorys' => "单品品名"];
                        break;
                    case 'marketphoto':    //商场爆款
                    case 'street':        //街拍图库
                        $iWidth = 240;
                        $iHeight = 320;
                        $selects = ['regions' => "地区", 'genders' => "性别", 'seasons' => "季节", 'industrys' => "行业", 'categorys' => "单品品名"];
                        break;
                    case 'ordermeeting'://订货会
                    case 'tideleader':    //潮流领袖
                        $iWidth = 240;
                        $iHeight = 320;
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'industrys' => "行业", 'categorys' => "单品品名"];
                        break;
                    case 'styledetail':    //款式细节
                        $iWidth = 165;
                        $iHeight = 220;
                        //$picCutWay = 2;
                        $selects = ['s_regions' => "地区（风格）", 's_genders' => "性别", 'seasons' => "季节", 'industrys' => "行业", 's_categorys' => "单品", 'positions' => "部位"];
                        break;
                    case 'shopset':    //橱窗陈列
                        $iWidth = 260;
                        $iHeight = 195;
                        $selects = ['s_genders' => "性别", 'seasons' => "季节", 'regions' => "地区"];
                        break;
                    case 'accessories':    //服饰品
                        $iWidth = 200;
                        $iHeight = 200;
                        $selects = ['s_genders' => "性别", 'seasons' => "季节", 'c_categorys' => "单品品名"];
                        break;
                }
            } else {
                $iWidth = 210;
                $iHeight = 280;
                switch ($key) {
                    case 'style_graphic':   //款式图集
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'regions_2' => "地区", 'modes' => "款型", 'materials' => "面料材质", 'ways' => "方式"];
                        break;
                    case 'brand':           //名牌跟踪
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'region_styles' => "地域风格", 'modes' => "款型", 'materials' => "面料材质", 'ways' => "方式"];
                        break;
                    case 'china_style':     //国内市场
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'region_styles' => "地域风格", 'modes' => "款型", 'materials' => "面料材质", 'ways' => "方式"];
                        break;
                    case 'market_photo':    //商场爆款
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'regions_4' => "地区", 'modes' => "款型"];
                        break;
                    case 'exhibition_tracking'://展会跟踪
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'regions_8' => "地区", 'modes' => "款型"];
                        break;
                    case 'presscon':        //发布会
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'regions_5' => "地区", 'modes' => "款型"];
                        break;
                    case 'street':          //街头时尚
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'regions_7' => "地区", 'modes' => "款型"];
                        break;
                    case 'sportstie':       //运动拉杆
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'regions_6' => "地区", 'modes' => "款型"];
                        break;
                    case 'ordermeeting':    //订货会
                        $selects = ['genders' => "性别", 'seasons' => "季节", 'modes' => "款型"];
                        break;
                }
            }

            $columnXml = $Dom->createElement('column');
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $columnXml->appendChild($id);
            $name = $Dom->createAttribute('name');
            $name->value = $val;
            $columnXml->appendChild($name);
            $picCutWayAttr = $Dom->createAttribute('picCutWay');
            $picCutWayAttr->value = $picCutWay;
            $columnXml->appendChild($picCutWayAttr);

            $picWidthAttr = $Dom->createAttribute('picWidth');
            $picWidthAttr->value = $iWidth;
            $columnXml->appendChild($picWidthAttr);

            $picHeightAttr = $Dom->createAttribute('picHeight');
            $picHeightAttr->value = $iHeight;
            $columnXml->appendChild($picHeightAttr);
            //添加所拥有选项
            $selectsXml = $Dom->createElement('selects');
            $columnXml->appendChild($selectsXml);
            foreach ($selects as $select => $val) {
                $selectXml = $Dom->createElement('select');
                $ref = $Dom->createAttribute('ref');
                $ref->value = $select;
                $name = $Dom->createAttribute('name');
                $name->value = $val;
                $selectXml->appendChild($ref);
                $selectXml->appendChild($name);
                $selectsXml->appendChild($selectXml);
            }
            $columnsXml->appendChild($columnXml);
        }
    }

    //向XML中追加性别
    public function appendGenderXml($Genders, $series, &$Dom, $special = false)
    {
        if ($special) {
            unset($Genders[3], $Genders[4]);
            $gendersXml = $Dom->createElement('s_genders');
            $series->appendChild($gendersXml);
        } else {
            unset($Genders[5]);
            $gendersXml = $Dom->createElement('genders');
            $series->appendChild($gendersXml);
        }
        //循环填入XML
        foreach ($Genders as $key => $val) {
            $genderXml = $Dom->createElement('gender');
            //创建属性值
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $name = $Dom->createAttribute('name');
            $name->value = $val;
            //属性值填入XML
            $genderXml->appendChild($id);
            $genderXml->appendChild($name);
            $gendersXml->appendChild($genderXml);
        }
    }

    //向XML中追加地区
    public function appendRegionXml($Regions, $series, &$Dom)
    {
        $regionsXml = $Dom->createElement('regions');
        $series->appendChild($regionsXml);
        foreach ($Regions as $key => $value) {
            //创建属性值
            $id = $Dom->createAttribute('id');
            $id->value = $value['iRegionId'];
            $name = $Dom->createAttribute('name');
            $name->value = $value['sCnRegionName'];
            //创建节点和填入属性
            $regionXml = $Dom->createElement('region');
            $regionXml->appendChild($id);
            $regionXml->appendChild($name);
            //节点加入Dom中
            $regionsXml->appendChild($regionXml);
        }
    }

    //款式详情特殊地区
    public function appendSpecialRegionXml($specialRegions, $series, &$Dom)
    {
        $specialRegionsXml = $Dom->createElement('s_regions');
        $series->appendChild($specialRegionsXml);
        foreach ($specialRegions as $key => $val) {
            $specialRegionXml = $Dom->createElement('s_region');
            //创建属性值
            $id = $Dom->createAttribute('id');
            $id->value = intval($key);
            $name = $Dom->createAttribute('name');
            $name->value = $val;
            //属性值填入XML
            $specialRegionXml->appendChild($id);
            $specialRegionXml->appendChild($name);
            $specialRegionsXml->appendChild($specialRegionXml);
        }
    }

    //向XML中追加季节
    public function appendSeasonXml($Seasons, $series, &$Dom)
    {
        $seasonsXml = $Dom->createElement('seasons');
        $series->appendChild($seasonsXml);
        foreach ($Seasons as $key => $val) {
            $seasonXml = $Dom->createElement('season');
            //创建属性值
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $name = $Dom->createAttribute('name');
            $name->value = $val;
            //属性值填入XML
            $seasonXml->appendChild($id);
            $seasonXml->appendChild($name);
            $seasonsXml->appendChild($seasonXml);
        }
    }

    //向XML中追加行业
    public function appendIndustryXml($Industrys, $series, &$Dom)
    {
        $industrysXml = $Dom->createElement('industrys');
        $series->appendChild($industrysXml);
        foreach ($Industrys as $key => $val) {
            $industryXml = $Dom->createElement('industry');
            //创建属性值
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $name = $Dom->createAttribute('name');
            $name->value = $val;
            //属性值填入XML
            $industryXml->appendChild($id);
            $industryXml->appendChild($name);
            $industrysXml->appendChild($industryXml);
        }
    }

    //向XML中追加单品、品名(可定制)
    public function appendCustomizedCategoryXml($Categorys, $series, &$Dom, $Customized, $filed = 'c_categorys')
    {
        if (!is_array($Customized) || empty($Customized)) {
            return false;
        }

        $categorysXml = $Dom->createElement($filed);
        $series->appendChild($categorysXml);
        foreach ($Categorys as $key => $val) {
            if (!in_array($key, $Customized)) {
                continue;
            }

            $categoryXml = $Dom->createElement('category');
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $name = $Dom->createAttribute('name');
            $name->value = $val['sName'];
            $categoryXml->appendChild($id);
            $categoryXml->appendChild($name);
            $categorysXml->appendChild($categoryXml);
            if (is_array($val['Pinming'])) {
                foreach ($val['Pinming'] as $k => $v) {
                    $subCategoryXml = $Dom->createElement('subCategory');
                    $id = $Dom->createAttribute('id');
                    $id->value = $k;
                    $name = $Dom->createAttribute('name');
                    $name->value = $v;
                    $subCategoryXml->appendChild($id);
                    $subCategoryXml->appendChild($name);
                    $categoryXml->appendChild($subCategoryXml);
                }
            }
        }
    }

    //向XML中追加单品、品名
    public function appendCategoryXml($Categorys, $series, &$Dom, $isOnlyCat = false)
    {
        if ($isOnlyCat) {
            $filed = 's_categorys';
        } else {
            $filed = 'categorys';
        }
        $categorysXml = $Dom->createElement($filed);
        $series->appendChild($categorysXml);
        foreach ($Categorys as $key => $val) {
            $categoryXml = $Dom->createElement('category');
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $name = $Dom->createAttribute('name');
            $name->value = $val['sName'];
            $categoryXml->appendChild($id);
            $categoryXml->appendChild($name);
            $categorysXml->appendChild($categoryXml);
            if (is_array($val['Pinming']) && !$isOnlyCat) {
                foreach ($val['Pinming'] as $k => $v) {
                    $subCategoryXml = $Dom->createElement('subCategory');
                    $id = $Dom->createAttribute('id');
                    $id->value = $k;
                    $name = $Dom->createAttribute('name');
                    $name->value = $v;
                    $subCategoryXml->appendChild($id);
                    $subCategoryXml->appendChild($name);
                    $categoryXml->appendChild($subCategoryXml);
                }
            }
        }
    }

    //向XML中追加部位
    public function appendPositionXml($Positions, $series, &$Dom)
    {
        $positionsXml = $Dom->createElement('positions');
        $series->appendChild($positionsXml);
        foreach ($Positions as $key => $val) {
            $positionXml = $Dom->createElement('position');
            //创建属性值
            $id = $Dom->createAttribute('id');
            $id->value = $key;
            $name = $Dom->createAttribute('name');
            $name->value = $val;
            //属性值填入XML
            $positionXml->appendChild($id);
            $positionXml->appendChild($name);
            $positionsXml->appendChild($positionXml);
        }
    }

    //取地区数据
    public function getDataRegion()
    {
        $sql = "SELECT iRegionId,sCnRegionName FROM `t_dict_region` WHERE iClassificationUse=1 AND iStatus=0 ORDER BY iClassificationSort DESC";
        $rows = $this->query($sql);
        return $rows;
    }

    //获取来源
    public function getSources()
    {
        $pids = '3486,3487,3488';//3486=>批发市场、3487=>百货商场、3488=>展会
        $sql = "SELECT id,cat_name,parent_id FROM `fashion_category` WHERE `parent_id` IN (" . $pids . ") AND `cat_class` = 'f010' AND `cat_kind` =2";
        $rows = $this->query($sql);
        foreach ($rows as $key => $val) {
            $_rows[$val['parent_id']][$val['id']] = $val['cat_name'];
        }
        return $_rows;
    }

//===========================================================================箱包==============================================================//
    public function getBagsColumns()
    {
        $return = [];
        $sql = "SELECT * FROM `popbags`.`t_dict_column` WHERE iClassification=1 AND iStatus=1";
        $query = $this->db()->query($sql);
        foreach ($query->result() as $row) {
            $return[$row->sPathName] = $row->sName;
        }
        return $return;
    }

    public function getBagsData()
    {
        $return = [];
        $sql = "SELECT * FROM `popbags`.`t_dict_attribute` WHERE iStatus=1 AND iClassification=1";
        $query = $this->db()->query($sql);
        foreach ($query->result() as $row) {
            $data[$row->iAttributePid][] = ['id' => $row->iAttributeId, 'name' => $row->sName];
        }
        foreach ($query->result() as $row) {
            $fields = $field = "";
            $childs = [];
            switch ($row->iAttributePid) {
                case '1'://性别
                    $fields = "genders";
                    $field = "gender";
                    break;
                case '2'://款型
                    $fields = "modes";
                    $field = "mode";
                    if ($data[$row->iAttributeId]) {
                        foreach ($data[$row->iAttributeId] as $val) {
                            $childs[] = ['field' => 'sub_mode', 'id' => $val['id'], 'name' => $val['name']];
                        }
                    }
                    break;
                case '3'://面料材质
                    $fields = "materials";
                    $field = "material";
                    if ($data[$row->iAttributeId]) {
                        foreach ($data[$row->iAttributeId] as $val) {
                            $childs[] = ['field' => 'sub_material', 'id' => $val['id'], 'name' => $val['name']];
                        }
                    }
                    break;
                case '5'://方式
                    $fields = "ways";
                    $field = "way";
                    break;
                case '10'://地域风格
                    $fields = "region_styles";
                    $field = "region_style";
                    break;
                case '170'://季节
                    $fields = "seasons";
                    $field = "season";
                    break;
                case '12'://地区
                    $fields = "regions";
                    $field = "region";
                    break;
            }
            if ($row->iAttributePid == 12) {    //地区特殊
                $_arr = explode(',', $row->sColumn);
                foreach ($_arr as $columnId) {
                    $tmp = "{$fields}_{$columnId}";
                    $return[$tmp][] = ['field' => $field, 'id' => $row->iAttributeId, 'name' => $row->sName];
                }
            } else {
                if (!empty($field)) {
                    if (!empty($childs)) {
                        $return[$fields][] = ['field' => $field, 'id' => $row->iAttributeId, 'name' => $row->sName, $fields => $childs];
                    } else {
                        $return[$fields][] = ['field' => $field, 'id' => $row->iAttributeId, 'name' => $row->sName];
                    }
                }
            }
        }
        return $return;
    }

    /*------------------------------------------------------------------------------------------------------------------------------------------
    *  @todo 给箱包XML追加各种属性（ps：数据要符合 data[fields]=[[ 'field'=>xxx,'id'=>xxx,'name'=>xxx,'子类型'=>[['field'=>xxx,'id'=>xxx,'name'=>xxx],[]] ],['field'=>xxx,'id'=>xxx,'name'=>xxx]] ）;
    *-------------------------------------------------------------------------------------------------------------------------------------------*/
    public function appendBagsXml($data, $series, &$Dom)
    {
        foreach ($data as $key => $values) {
            $firstXml = $Dom->createElement($key);
            $series->appendChild($firstXml);
            foreach ($values as $val) {
                $secondXml = $Dom->createElement($val['field']);
                //创建属性值
                $id = $Dom->createAttribute('id');
                $id->value = $val['id'];
                $name = $Dom->createAttribute('name');
                $name->value = $val['name'];
                //属性值填入XML
                $secondXml->appendChild($id);
                $secondXml->appendChild($name);
                $firstXml->appendChild($secondXml);

                //含有子分类时(最后一个参数为数组时则认为是多层数据)
                if (is_array(end($val))) {
                    $end = array_slice($val, -1, 1);
                    $_key = key($end);
                    //$thirdXml= $Dom->createElement($key);
                    // $secondXml->appendChild($thirdXml);
                    foreach ($end[$_key] as $_val) {
                        $fourthXml = $Dom->createElement($_val['field']);
                        //创建属性值
                        $id = $Dom->createAttribute('id');
                        $id->value = $_val['id'];
                        $name = $Dom->createAttribute('name');
                        $name->value = $_val['name'];
                        //属性值填入XML
                        $fourthXml->appendChild($id);
                        $fourthXml->appendChild($name);
                        $secondXml->appendChild($fourthXml);
                    }
                }
            }
        }
    }

    public function changeIdToPath($_path)
    {
        $arr = explode(',', $_path);
        $attrs = $this->getBagAttrs();
        foreach ($arr as $val) {
            if ($val) {
                $_arr[] = $attrs[$val]['iAttributeId'];
            }
        }
        return implode('/', $_arr);
    }

    //获取箱包属性 （返回以 id 为键 其他为值的形式）
    private function getBagAttrs()
    {
        if (empty($this->bagAttrs)) {
            $return = [];
            $sql = "SELECT * FROM `popbags`.`t_dict_attribute` WHERE iStatus=1 AND iClassification=1";
            $query = $this->db()->query($sql);
            foreach ($query->result() as $row) {

                $return[$row->iAttributeId] = ['iAttributeId' => $row->iAttributeId, 'sName' => $row->sName, 'sPathName' => $row->sPathName];
            }
            $this->bagAttrs = $return;
        }
        return $this->bagAttrs;
    }
}