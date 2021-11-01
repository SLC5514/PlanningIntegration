<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classification extends POP_Controller
{
    const HASHKEY = 'classification';
    const MEMCACHE_PRE = 'pop_classification_'; //memcache前缀
    // 表名
    const T_CLASSIFICATION_ACCOUNT = '`pop_classification_sys`.`t_classification_account`';
    const T_CLASSIFICATION_PHOTO_DATA = '`pop_classification_sys`.`t_classification_photo_data`';
    const T_CLASSIFICATION_PHOTO_BATCH = '`pop_classification_sys`.`t_classification_photo_batch`';
    private $Dom;

    function __construct()
    {
        parent::__construct();
        $this->Dom = new DOMDocument('1.0', 'utf-8');    //实例化XML
        $cHash = trim($this->input->post('hash'));
        $this->checkHash($cHash);
        $this->load->model('Classification_model');
    }

    //验证Hash值是否正确
    private function checkHash($cHash)
    {
        //服务端自己计算的Hash
        $time = date('Y-m-d');
        $sHash = self::HASHKEY . $time;
        $sHash = MD5($sHash);
        //判断客户端与服务端Hash是否一致
        if ($sHash != $cHash) {
            $arr = array('code' => '1001', 'info' => 'hash值不正确');
            echo $this->outXml($arr);
            exit;
        }
    }

    public function columninfo()
    {
        $sBelongSite = intval($this->input->post('sBelongSite'));
        if (!in_array($sBelongSite, [1, 2])) {    //站点1为服装,2为箱包
            $arr = array('code' => '1020', 'info' => '站点有误！');
            echo $this->outXml($arr);
            exit;
        }
        if ($sBelongSite == 1) {
            $xml = $this->getFashionColumn($sBelongSite);
        } else {
            $xml = $this->getBagsColumn($sBelongSite);
        }
        echo $xml;
    }

    private function getBagsColumn($sBelongSite)
    {
        $this->load->driver('cache');
        $MemTime = '600';//缓存时间
        $MemKey = self::MEMCACHE_PRE . 'columninfo_bag_02';
        $xml = $this->cache->memcached->get($MemKey);
        if (empty($xml) || isset($_REQUEST['refresh'])) {
            $Dom = $this->Dom;
            $result = $Dom->createElement('result');
            $Dom->appendChild($result);
            //状态码
            $code = $Dom->createElement('code', 1);
            $result->appendChild($code);
            //主体
            $body = $Dom->createElement('body');
            $result->appendChild($body);
            //箱包栏目
            $Columns = $this->Classification_model->getBagsColumns();
            $this->Classification_model->appendColumnXml($Columns, $body, $Dom, $sBelongSite);
            //属性
            $series = $Dom->createElement('series');
            $body->appendChild($series);

            $data = [];
            $data = $this->Classification_model->getBagsData();
            $this->Classification_model->appendBagsXml($data, $series, $Dom);
            $xml = $Dom->saveXML();
            $this->cache->memcached->save($MemKey, $xml, $MemTime);
        }
        return $xml;

    }

    private function getFashionColumn($sBelongSite)
    {
        $this->load->driver('cache');
        $MemTime = '600';//缓存时间
        $MemKey = self::MEMCACHE_PRE . 'columninfo_fashion';
        $xml = $this->cache->memcached->get($MemKey);
        if (empty($xml) || isset($_REQUEST['refresh'])) {
            $Dom = $this->Dom;
            $result = $Dom->createElement('result');
            $Dom->appendChild($result);
            //状态码
            $code = $Dom->createElement('code', 1);
            $result->appendChild($code);
            //主体
            $body = $Dom->createElement('body');
            $result->appendChild($body);
            //栏目
            $Columns = getCommonData('common_data', 'sColumnEnToCn');
            $this->Classification_model->appendColumnXml($Columns, $body, $Dom, $sBelongSite);
            //属性
            $series = $Dom->createElement('series');
            $body->appendChild($series);
            //性别
            $Genders = GetCategory::getGender();
            $this->Classification_model->appendGenderXml($Genders, $series, $Dom, false);
            //特殊性别
            $this->Classification_model->appendGenderXml($Genders, $series, $Dom, true);
            //地区
            $Regions = $this->Classification_model->getDataRegion();
            $this->Classification_model->appendRegionXml($Regions, $series, $Dom);
            //地区(款式详情)
            $specialRegions = GetCategory::getSomeAttr(26);
            $this->Classification_model->appendSpecialRegionXml($specialRegions, $series, $Dom);
            //季节
            $Seasons = GetCategory::getSeason('', false);
            foreach ($Seasons as $val) {
                if ($val['iClassificationUse'] == 1) {
                    $tmp[$val['iAttributeId']] = $val['sName'];
                }
            }
            $Seasons = $tmp;
            $this->Classification_model->appendSeasonXml($Seasons, $series, $Dom);
            //行业
            $Industrys = GetCategory::getTrade();
            $this->Classification_model->appendIndustryXml($Industrys, $series, $Dom);
            //单品、品名
            $Categorys = GetCategory::getSingle($column = '', true, true);

            //定制（围巾、手套、帽子、领带、袜子、首饰）
            $Customized = ['11262', '11251', '11250', '11249', '11248', '11261', '11125'];

            $this->Classification_model->appendCategoryXml($Categorys, $series, $Dom);
            $this->Classification_model->appendCategoryXml($Categorys, $series, $Dom, true);
            $this->Classification_model->appendCustomizedCategoryXml($Categorys, $series, $Dom, $Customized, $filed = 'c_categorys');
            //部位
            $Positions = GetCategory::getSomeAttr(13);
            $this->Classification_model->appendPositionXml($Positions, $series, $Dom);
            //$Dom->formatOutput = true;//格式化输出
            $xml = $Dom->saveXML();
            $this->cache->memcached->save($MemKey, $xml, $MemTime);
        }
        return $xml;
    }

    /**
     *  分类软件系统登录接口
     *  返回加密后的账号id=>EncryptionDeciphering,权限，站点
     *
     */
    public function login()
    {
        $sAccountName = mysql_escape_string(trim($this->input->post('sAccountName')));
        $sPassword = trim($this->input->post('sPassword'));
        if (!$sAccountName || !$sPassword) {
            $arr = ['code' => '1002', 'info' => '账号名或密码不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $tableName = self::T_CLASSIFICATION_ACCOUNT;
        $sql = 'SELECT `iAccountID`, `sPassword`, `iAdminRight`, `sBelongSite`,`sEmployerName` FROM ' . $tableName . ' WHERE `sAccountName` = ? AND `iStatus` = ? LIMIT 1';
        $res = $this->Classification_model->query($sql, [$sAccountName, 1]);
        if ($res === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$res) {
            $arr = ['code' => '1003', 'info' => '账号不存在'];
            echo $this->outXml($arr);
            exit;
        }
        $row = $res[0];
        if ($sPassword != $row['sPassword']) {
            $arr = ['code' => '1004', 'info' => '账号名或密码错误'];
            echo $this->outXml($arr);
            exit;
        } else {
            $iAccountID = EncryptionDeciphering($row['iAccountID']);
            // 生成xml
            $Dom = $this->Dom;
            $result = $Dom->createElement('result');
            $Dom->appendChild($result);
            // 创建code节
            $code = $Dom->createElement('code', 1);
            $result->appendChild($code);
            // 创建body节点
            $body = $Dom->createElement('body');
            $result->appendChild($body);
            // 账号ID(加密)
            $iAccountIDXml = $Dom->createElement('iAccountID', $iAccountID);
            $body->appendChild($iAccountIDXml);
            // 管理员权限
            $iAdminRightXml = $Dom->createElement('iAdminRight', $row['iAdminRight']);
            $body->appendChild($iAdminRightXml);
            // 站点
            $sBelongSiteXml = $Dom->createElement('sBelongSite', $row['sBelongSite']);
            $body->appendChild($sBelongSiteXml);
            // 员工姓名
            $sEmployerNameXml = $Dom->createElement('sEmployerName');
            $body->appendChild($sEmployerNameXml);
            // 将员工姓名放进CDATA
            $cdata = $Dom->createCDATASection($row['sEmployerName']);
            $sEmployerNameXml->appendChild($cdata);
            echo $Dom->saveXML();
            exit;
        }
    }

    /**
     * 图片上传
     *
     */

    public function uploadphoto()
    {
        // 加密的账号ID
        $iAccountIDPost = trim($this->input->post('iAccountID'));
        if (!$iAccountIDPost) {
            $arr = ['code' => '1005', 'info' => '账号ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $iAccountID = intval(EncryptionDeciphering($iAccountIDPost, false));
        // 验证账号ID是否存在
        $flag = $this->isExistiAccountID($iAccountID);
        if ($flag === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$flag) {
            $arr = ['code' => '1006', 'info' => '账号ID参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 栏目名称
        $sColumnName = mysql_escape_string(trim($this->input->post('sColumnName')));
        if (!$sColumnName) {
            $arr = ['code' => '1007', 'info' => '栏目名称不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        // 图片名称(数组)
        $sPhotoName = json_decode($this->input->post('sPhotoName'));
        if (!is_array($sPhotoName)) {
            $arr = ['code' => '1010', 'info' => '图片名称参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        $sPhotoName = array_filter($sPhotoName);
        if (!$sPhotoName) {
            $arr = ['code' => '1009', 'info' => '图片名称不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $iPhotoNum = count($sPhotoName);
        // 事务开始 ，事物之间的query方法查询，加个参数false,mysql链接不关闭
        $this->Classification_model->db->initialize();
        $this->Classification_model->db->trans_start();
        $now = date('Y-m-d H:i:s');
        $table = self::T_CLASSIFICATION_PHOTO_BATCH;
        $data = [
            'iAccountID' => $iAccountID,
            'iPhotoNum' => $iPhotoNum,
            'dCreateTime' => $now
        ];
        $iBatchID = $this->Classification_model->executeSave($table, $data, false);
        if (!$iBatchID) {
            // 事务回滚
            $this->Classification_model->db->trans_rollback();
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 更新表,写入批次号
        $sBatchName = date("Ymd");
        $length = strlen($iBatchID);
        if ($length < 10) {
            $sBatchName = $sBatchName . str_pad($iBatchID, 10, "0", STR_PAD_LEFT);
        } else {
            $sBatchName .= $iBatchID;
        }
        $dataBatch = ['sBatchName' => $sBatchName];
        $condition = ['iBatchID' => $iBatchID];
        $flag2 = $this->Classification_model->executeUpdate($table, $dataBatch, $condition, false);
        if (!$flag2) {
            // 事务回滚
            $this->Classification_model->db->trans_rollback();
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 生成xml
        $Dom = $this->Dom;
        $result = $Dom->createElement('result');
        $Dom->appendChild($result);
        // 创建code节
        $code = $Dom->createElement('code', 1);
        $result->appendChild($code);
        // 创建body节点
        $body = $Dom->createElement('body');
        $result->appendChild($body);
        // 创建批次节点
        $batchXml = $Dom->createElement('batch');
        $body->appendChild($batchXml);
        // 添加批次节点ID属性
        $batchIDXml = $Dom->createAttribute('id');
        $batchIDXml->value = $iBatchID;
        // 属性值填入XML
        $batchXml->appendChild($batchIDXml);
        // 添加批次节点name属性
        $batchNameXml = $Dom->createAttribute('name');
        $batchNameXml->value = $sBatchName;
        // 属性值填入XML
        $batchXml->appendChild($batchNameXml);
        $dataPhoto = [
            'iAccountID' => $iAccountID,
            'iBatchID' => $iBatchID,
            'sColumnName' => $sColumnName,
            'dCreateTime' => $now
        ];
        $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
        foreach ($sPhotoName as $val) {
            $dataPhoto['sPhotoName'] = $val;
            $iPhotoID = $this->Classification_model->executeSave($tableName, $dataPhoto, false);
            if (!$iPhotoID) {
                // 事务回滚
                $this->Classification_model->db->trans_rollback();
                $this->Dom = new DOMDocument('1.0', 'utf-8');
                $arr = ['code' => '1011', 'info' => '服务器端错误'];
                echo $this->outXml($arr);
                exit;
            } else {
                // 创建图片节点
                $photoInfoXml = $Dom->createElement('photoInfo');
                $batchXml->appendChild($photoInfoXml);
                // 将图片名称放入CDATA
                $cdata = $Dom->createCDATASection($val);
                $photoInfoXml->appendChild($cdata);
                // 添加图片节点属性id
                $photoIDXml = $Dom->createAttribute('id');
                $photoIDXml->value = $iPhotoID;
                // 属性值填入XML
                $photoInfoXml->appendChild($photoIDXml);
            }
        }
        // 事务提交
        $this->Classification_model->db->trans_complete();
        echo $Dom->saveXML();
    }

    /**
     *  按批次获取图片列表
     */
    public function photolist()
    {
        // 批次ID
        $iBatchID = intval(trim($this->input->post('iBatchID')));
        if (!$iBatchID) {
            $arr = ['code' => '1012', 'info' => '参数批次ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        // 取出批次号
        $sBatchInfo = $this->getBatchInfoById($iBatchID);
        if ($sBatchInfo === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$sBatchInfo) {
            $arr = ['code' => '1013', 'info' => '参数批次ID错误'];
            echo $this->outXml($arr);
            exit;
        }
        $sBatchName = $sBatchInfo['sBatchName'];
        $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
        $sql = "SELECT `iPhotoID`, `sPhotoName` FROM {$tableName} WHERE `iBatchID` = {$iBatchID}  AND `iStatus` = 1";
        if (isset($_POST['iClassified'])) {
            $iClassified = intval($this->input->post('iClassified'));
            $sql = $sql . " AND iClassified = " . $iClassified;
        }
        $res = $this->Classification_model->query($sql);
        if ($res === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 生成xml
        $Dom = $this->Dom;
        $result = $Dom->createElement('result');
        $Dom->appendChild($result);
        // 创建code节点
        $code = $Dom->createElement('code', 1);
        $result->appendChild($code);
        // 创建body节点
        $body = $Dom->createElement('body');
        $result->appendChild($body);
        // 创建批次节点
        $batchXml = $Dom->createElement('batch');
        $body->appendChild($batchXml);
        // 添加批次节点ID属性
        $batchIDXml = $Dom->createAttribute('id');
        $batchIDXml->value = $iBatchID;
        // 属性值填入XML
        $batchXml->appendChild($batchIDXml);
        // 添加批次节点name属性
        $batchNameXml = $Dom->createAttribute('name');
        $batchNameXml->value = $sBatchName;
        // 属性值填入XML
        $batchXml->appendChild($batchNameXml);
        if ($res) {
            foreach ($res as $key => $val) {
                // 创建图片节点
                $photoInfoXml = $Dom->createElement('photoInfo');
                $batchXml->appendChild($photoInfoXml);
                // 将图片名称放入CDATA
                $cdata = $Dom->createCDATASection($val['sPhotoName']);
                $photoInfoXml->appendChild($cdata);
                // 添加图片节点属性id
                $photoIDXml = $Dom->createAttribute('id');
                $photoIDXml->value = $val['iPhotoID'];
                // 属性值填入XML
                $photoInfoXml->appendChild($photoIDXml);
            }
        }
        echo $Dom->saveXML();
    }

    /**
     *  为图片打标签
     */
    public function setphototag()
    {
        // 加密的账号ID
        $iAccountIDPost = trim($this->input->post('iAccountID'));
        if (!$iAccountIDPost) {
            $arr = ['code' => '1005', 'info' => '账号ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $iAccountID = intval(EncryptionDeciphering($iAccountIDPost, false));
        // 验证账号ID是否存在
        $flag = $this->isExistiAccountID($iAccountID);
        if ($flag === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$flag) {
            $arr = ['code' => '1006', 'info' => '账号ID参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 图片属性(二维数组)
        $args = json_decode($this->input->post('Args'), true);
        if (!is_array($args) || !array_filter($args)) {
            $arr = ['code' => '1010', 'info' => '图片属性参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
        $boolean = true;
        // 事务开始
        $this->Classification_model->db->initialize();
        $this->Classification_model->db->trans_start();
        $now = date('Y-m-d H:i:s');
        foreach ($args as $val) {
            if (!$val['sPhotoIDs']) {
                $arr = ['code' => '1010', 'info' => '图片ID不能为空'];
                echo $this->outXml($arr);
                exit;
            }
            $sPhotoIDArr = explode(',', $val['sPhotoIDs']);
            $sPhotoIDArr = array_map('intval', $sPhotoIDArr);
            // 图片IDs
            $sPhotoIDs = implode(',', $sPhotoIDArr);
            // 款目录名称
            $sSectionName = date("Ymd");
            $minID = min($sPhotoIDArr);
            $length = strlen($minID);
            if ($length < 10) {
                $sSectionName = $sSectionName . str_pad($minID, 10, "0", STR_PAD_LEFT);
            } else {
                $sSectionName .= $minID;
            }
            // 性别
            $iGenderID = intval($val['gender']);
            // 地区
            $iRegionID = $val['region'] ? intval($val['region']) : ($val['s_region'] ? intval($val['s_region']) : 0);
            // 季节
            $iSeasonID = intval($val['season']);

            $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
            if ($flag[0]["sBelongSite"] == 1) {  //服装站
                // 行业
                $iIndustryID = intval($val['industry']);
                // 单品
                $iCategoryID = $val['category'] ? intval($val['category']) : ($val['s_category'] ? intval($val['s_category']) : 0);
                // 品名
                $iSubCategoryID = intval($val['subCategory']);
                // 部位
                $iPartId = intval($val['position']);
                $sql = "UPDATE {$tableName} 
                SET 
                    `iGenderID` = {$iGenderID},
                    `iRegionID` = {$iRegionID},
                    `iSeasonID` = {$iSeasonID},
                    `iIndustryID` = {$iIndustryID},
                    `iCategoryID` = {$iCategoryID},
                    `iSubCategoryID` = {$iSubCategoryID},
                    `iPartId` = {$iPartId},
                    `sSectionName` = {$sSectionName},
                    `iClassified` = 1,
                    `dUpdateTime` = '{$now}'
                WHERE `iPhotoID` IN ({$sPhotoIDs}) AND iAccountID = {$iAccountID}";
            } elseif ($flag[0]["sBelongSite"] == 2) { //箱包站
                // 款型
                $sModeID = $sMaterialID = 0;
                if (!empty($val['sub_mode'])) {
                    $sModeID = intval($val['mode']) . ',' . intval($val['sub_mode']);
                } elseif (!empty($val['mode'])) {
                    $sModeID = intval($val['mode']);
                }
                // 面料材质
                if (!empty($val['sub_material'])) {
                    $sMaterialID = intval($val['material']) . ',' . intval($val['sub_material']);
                } elseif (!empty($val['material'])) {
                    $sMaterialID = intval($val['material']);
                }
                // 地域风格
                $iRegionStyleID = intval($val['region_style']);
                // 部位
                $iWayID = intval($val['way']);
                $sql = "UPDATE {$tableName} 
                SET 
                    `iGenderID` = {$iGenderID},
                    `iRegionID` = {$iRegionID},
                    `iSeasonID` = {$iSeasonID},
                    `sModeID` = '{$sModeID}',
                    `sMaterialID` = '{$sMaterialID}',
                    `iRegionStyleID` = {$iRegionStyleID},
                    `iWayID` = {$iWayID},
                    `sSectionName` = {$sSectionName},
                    `iClassified` = 1,
                    `dUpdateTime` = '{$now}'
                WHERE `iPhotoID` IN ({$sPhotoIDs}) AND iAccountID = {$iAccountID}";
            }
            $this->Classification_model->db()->query($sql);
            $num = $this->Classification_model->db()->affected_rows();
            if ($num < count($sPhotoIDArr)) {
                $boolean = false;
                break;
            }
        }
        if (!$boolean) {
            // 事务回滚
            $this->Classification_model->db->trans_rollback();
            $arr = ['code' => '1015', 'info' => '图片打标签失败，确认图片ID参数是否正确'];
        } else {
            // 事务提交
            $this->Classification_model->db->trans_complete();
            $arr = ['code' => '1'];
        }
        echo $this->outXml($arr);
        exit;
    }

    /**
     *  删除图片
     */
    public function delphoto()
    {
        // 加密的账号ID
        $iAccountIDPost = trim($this->input->post('iAccountID'));
        if (!$iAccountIDPost) {
            $arr = ['code' => '1005', 'info' => '账号ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $iAccountID = intval(EncryptionDeciphering($iAccountIDPost, false));
        // 验证账号ID是否存在
        $flag = $this->isExistiAccountID($iAccountID);
        if ($flag === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$flag) {
            $arr = ['code' => '1006', 'info' => '账号ID参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 图片ID
        $iPhotoID = intval(trim($this->input->post('iPhotoID')));
        if (empty($iPhotoID)) {
            $arr = ['code' => '1010', 'info' => '图片ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
        $sql = "UPDATE {$tableName} SET `iStatus` = '0' WHERE `iPhotoID` = {$iPhotoID} AND `iAccountID` = {$iAccountID};";
        // 事务开始 ，事物之间的query方法查询，加个参数false,mysql链接不关闭
        $this->Classification_model->db->initialize();
        $this->Classification_model->db->trans_start();
        $this->Classification_model->db->query($sql);
        $flag2 = $this->Classification_model->db()->affected_rows();
        if (!$flag2) {
            $arr = ['code' => '1016', 'info' => '图片删除失败,确认图片ID是否正确'];
            echo $this->outXml($arr);
            exit;
        } else {
            $table = self::T_CLASSIFICATION_PHOTO_BATCH;
            // 获取批次ID
            $iBatchID = $this->getiBatchIDByiPhotoID($iPhotoID, false);
            if ($iBatchID === false) {
                $arr = ['code' => '1011', 'info' => '服务端错误'];
                echo $this->outXml($arr);
                exit;
            }
            if (!$iBatchID) {
                $arr = ['code' => '1016', 'info' => '图片删除失败,确认图片ID是否正确'];
                echo $this->outXml($arr);
                exit;
            }
            $sql = "UPDATE {$table} SET `iPhotoNum` = `iPhotoNum`-1 WHERE `iBatchID` = {$iBatchID} AND `iAccountID` = {$iAccountID}";
            $this->Classification_model->db()->query($sql);
            $flag3 = $this->Classification_model->db()->affected_rows();
            if (!$flag3) {
                // 事务回滚
                $this->Classification_model->db->trans_rollback();
                $arr = ['code' => '1017', 'info' => '图片批次数量操作失败'];
            } else {
                // 事务提交
                $this->Classification_model->db->trans_complete();
                $arr = ['code' => '1'];
            }
            echo $this->outXml($arr);
            exit;
        }
    }

    /**
     *  批次图片生成包
     */
    public function packagecreate()
    {
        // 加密的账号ID
        $iAccountIDPost = trim($this->input->post('iAccountID'));
        if (!$iAccountIDPost) {
            $arr = ['code' => '1005', 'info' => '账号ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $iAccountID = intval(EncryptionDeciphering($iAccountIDPost, false));
        // 验证账号ID是否存在
        $flag = $this->isExistiAccountID($iAccountID);
        if ($flag === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$flag) {
            $arr = ['code' => '1006', 'info' => '账号ID参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 批次ID
        $iBatchID = intval(trim($this->input->post('iBatchID')));
        if (!$iBatchID) {
            $arr = ['code' => '1012', 'info' => '参数批次ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        // 通过批次ID获取批次号和账号ID
        $sBatchInfo = $this->getBatchInfoById($iBatchID);
        if ($sBatchInfo === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$sBatchInfo) {
            $arr = ['code' => '1013', 'info' => '参数批次ID错误'];
            echo $this->outXml($arr);
            exit;
        }
        if ($sBatchInfo['iAccountID'] != $iAccountID) {
            $arr = ['code' => '1014', 'info' => '此批次不属于此账号'];
            echo $this->outXml($arr);
            exit;
        }
        $sBatchName = $sBatchInfo['sBatchName'];
        $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
        $sql = "SELECT * FROM  {$tableName} WHERE `iBatchID` = ? AND `iAccountID` = ? AND `iStatus` = ?;";
        $res = $this->Classification_model->query($sql, [$iBatchID, $iAccountID, 1]);
        if ($res === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        // 生成xml
        $Dom = $this->Dom;
        $result = $Dom->createElement('result');
        $Dom->appendChild($result);
        // 创建code节点
        $code = $Dom->createElement('code', 1);
        $result->appendChild($code);
        // 创建body节点
        $body = $Dom->createElement('body');
        $result->appendChild($body);
        // 创建批次节点
        $batchXml = $Dom->createElement('batch');
        $body->appendChild($batchXml);
        // 添加批次节点属性
        $batchIDXml = $Dom->createAttribute('id');
        $batchIDXml->value = $iBatchID;
        // 属性值填入XML
        $batchXml->appendChild($batchIDXml);
        // 添加批次节点name属性
        $batchNameXml = $Dom->createAttribute('name');
        $batchNameXml->value = $sBatchName;
        // 属性值填入XML
        $batchXml->appendChild($batchNameXml);
        if ($res) {
            foreach ($res as $key => $row) {
                if ($row['iClassified'] == 0) {
                    $arr = ['code' => '1018', 'info' => '此批次中含有未分类的图片'];
                    $this->Dom = new DOMDocument('1.0', 'utf-8');
                    echo $this->outXml($arr);
                    exit;
                }
                if ($flag[0]['sBelongSite'] == 1) {  //服装站
                    // 性别
                    $iGender = trim(GetCategory::getOtherFromIds($row['iGenderID'], ['sEnPathName'], 'string'));
                    // 地区
                    $iRegion = trim(GetCategory::getFieldFromId($row['iRegionID'], 'sRegionPathName'));
                    // 季节
                    $iSeason = trim(GetCategory::getOtherFromIds($row['iSeasonID'], ['sEnPathName'], 'string'));
                    // 单品
                    $iCategory = trim(GetCategory::getOtherFromIds($row['iCategoryID'], ['sEnPathName'], 'string'));
                    // 品名
                    $iSubCategory = trim(GetCategory::getOtherFromIds($row['iSubCategoryID'], ['sEnPathName'], 'string'));
                    // 部位
                    $iPartID = trim(GetCategory::getOtherFromIds($row['iPartID'], ['sEnPathName'], 'string'));
                    switch ($row['sColumnName']) {
                        case 'perform': // 秀场提炼
                            $path = $sBatchName . '/' . $iGender . '/' . $iRegion . '/' . $iSeason . '/' . $row['iIndustryID'] . '/' . $iCategory . '/' . $iSubCategory . '/' . $row['sSectionName'];
                            break;
                        case 'stylegraphic': // 款式流行
                            $path = $sBatchName . '/' . $iGender . '/' . $iSeason . '/' . $row['iIndustryID'] . '/' . $iCategory . '/' . $iSubCategory . '/' . $row['sSectionName'];
                            break;
                        case 'marketphoto': // 商场爆款
                            $path = $sBatchName . '/' . $iRegion . '/' . $iGender . '/' . $iSeason . '/' . $row['iIndustryID'] . '/' . $iCategory . '/' . $iSubCategory . '/' . $row['sSectionName'];
                            break;
                        case 'ordermeeting': // 订货会
                        case 'tideleader': // 潮流领袖
                            $path = $sBatchName . '/' . $iGender . '/' . $iSeason . '/' . $row['iIndustryID'] . '/' . $iCategory . '/' . $iSubCategory . '/' . $row['sSectionName'];
                            break;
                        case 'street': // 街拍图库
                            $path = $sBatchName . '/' . $iRegion . '/' . $iGender . '/' . $iSeason . '/' . $row['iIndustryID'] . '/' . $iCategory . '/' . $iSubCategory;
                            break;
                        case 'styledetail': // 款式细节
                            // 款式细节地区
                            $iRegion = trim(GetCategory::getOtherFromIds($row['iRegionID'], ['sEnPathName'], 'string'));
                            $path = $sBatchName . '/' . $iRegion . '/' . $iGender . '/' . $iSeason . '/' . $row['iIndustryID'] . '/' . $iCategory . '/' . $iPartID;
                            break;
                        case 'shopset': // 橱窗陈列
                            $path = $sBatchName . '/' . $iGender . '/' . $iSeason . '/' . $iRegion;
                            break;
                        case 'accessories': //服饰品
                            $path = $sBatchName . '/' . $iGender . '/' . $iSeason . '/' . '/' . $iCategory . '/' . $iSubCategory;
                            break;
                    }
                } elseif ($flag[0]['sBelongSite'] == 2) { //箱包站
                    switch ($row['sColumnName']) {
                        case 'style_graphic':   //款式图集
                            $_path = $row['iGenderID'] . ',' . $row['iSeasonID'] . ',' . $row['iRegionID'] . ',' . $row['sModeID'] . ',' . $row['sMaterialID'] . ',' . $row['iWayID'];
                            break;
                        case 'brand':           //名牌跟踪
                        case 'china_style':     //国内市场
                            $_path = $row['iGenderID'] . ',' . $row['iSeasonID'] . ',' . $row['iRegionStyleID'] . ',' . $row['sModeID'] . ',' . $row['sMaterialID'] . ',' . $row['iWayID'];
                            break;
                        case 'market_photo':    //商场爆款
                        case 'exhibition_tracking'://展会跟踪
                        case 'presscon':        //发布会
                        case 'street':          //街头时尚
                        case 'sportstie':       //运动拉杆
                            $_path = $row['iGenderID'] . ',' . $row['iSeasonID'] . ',' . $row['iRegionID'] . ',' . $row['sModeID'];
                            break;
                        case 'ordermeeting':
                            $_path = $row['iGenderID'] . ',' . $row['iSeasonID'] . ',' . $row['sModeID'];
                            break;
                    }
                    if ($row['sColumnName'] == 'street') { //街头时尚没有款目录
                        $path = $sBatchName . '/' . $this->Classification_model->changeIdToPath($_path);
                    } else {
                        $path = $sBatchName . '/' . $this->Classification_model->changeIdToPath($_path) . '/' . $row['sSectionName'];
                    }
                }
                // 追加img
                $img = $Dom->createElement('img');
                $batchXml->appendChild($img);

                $sPhotoName = $row['sPhotoName'];
                // 大图路径
                $bigPath = '/' . $row['sColumnName'] . '/' . $path . '/big/';
                // 小图路径
                if (in_array($row['sColumnName'], array('shopset', 'ordermeeting', 'accessories')) OR $flag[0]['sBelongSite'] == 2) {
                    $smallPath = '/' . $row['sColumnName'] . '/' . $path . '/small/';
                } else {
                    $smallPath = '/' . $row['sColumnName'] . '/' . $path . '/middle/';
                }
                // 追加图片名
                $photoInfoXml = $Dom->createElement('photoInfo');
                $img->appendChild($photoInfoXml);
                // 将图片名称放入CDATA
                $cdata = $Dom->createCDATASection($sPhotoName);
                $photoInfoXml->appendChild($cdata);
                // 添加图片节点属性id
                $photoIDXml = $Dom->createAttribute('id');
                $photoIDXml->value = $row['iPhotoID'];
                // 属性值填入XML
                $photoInfoXml->appendChild($photoIDXml);
                // 追加大图路径
                $bigPathXml = $Dom->createElement('bigPath', $bigPath);
                $img->appendChild($bigPathXml);
                // 追加小图路径
                $smallPathXml = $Dom->createElement('smallPath', $smallPath);
                $img->appendChild($smallPathXml);
            }
            // 修改t_classification_photo_batch表中的打包时间
            $table = self::T_CLASSIFICATION_PHOTO_BATCH;
            $data = ['dPackageTime' => date('Y-m-d H:i:s')];
            $condition = ['iBatchID' => $iBatchID];
            $flag = $this->Classification_model->executeUpdate($table, $data, $condition, false);
            if (!$flag) {
                $arr = ['code' => '1011', 'info' => '服务端错误'];
                $this->Dom = new DOMDocument('1.0', 'utf-8');
                echo $this->outXml($arr);
                exit;
            }
        }
        echo $Dom->saveXML();
        exit;
    }

    // 验证该账号在账号表里是否存在
    private function isExistiAccountID($iAccountID, $rc = true)
    {
        $tableName = self::T_CLASSIFICATION_ACCOUNT;
        $sql = "SELECT `iAccountID`,`sBelongSite` FROM {$tableName} WHERE `iAccountID` = ? LIMIT 1;";
        $res = $this->Classification_model->query($sql, [$iAccountID], $rc);
        if ($res === false) {
            return false;
        } elseif (!$res) {
            return array();
        } else {
            return $res;
        }
    }

    // 通过批次id获取批次号
    private function getBatchInfoById($iBatchID, $rc = true)
    {
        $table = self::T_CLASSIFICATION_PHOTO_BATCH;
        $sql = "SELECT `iAccountID`, `sBatchName` FROM {$table} WHERE `iBatchID` = ?;";
        $iBatch = $this->Classification_model->query($sql, [$iBatchID], $rc);
        if ($iBatch === false) {
            return false;
        } elseif (!$iBatch) {
            return array();
        } else {
            return $iBatch[0];
        }
    }

    // 通过图片id获取批次ID
    private function getiBatchIDByiPhotoID($iPhotoID, $rc = true)
    {
        $tableName = self::T_CLASSIFICATION_PHOTO_DATA;
        $sql = "SELECT `iBatchID` FROM {$tableName} WHERE `iPhotoID` = ? ;";
        $res = $this->Classification_model->query($sql, [$iPhotoID], $rc);
        if ($res === false) {
            return false;
        } elseif (!$res) {
            return array();
        } else {
            return $res[0]['iBatchID'];
        }
    }

    /* @TODO        生成XML
     * $arr        [array]    [需要被转化成XML格式的数组]
     * $nodeName    [string]    [节点名称,根节点默认data]
     * $nodeName    [$element]    [仅递归中使用，调用勿传]
     * 注意:传进的数组键名要符合变量定义规范(仅适应和简单无属性XML)
     */
    public function outXml($arr, $nodeName = 'result', $element = '', $Dom = '')
    {
        $Dom = empty($Dom) ? new DOMDocument('1.0', 'utf-8') : $Dom;
        if ($element == '') {
            $data = $Dom->createElement($nodeName);
            $Dom->appendChild($data);
        } else {
            $data = $element;
        }
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $element = $Dom->createElement($key);
                $data->appendChild($element);
                $this->outXml($value, $key, $element, $Dom);
            } else {
                $element = $Dom->createElement($key, $value);
                $data->appendChild($element);
            }
        }
        return $Dom->saveXML();
    }
    //===================================================2017-2-24后补充==============================================================
    //--------------------------------------------------------------
    //	栏目补充信息
    //--------------------------------------------------------------
    public function perfectInformation()
    {
        $EncodeID = $this->input->post('iAccountID') OR die("用户ID不能为空!");
        $iAccountID = intval(EncryptionDeciphering($EncodeID, false));
        // 验证账号ID是否存在
        $flag = $this->isExistiAccountID($iAccountID) OR die("用户ID不符合要求！");
        $sBatchName = $this->input->post('sBatchName') OR die("批次号不能为空!");
        $sql = "SELECT sColumnName FROM " . self::T_CLASSIFICATION_PHOTO_BATCH . " AS A LEFT JOIN " . self::T_CLASSIFICATION_PHOTO_DATA . " AS B ON A.iBatchID=B.iBatchID WHERE A.sBatchName=? AND A.iAccountID=? AND iJobStatus=0 LIMIT 1";
        $row = $this->Classification_model->query($sql, [$sBatchName, $iAccountID]);
        $column = $row[0]['sColumnName'];
        if (!$column) {
            die("你要添加数据的批次不存在，或状态非未处理！");
        }
        //获取时装周专题
        $fashionWeeks = GetCategory::getSomeAttr(19);
        //来源
        $Sources = $this->Classification_model->getSources();
        //showroom
        $showRoom = GetCategory::getSomeAttr(27);
        //街拍类型
        $streetBeatTypes = GetCategory::getSomeAttr(24);

        $this->assign('EncodeID', $EncodeID);
        $this->assign('sBatchName', $sBatchName);
        $this->assign("streetBeatTypes", $streetBeatTypes);
        $this->assign("SourcesJson", json_encode($Sources));
        $this->assign("showRoom", $showRoom);
        $this->assign("showRoomJson", json_encode($showRoom));
        $this->assign("fashionWeeks", $fashionWeeks);
        $this->assign("column", $column);
        $this->assign("nowDate", date('Y-m-d H:i:s'));
        $this->display('perfectInformation.html');
    }

    public function addData()
    {
        // 验证账号ID是否存在
        $EncodeID = $this->input->post('EncodeID') OR die("用户ID不能为空!");
        $iAccountID = intval(EncryptionDeciphering($EncodeID, false));
        $flag = $this->isExistiAccountID($iAccountID) OR die("用户ID不符合要求！");

        $sBatchName = $this->input->post('sBatchName');    //批次号
        $data['sZipName'] = $this->input->post('sZipName');            //包文件名
        $data['iStreetBeatType'] = $this->input->post('iStreetBeatType');    //街拍类型
        $data['sFashionWeek'] = $this->input->post('sFashionWeek');        //时装周专题
        $data['iSource'] = $this->input->post('source_2') ? $this->input->post('source_2') : $this->input->post('source_1');//来源
        $data['iShowroomCity'] = $this->input->post('showRoomPid');    //showRoom 城市
        $data['iShowroom'] = $this->input->post('showRoom');            //showRoom
        $data['dScheduleTime'] = $this->input->post('dScheduleTime') ? $this->input->post('dScheduleTime') : date('Y-m-d H:i:s'); //计划更新时间
        //地区
        if ($this->input->post('city')) {
            $data['iRegion'] = $this->input->post('city');
        } elseif ($this->input->post('country')) {
            $data['iRegion'] = $this->input->post('country');
        } elseif ($this->input->post('continent')) {
            $data['iRegion'] = $this->input->post('continent');
        } elseif ($this->input->post('region')) {
            $data['iRegion'] = $this->input->post('region');
        }
        $data['iJobStatus'] = 1;
        $data = array_filter($data);

        $table = self::T_CLASSIFICATION_PHOTO_BATCH;
        $where = "sBatchName='{$sBatchName}' AND iJobStatus=0";
        $res = $this->Classification_model->db()->update($table, $data, $where);
        $num = $this->Classification_model->db()->affected_rows();
        if ($num) {
            echo "添加成功！";
        } else {
            echo "添加失败，请确认数据是否已被添加，如未被添加请重新尝试！";
        }
    }

    public function ajaxRegion()
    {
        $areaid = $_GET['areaid'];
        // 地区
        $regionInfo = GetCategory::getRegionInfo($areaid);
        echo json_encode($regionInfo);
        exit;
    }

    //--------------------------------------------------------------
    //	图片识别接口
    //--------------------------------------------------------------
    public function discern()
    {
        //1、基本验证
        $EncodeID = $this->input->post('iAccountID');                    //加密用户ID
        $iAccountID = $this->checkAccount($EncodeID);
        $sStreamContent = base64_decode($this->input->post('sStreamContent'));    //二进制流
        if (!$sStreamContent) {
            $arr = ['code' => '1031', 'info' => '二进制流信息不能为空'];
            echo $this->outXml($arr);
            exit;
        }

        //2、上传图片
        $this->load->library('POP_Uploadpic');
        $savePath = '/tmp/discern/' . date("Ymd") . "/$iAccountID/";
        $body = array(
            'fName' => "discern_{$iAccountID}" . time() . rand(1111, 9999) . '.jpg',
            'fTargetPath' => $savePath,
            'fStream' => $sStreamContent,
            'fPrefix' => 'fashion',
        );
        $json = $this->pop_uploadpic->curlRequest($body);
        $imgRes = json_decode($json, true);
        $imgurl = STATIC_URL1 . $imgRes['info'];

        //3、调用第三方接口
        $this->load->library('POP_CostumeAnalyse');
        $apiResult = $this->pop_costumeanalyse->popCostumeAnalyse($imgurl);
        if ($apiResult === false) {
            $arr = ['code' => '1032', 'info' => '图片上传未成功！或' . $imgurl . '图片未找到'];
            echo $this->outXml($arr);
            exit;
        }
        //4、组装XML
        $Dom = $this->Dom;
        $result = $Dom->createElement('result');
        $Dom->appendChild($result);
        //状态码
        $code = $Dom->createElement('code', 1);
        $result->appendChild($code);
        //主体
        $body = $Dom->createElement('body');
        $result->appendChild($body);
        //图片url
        $url = $Dom->createElement('url', $imgurl);
        $body->appendChild($url);
        //圈出区域
        $areas = $Dom->createElement('areas');
        $body->appendChild($areas);
        foreach ($apiResult['res'] as $val) {
            $area = $Dom->createElement('area');
            $areas->appendChild($area);
            $x = $val['box'][0];
            $y = $val['box'][1];
            $width = $val['box'][2];
            $height = $val['box'][3];
            $xAttr = $Dom->createAttribute('x');
            $xAttr->value = $x;
            $area->appendChild($xAttr);
            $yAttr = $Dom->createAttribute('y');
            $yAttr->value = $y;
            $area->appendChild($yAttr);
            $widthAttr = $Dom->createAttribute('width');
            $widthAttr->value = $width;
            $area->appendChild($widthAttr);
            $heightAttr = $Dom->createAttribute('height');
            $heightAttr->value = $height;
            $area->appendChild($heightAttr);
        }
        $xml = $Dom->saveXML();
        echo $xml;
        exit;
    }

    //--------------------------------------------------------------
    //	获取批次列表
    //--------------------------------------------------------------
    public function batchlist()
    {
        //基本验证
        $EncodeID = $this->input->post('iAccountID');
        $iAccountID = $this->checkAccount($EncodeID);

        $iPageNum = $this->input->post('iPageNum') ? $this->input->post(iPageNum) : 1;        //页码
        $iPageSize = $this->input->post('iPageSize') ? $this->input->post(iPageSize) : 30;    //每页条数

        $where = "WHERE A.iAccountID=$iAccountID";
        //查询条件
        $sColumnName = trim($this->input->post('sColumn'));            //栏目(在另一张表特殊)
        $where .= $sColumnName ? " AND sColumnName='$sColumnName'" : '';
        $sBatchName = trim($this->input->post('sBatchName'));            //批次名
        $where .= $sBatchName ? " AND sBatchName='$sBatchName'" : '';
        $dCreateStartTime = trim($this->input->post('dCreateStartTime'));    //开始时间
        $where .= $dCreateStartTime ? " AND A.dCreateTime>='$dCreateStartTime'" : '';
        $dCreateEndTime = trim($this->input->post('dCreateEndTime'));        //结束时间

        $fild = "DISTINCT A.sBatchName,A.iPhotoNum ,A.dPackageTime ,B.sColumnName,A.iJobStatus";
        $where .= $dCreateEndTime ? " AND A.dCreateTime <='$dCreateEndTime'" : '';
        $limit = "LIMIT " . ($iPageNum - 1) * $iPageSize . ",$iPageSize";
        $sql = "SELECT $fild  FROM " . self::T_CLASSIFICATION_PHOTO_BATCH . " AS A LEFT  JOIN " . self::T_CLASSIFICATION_PHOTO_DATA . " AS B ON A.iBatchID=B.iBatchID $where  $limit";
        $res = $this->Classification_model->query($sql);
        $sql = "SELECT count(DISTINCT sBatchName) as total FROM " . self::T_CLASSIFICATION_PHOTO_BATCH . " AS A LEFT  JOIN " . self::T_CLASSIFICATION_PHOTO_DATA . " AS B ON A.iBatchID=B.iBatchID $where";
        $totalRes = $this->Classification_model->query($sql);
        $total = $totalRes[0]['total'];

        $Dom = $this->Dom;
        $result = $Dom->createElement('result');
        $Dom->appendChild($result);
        //状态码
        $code = $Dom->createElement('code', 1);
        $result->appendChild($code);
        //主体
        $body = $Dom->createElement('body');
        $result->appendChild($body);

        $rows = $Dom->createElement('batchs');
        $body->appendChild($rows);

        $attr = $Dom->createAttribute('total');
        $attr->value = $total;
        $rows->appendChild($attr);
        if (count($res) > 0) {
            foreach ($res as $value) {
                $row = $Dom->createElement('batch');
                $rows->appendChild($row);
                foreach ($value as $key => $val) {
                    $attr = $Dom->createAttribute($key);
                    $attr->value = $val;
                    $row->appendChild($attr);
                }
            }
        }
        $xml = $Dom->saveXML();
        echo $xml;
    }

    //--------------------------------------------------------------
    //	通知接口
    //--------------------------------------------------------------
    public function notice()
    {
        $sBatchName = trim($this->input->post('sBatchName'));            //批次名
        $iJobStatus = (int)$this->input->post('iJobStatus');            //工作状态

        $data['iJobStatus'] = $iJobStatus;
        if (1 <= $iJobStatus - 1 && 2 >= $iJobStatus - 1) {
            $where = "sBatchName='$sBatchName' AND iJobStatus=" . ($iJobStatus - 1);
        } else {
            $arr = ['code' => '1035', 'info' => 'iJobStatus不符合要求！'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$sBatchName) {
            $arr = ['code' => '1037', 'info' => '批次不能为空！'];
            echo $this->outXml($arr);
            exit;
        }
        $table = self::T_CLASSIFICATION_PHOTO_BATCH;
        $sql = "SELECT * FROM $table  WHERE sBatchName='$sBatchName'";
        $row = $this->Classification_model->query($sql);
        if (empty($row[0]['iBatchID'])) {
            $arr = ['code' => '1038', 'info' => '批次不存在！'];
            echo $this->outXml($arr);
            exit;
        }
        $res = $this->Classification_model->db()->update($table, $data, $where);
        $num = $this->Classification_model->db()->affected_rows();
        if ($num > 0) {
            $arr = ['code' => '1', 'info' => 'success'];
            echo $this->outXml($arr);
            exit;
        } else {
            $arr = ['code' => '1036', 'info' => 'iJobStatus与上一状态不匹配'];
            echo $this->outXml($arr);
            exit;
        }
    }

    //--------------------------------------------------------------
    //	私有方法
    //--------------------------------------------------------------
    /*
     *@todo		[检验并解密用户ID]
     *@param	[string]	[$EncodeID]	[加密的用户ID]
     *@return	[int]		[用户ID]
     */
    private function checkAccount($EncodeID)
    {
        if (!$EncodeID) {
            $arr = ['code' => '1005', 'info' => '账号ID不能为空'];
            echo $this->outXml($arr);
            exit;
        }
        $iAccountID = intval(EncryptionDeciphering($EncodeID, false));
        // 验证账号ID是否存在
        $flag = $this->isExistiAccountID($iAccountID);
        if ($flag === false) {
            $arr = ['code' => '1011', 'info' => '服务端错误'];
            echo $this->outXml($arr);
            exit;
        }
        if (!$flag) {
            $arr = ['code' => '1006', 'info' => '账号ID参数错误'];
            echo $this->outXml($arr);
            exit;
        }
        return $iAccountID;
    }
}
