<?php
/*
  * 收藏相关
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('collect_model');
    }

    /*
      + 设置收藏
      + @parameter $parameter 收藏信息
      + @parameter $handle	收藏操作  join 加入收藏  cancel 取消收藏
      + $parameter  iColumnId-sTableName-iPriId-iType
      + join : https://2016.pop-fashion.com/collect/setcollect/1-picture-100-12/
      + cancel : https://2016.pop-fashion.com/collect/setcollect/1-picture-100-12/cancel/
      +
    */
    public function setcollect($parameter = '', $handle = 'join')
    {
        $aParameter = $parameter ? explode('-', $parameter) : [];
        $columnId = intval($aParameter[0]);
        $fakeTable = strtolower(trim($aParameter[1]));
        $id = intval($aParameter[2]);
        $iType = intval($aParameter[3]);
        $realTable = getProductTableName($fakeTable);

        // 收藏类别：1=>发布会,2=>品牌,素材类[3=>款式模板 4=>款式细节 5=>图案素材 6=>服饰品 7=>店铺陈列],8=>图册,灵感[9=>灵感报告 10=>灵感图库],11=>趋势,12=>分析,13=>款式,14=>设计素材/面料图库
        if (!$iType) {
            switch ($columnId) {
                case 80:
                    $iType = 3;
                    break;
                case 81:
                    $iType = 4;
                    break;
                case 82:
                case 117:
                case 120:
                case 124:
                    $iType = 5;
                    break;
                case 84:
                    $iType = 6;
                    break;
                case 85:
                    $iType = 7;
                    break;
                case 91:
                    $iType = 10;
                    break;
                case 4:
                case 50:
                case 52:
                case 54:
                case 55:
                case 56:
                case 57:
                case 122:
                case 123:
                    $iType = 13;
                    break;
                default :
                    $iType = -1;
                    break;
            }
        }

        // 参数不正确
        if (!$columnId || !$id || !$iType || !$realTable) {
            echo -1;
            exit;
        }
        // 权限不符合--试用VIP用户 || VIP子账号
        $aPower = memberPower();
        if (!$aPower['P_Collect']) {
            echo -2;
            exit;
        }

        $aLogonMessage = get_cookie_value();
        $aCollect = [];
        $aCollect['dCreateTime'] = date('Y-m-d H:i:s');
        $aCollect['sAccountId'] = $sAccountId = $aLogonMessage['sChildID'];

        $aCollect['iColumnId'] = $columnId;
        $aCollect['iPriId'] = $id;
        $aCollect['iType'] = $iType;
        $aCollect['sTableName'] = $realTable;

        $sDatabase = in_array($realTable, ['picture', 'mostrend_content']) ? OpPopFashionMerger::POP_TREND_DATABASE_NAME : ($realTable == 'brand_library' ? OpPopFashionMerger::POP_POP136_DATABASE_NAME : OpPopFashionMerger::POP_FASHION_DATABASE_NAME);
        $aCollect['sDatabase'] = $sDatabase;

        $bIsCollect = $this->collect_model->existCollectStatus($sAccountId, $sDatabase, $realTable, $id);

        $collectResult = ['code' => 0 , 'data' => [], 'msg' => ''];
        switch ($handle) {
            case 'cancel' : // 已收藏的取消收藏
                if ($bIsCollect) {
                    unset($aCollect['dCreateTime'], $aCollect['iColumnId'], $aCollect['iType']);
                    $res = $this->collect_model->cancelCollect($aCollect);
                    $res && $this->collect_model->updateCollectSolr($realTable, $id);

                    $collectResult['code'] = 10;
                    $collectResult['msg'] = '已取消收藏';
                }
                break;
            case 'join' : // 未收藏的加入收藏
            default:
                if (!$bIsCollect) {
                    if (!$this->collect_model->insertCollect($aCollect)) {
                        echo -4;die;
                    }
                    // 收藏量统计
                    $this->load->model('statistics_model');
                    $res = $this->statistics_model->setCollectCount($realTable, $id, $columnId);
                    $res && $this->collect_model->updateCollectSolr($realTable, $id);

                    $columnPid = GetCategory::getOtherFromColId($columnId, 'iColumnPid');
                    $columnPid = !$columnPid ? $columnId : $columnPid;
                    $colName = GetCategory::getOtherFromColId($columnPid, 'sName');
                    $collectResult['code'] = 20;
                    $collectResult['msg'] = '已加入收藏夹-' . $colName;
                }
                break;
        }
        echo json_encode($collectResult);die;
    }

}