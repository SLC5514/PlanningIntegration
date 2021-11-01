<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo API
 */
class Api extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
      + 权限修改清除cache
      + POP_GLOBAL_KEYS = acd2605364d8017f0828a26c60cc7f64
      + /Api/clearPrivilege/?M_ID=106645&&T=1458877059&token=MD5( MD5( M_ID.'-'.T.POP_GLOBAL_KEYS ) )
    */
    public function index()
    {
        echo 'api';
    }

    /*
      + 权限修改清除cache
      + POP_GLOBAL_KEYS = acd2605364d8017f0828a26c60cc7f64
      + /Api/clearPrivilege/?M_ID=106645&&T=1458877059&token=MD5( MD5( M_ID.'-'.T.POP_GLOBAL_KEYS ) )
    */
    public function clearPrivilege()
    {
        $get = $this->input->get(array('M_ID', 'T', 'token'), TRUE);
        $checkToken = MD5(MD5($get['M_ID'] . '-' . $get['T'] . POP_GLOBAL_KEYS));
        if ((time() - $get['T']) > 600 || $checkToken != $get['token']) {
            return FALSE;
        } else {
            //清除缓存
            $minId = $get['M_ID'];
            $this->load->model('User_model');
            $cc = $this->User_model->checkUserVip($get['M_ID'], TRUE);
            //下线所有用户
            $memcacheKey = MIAN_UID_MEMKEY_PRE . $minId;
            $Users = $this->cache->memcached->get($memcacheKey);
            if (is_array($Users)) {
                foreach ($Users as $pop_uid) {
                    $_memcacheKey = UID_MEMKEY_PRE . $pop_uid;
                    $this->cache->memcached->delete($_memcacheKey);
                }
            }
            $this->cache->memcached->delete($memcacheKey);
            return TRUE;
        }
    }

    /**
     * 提供云图移动客户端使用
     * 根据用户账号名称，获取用户权限和身份信息
     */
    public function privilegeInfo()
    {
        $this->load->model('User_model');

        $userId = $this->getUserIdByAccountName();

        $data = ['identity' => '', 'privilege' => []];
        $priInfo = $this->User_model->getUserPowerDate($userId);
        if ($priInfo) {
            $data['identity'] = 'VIP';

            $priInfo = array_map(function ($val) {
                unset($val['iPrivId']);
                $val['dEndTime'] = strtotime($val['dEndTime']);
                return $val;
            }, $priInfo);

            $data['privilege'] = $priInfo;
        } else {
            $data['identity'] = 'GENERAL';
        }

        outPrintApiJson(0, 'OK', $data, '请求时间:' . date("Y-m-d H:i:s"));
    }

    /**
     * 提供云图移动客户端使用
     * 根据用户账号名称，获取用户虚拟样衣模板数据
     */
    public function vsTpl()
    {
        $this->load->model('VirtualTryOn_model');

        $userId = $this->getUserIdByAccountName();

        $iTplSite = $this->VirtualTryOn_model->getUserTplSite($userId);
        if (!$iTplSite) {
            outPrintApiJson(10003, '模板未设置站点');
        }

        $data = $this->VirtualTryOn_model->getTemplates($iTplSite, $userId);

        foreach ($data as $key => $item) {

            if ($key == 'isCustom') {
                continue;
            }

            $data[$key] = array_map(function ($val) {
                $val['sLargePath'] = STATIC_URL1 . $val['sLargePath'];
                $val['sThumbnailPath'] = STATIC_URL1 . $val['sThumbnailPath'];
                return $val;
            }, $item);
        }

        $relation = [];
        // 1-服装,2-箱包,3-鞋子,5-家纺
        switch ($iTplSite) {
            case 1:
                // fashion.t_dict_attribute 虚拟样衣模板分类
                $relation = GetCategory::getAttrValueByType(30);
                break;
            case 2:
                // 后台配置硬编码
                $relation = [
                    1 => '男包',
                    2 => '女包',
                    3 => '运动拉杆',
                    4 => '小皮件',
                    100 => '定制',
                ];
                break;
            case 3:
                // 后台配置硬编码
                $relation = [
                    1 => '女鞋',
                    2 => '男鞋',
                    3 => '运动鞋',
                    4 => '童鞋',
                    100 => '定制',
                ];
                break;
            case 5:
                // 后台配置硬编码
                $relation = [
                    1 => '床上用品',
                    2 => '家居用品',
                    3 => '软装布艺',
                    4 => '家居服',
                    5 => '配饰',
                    100 => '定制',
                ];
                break;

        }
        // 排除不存在的模板，在info中
        $exitsArr = array_keys($relation);
        if ($exitsArr) {
            foreach ($exitsArr as $item) {
                if (!in_array($item, array_keys($data))) {
                    unset($relation[$item]);
                }
            }
        }

        outPrintApiJson(0, 'OK', $data, ['site' => '所选站点为:' . $iTplSite, 'map' => array_flip($relation), 'keys' => array_keys(array_flip($relation))]);
    }

    /**
     * 根据账号名获取用户id
     *
     * @return mixed
     */
    private function getUserIdByAccountName()
    {
        $accountName = $this->input->post('account_name');

        if (!$accountName) {
            outPrintApiJson(10001, '账号名为空', [], '请求时间:' . date("Y-m-d H:i:s"));
        }

        // 根据用户账号获取用户ID
        $userId = $this->User_model->check_user_exists($accountName);

        if (!$userId) {
            outPrintApiJson(10002, '账号名不存在', [], '请求时间:' . date("Y-m-d H:i:s"));
        }

        return $userId;

    }

}