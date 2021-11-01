<?php

/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/3 13:57
 */
class VirtualTryOn_model extends POP_Model
{
    //模板分类字典表
    const TABLE_T_VIRTUAL_TEMPLATE_DICTIONARY = 'yuntu.t_virtual_template_dictionary';
    //模板表
    const TABLE_T_VIRTUAL_TEMPLATE = 'yuntu.t_virtual_template';
    //模板文件表
    const TABLE_T_VIRTUAL_TEMPLATE_FILE = 'yuntu.t_virtual_template_file';
    //上传的本地花型表
    const TABLE_T_UPLOAD_PATTERN_FLOWERS = 'yuntu.t_upload_pattern_flowers';
    //分组表
    const TABLE_T_DESIGN_GROUP = 'yuntu.t_design_group';
    //分组与效果图关系表
    const TABLE_T_GROUP_TEMPLATE_RELATION = 'yuntu.t_group_template_relation';
    //制版间
    const TABLE_T_DESIGN_TEMPLATE = 'yuntu.t_design_template';
    //个人中心-分享表
    const TABLE_T_SHARE_GROUP = 'yuntu.t_share_group';


    public function getUserTplSite($uId)
    {
        $rs = $this->db->where(['id' => $uId])->get('pop136.fashion_user')->result_array();
        $template_site = $rs[0]['template_2d'] ? json_decode($rs[0]['template_2d'],true) : [];
        return $template_site;
    }

    public function modTplSite($uId, $iTplSite)
    {
        $sql = "UPDATE pop136.fashion_user SET iTplSite={$iTplSite} WHERE id=$uId";
        return $this->db->query($sql);
    }

    public function getTemplates($iSite = 1, $iAccountId)
    {
        //云图 增加个人定制模板查询,条件如 iAccountId=0 OR iAccountId = 当前登录用户ID
        $where = " `iSite`={$iSite} AND `iStatus`=1 AND `iAccountId` IN (0,{$iAccountId}) ";
        $lists = $this->db->where($where)
            ->order_by('iWeight DESC')
            ->get('fashion.t_virtual_spl_template')
            ->result_array();
        $list = array();
        foreach ($lists as $k => $v) {
            if (!key_exists($v['iClassifyId'], $list)) {
                $list[$v['iClassifyId']] = array();
            }
            $list[$v['iClassifyId']][] = $v;

            //是否有定制模板
            if ($v['iClassifyId'] == '12218') {
                $list['isCustom'] = '1';  //1：有定制模板
            }
        }

        $lists = $list;
        return $lists;
    }

    /**
     * 模拟成品--没有$path时--默认图案素材的前40条数据
     *
     * @param $columnId
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getDefaultPatternMaterial($columnId, $offset, $limit)
    {
        // 拼接条件
        $list = $result = [];
        $conditions = ['iColumnId' => $columnId];
        if ($columnId == 82) { // 82 -- 图案素材
            $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour"));
            $conditions['dCreateTime'] = '[* TO ' . $endTime . 'Z]';
            $conditions['aWebsite'] = 1;
        }

        $arSort = ["dCreateTime" => "DESC", "pri_id" => "DESC"];// 时间与ID倒序
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        if ($totalCount) {
            foreach ($result as $key => $val) {
                $row["t"] = getProductTableName($val["tablename"]);
                $row["id"] = $val["pri_id"];

                // 获取其他数据
                $_aData = OpPopFashionMerger::getProductData($row["id"], $val["tablename"]);
                $aData = $_aData[$row["id"]];
                $imgPath = getImagePath($val["tablename"], $aData);

                $row['href'] = "/PatternLibrary/details/t_{$row['t']}-id_{$row['id']}";
                $row['big'] = $imgPath['bigPath'] ? $imgPath['bigPath'] : '';
                $row['small'] = $imgPath['smallPath'] ? $imgPath['smallPath'] : '';
                $row['mbig'] = $imgPath['bigPath'] ? str_replace('/big/', '/mbig/', $imgPath['bigPath']) : '';
                $row['isFull'] = $aData['sApplication'] == '14102' ? false : true; // 14102 局部, 14103 满身
                $row['defaultColor'] = $aData['sDefaultColor'];

                $list[$key] = $row;
            }
        }
        return $list;
    }

    //云图2D试衣--根据品类获取模板数据
    public function getTemplateDataBySite($tplSite = '',$classifyArr = [],$offset=0,$limit=1)
    {
        //查询所有模板
        $where = ' iPublishStatus=1 and  iStatus=1 ';
        if (in_array($tplSite, [1, 2, 3, 5,100])) {
            $where .= " and iSiteId=$tplSite ";
        }
        if(!empty($classifyArr)){
            if(is_array($classifyArr)) {
                $classifyStr = implode(',', $classifyArr);
                $where .= " and iClassifyId in($classifyStr) ";
            }else{
                $where .= " and iClassifyId=$classifyArr ";
            }
        }

        $this->db->where($where)->from(self::TABLE_T_VIRTUAL_TEMPLATE);
        $count = $this->db->count_all_results("",false);
        $query = $this->db->order_by('dCreateTime desc')->limit($limit,$offset)->get();
        $results = $query->result_array();

        if(!$results){
            return [0,[]];
        }

        return [$count,$results];

    }

    //查询对应模板id下的svg部件
    public function getTemplateSvgFile($template_id = '')
    {
        $ids = $template_id;
        if(is_array($template_id)){
            $ids = implode(',',$template_id);
        }
        $sql = "select id,iTemplateId,sPartName,sPartPath,iSort from ".self::TABLE_T_VIRTUAL_TEMPLATE_FILE." where iTemplateId in(?) and iStatus=1 order by iSort desc";
        $data = $this->db->query($sql,[$ids])->result_array();
        foreach ($data as &$value){
            $value['sPartPathArr'] = json_decode($value['sPartPath']);
        }
        return $data;
    }

    //获取该品类下所有分类
    public function getClassifyBySite($site)
    {
        if(!$site){
            return [];
        }
        $sql = "select * from ".self::TABLE_T_VIRTUAL_TEMPLATE_DICTIONARY." where iStatus=1 and iPid=? order by iSort desc";
        $data =  $this->db->query($sql,[$site])->result_array();
        return !empty($data) ? $data : [];
    }
    //获取分类的名称
    public function getClassifyNameById($classifyId= [])
    {
        if(!$classifyId){
            return [];
        }
        $ids = $classifyId;
        if(is_array($classifyId)){
            $ids = implode(',',$classifyId);
        }

        $sql = "select * from ".self::TABLE_T_VIRTUAL_TEMPLATE_DICTIONARY." where iStatus=1 and id in($ids) order by iSort desc ";
        $data =  $this->db->query($sql)->result_array();
        return !empty($data) ? $data : [];
    }

    //2d模板详情--兼容后台预览
    public function getTemplateDeatil($templateId,$designId='')
    {
        $return = [];
        if(!$templateId){
            return $return;
        }
        $sql = "select id,iSiteId,iClassifyId,sTemplateName,sCover,sBigTemplateImg from ".self::TABLE_T_VIRTUAL_TEMPLATE." where id=? and iStatus=1";
        $data =  $this->db->query($sql,[$templateId])->result_array();
        if(!$data){
            return $return;
        }
        $svg_files = $this->getTemplateSvgFile($templateId);
        $data[0]['svg_file'] = !empty($svg_files) ? $svg_files : [];

        if(!empty($designId)){
            $sql = "select id,iTemplateId,sTemplateSvgFile from ".self::TABLE_T_DESIGN_TEMPLATE." where id=? and iStatus=1";
            $renderInfo =  $this->db->query($sql,[$designId])->result_array();
        }
        //已渲染的json信息
        $render = '';
        if(!empty($renderInfo[0]['sTemplateSvgFile'])){
            $render = json_decode($renderInfo[0]['sTemplateSvgFile'],true);
        }
        $data[0]['render_info'] = $render;
        return $data[0];
    }

    //我的花型列表
    public function getFlowersList($userId,$offset,$limit=1)
    {
        $this->db->where('iUserId',$userId)->from(self::TABLE_T_UPLOAD_PATTERN_FLOWERS);
        $count = $this->db->count_all_results("",FALSE);
        $query = $this->db->order_by('dCreateTime desc')->limit($limit,$offset)->get();
        $data = $query->result_array() ;

        return empty($data) ? [0,[]] : [$count,$data];
    }

    //定制模板列表
    public function getUserCustomTpl($userId,$offset,$limit=1)
    {
        $where = "iUserId=$userId and iIsCustom=1 and iPublishStatus=1 and iStatus=1";
        $this->db->select('id,sTemplateName,iUserId,sCover')->from(self::TABLE_T_VIRTUAL_TEMPLATE)->where($where);
        $count = $this->db->count_all_results("",FALSE);
        $query = $this->db->order_by('dCreateTime desc')->limit($limit,$offset)->get();
        $data = $query->result_array() ;
        return empty($data) ? [0,[]] : [$count,$data];
    }

    //保存制版间至分组
    public function saveDesignImgGroup($userId,$groupId,$designId)
    {
        $table = self::TABLE_T_GROUP_TEMPLATE_RELATION;
        $data = [
            'iUserId' => $userId,
            'iGroupId' => $groupId,
            'iDesignId' => $designId,
            'dCreateTime' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    //新增分组
    public function tplAddGroup($userId, $groupName)
    {
        $table = self::TABLE_T_DESIGN_GROUP;
        $data = [
            'iUserId' => $userId,
            'sGroupName' => htmlspecialchars($groupName),
            'dCreateTime' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert($table,$data);
        return $this->db->insert_id();

    }

    //编辑分组
    public function tplEditGroup($userId,$groupId,$groupName)
    {
        $table = self::TABLE_T_DESIGN_GROUP;
        $data = [
            'iUserId' => $userId,
            'sGroupName' => htmlspecialchars($groupName),
        ];
        $this->db->where('id',$groupId);
        $this->db->update($table,$data);
        return $this->db->affected_rows();
    }

    //删除分组
    public function tplDelGroup($userId,$groupId)
    {
        //删除分组表
        $where = ['id'=>$groupId,'iUserId'=>$userId];
        $this->db->delete(self::TABLE_T_DESIGN_GROUP,$where);

        //删除分组和效果图关系表
        $where1 = ['iUserId'=>$userId,'iGroupId'=>$groupId];
        return $this->db->delete(self::TABLE_T_GROUP_TEMPLATE_RELATION,$where1);
    }
    
    //获取分组列表
    public function getTplGroupList($userId,$offset='',$pageSize='')
    {
        $this->db->where(['iUserId' => $userId])->from(self::TABLE_T_DESIGN_GROUP);
        $count = $this->db->count_all_results("",false);
        $query =$this->db->order_by('dCreateTime desc');
        if($pageSize) {
            $query->limit($pageSize, $offset);
        }
        $results = $query->get()->result_array();

        return [$count,$results];

    }

    //取分组下第一张效果图
    public function getGroupCover($userId, $groupIdArr = [],$group_name = [])
    {
        $data = $covers = $groupCover = [];
        if(is_array($groupIdArr)){
            $groupIds = implode(',',$groupIdArr);
        }
        //取每个分组下最新1个的制版图id
        $sql = "SELECT * FROM (SELECT * FROM ".self::TABLE_T_GROUP_TEMPLATE_RELATION." WHERE iUserId={$userId} and iGroupId in($groupIds) ORDER BY dCreateTime DESC) AS t GROUP BY iGroupId";
        $group = $this->db->query($sql)->result_array();
        if($group) {
            foreach ($group as $val) {
                $designIds[] = $val['iDesignId'];
                $groupCover[$val['iGroupId']] = ['iDesignId'=>$val['iDesignId']];
            }
        }

        //取制版效果图
        if(!empty($designIds)){
            $data = $this->getCoverByDesignId($designIds);
            foreach ($data as $val){
                $covers[$val['id']] = $val;
            }
        }

        $return = [];
        foreach ($groupIdArr as $id){
            $cover_list = $covers[$groupCover[$id]['iDesignId']];
            $return[] = [
                'group_id'=> $id,
                'design_id'=> isset($groupCover[$id]['iDesignId']) ? $groupCover[$id]['iDesignId'] : '',
                'group_name'=> isset($group_name[$id]) && !empty($group_name[$id]) ? $group_name[$id] : '',
                'iTemplateId'=>isset($cover_list) && !empty($cover_list) ? $cover_list['iTemplateId'] : '',
                'covers'=> isset($cover_list) && !empty($cover_list) ? $cover_list['sTemplateRender'] : '',
            ];

        }

        return $return;
    }

    //取制版效果图
    public function getCoverByDesignId($designIds = [])
    {
        $data = $this->db->select('id,iTemplateId,sTemplateRender')->where(['iStatus'=>1])->where_in('id',$designIds)->from(self::TABLE_T_DESIGN_TEMPLATE)->order_by('dCreateTime desc')->get()->result_array();
        return $data;
    }

    //根据用户id取制版效果图
    public function getDesignListByDesignId($userId,$offset='', $limit = '')
    {
        $this->db->select('id,iTemplateId,sTemplateRender')->where(['iStatus'=>1,'iUserId'=>$userId])->from(self::TABLE_T_DESIGN_TEMPLATE);
        $count =   $this->db->count_all_results("", false);
        $query = $this->db->order_by('dCreateTime desc')->limit($limit, $offset)->get();
        $data = $query->result_array();
        return [$count,$data];
    }

    //取单个分组下列表数据
    public function getSingleGroupList($userId,$groupId = '',$offset='',$limit = '')
    {
        if(!empty($groupId)){
           $where =  ['iGroupId' => $groupId, 'iUserId' => $userId];
        }else{
            $where =  ['iUserId' => $userId];
        }
        $this->db->where($where)->from(self::TABLE_T_GROUP_TEMPLATE_RELATION);
        $count = $this->db->count_all_results("", false);
        $query = $this->db->order_by('dCreateTime desc')->limit($limit, $offset)->get();
        $res = $query->result_array();

        $designIds = [];
        foreach ($res as $val){
            $designIds[] = $val['iDesignId'];
        }
        $designIds = array_unique(array_filter($designIds));
        //取制版效果图
        $data = $covers = [];
        if(!empty($designIds)){
            $data = $this->getCoverByDesignId($designIds);
        }
        if(count($data)<=0){
            return [0,[]];
        }
        return [$count,$data];
    }

    //获取分组信息
    public function getGroupInfo($userId,$groupId = '')
    {
        return $this->db->where(['id' => $groupId, 'iUserId' => $userId])->from(self::TABLE_T_DESIGN_GROUP)->get()->result_array();
    }

    //图片-移除分组
    public function removeImg($userId,$groupId,$imgIdArr)
    {
        $where = ['iUserId'=>$userId,'iGroupId'=>$groupId];
        return $this->db->where($where)->where_in('iDesignId',$imgIdArr)->delete(self::TABLE_T_GROUP_TEMPLATE_RELATION);
    }
    //图片-删除模拟图
    public function delDesignImg($userId,$imgIdArr)
    {
        //删除关系表
        $this->db->where(['iUserId'=>$userId])->where_in('iDesignId',$imgIdArr)->delete(self::TABLE_T_GROUP_TEMPLATE_RELATION);
        //删除模板表
        return $this->db->where_in('id',$imgIdArr)->delete(self::TABLE_T_DESIGN_TEMPLATE);

    }

    //获取用户有权限的模板站点
    public function getUserPowerTemplateSite()
    {
        $this->load->model('User_model');
        $col_power = $this->User_model->checkUserVip();
        //2d栏目
        $col = 1;
        $user_type = in_array($col, $col_power) ? "VIP" : "GENERAL";
        $rs = $this->db->select('id,iTplSite,template_2d')->from('pop136.fashion_user')->where(['id'=>getUserId(1)])->get()->result_array();

        $template = $rs[0]['template_2d'];
        if($user_type=='GENERAL'){
            $template_2d = '';
            if(!empty($template)){
                $template_2d = implode(',',array_keys(json_decode($template,true)));
            }
            return $template_2d;
        }elseif(in_array($user_type,['VIP','TRIAL'])){
            return !empty($template) ? $template : '';
        }

    }

    //保存分享设置
    public function saveMyShareSet($userId, $groupId, $isEncrypt, $expireDay)
    {

        $data = [
            'iUserId' => $userId,
            'iGroupId' => $groupId,
            'iIsEncrypt' => $isEncrypt,
            'iExpireDay' => $expireDay,//有效期天数 1：1天；7:7天；30:1个月；0：永久
        ];

        $this->db->insert(self::TABLE_T_SHARE_GROUP, $data);
        $insert_id = $this->db->insert_id();


        //生成6位随机邀请码
        $key = '';
        $pattern = '1234567890abcdefghijkmnpqrstuvwxyz';        //无 l o
        for ($i = 0; $i < 6; $i++) {
            $key .= $pattern[mt_rand(0, strlen($pattern) - 1)];
        }

        $token = base64_encode($groupId . '_' . POP_GLOBAL_KEYS . '_' . $insert_id.'_'.$userId);
        $shareUrl = "/virtualtryon/shareList/$token/";
        $data = [
            'sShareUrl' => $shareUrl,
            'sPwd' => $isEncrypt ? $key : '',
        ];
        $this->db->where('id',$insert_id);
        $this->db->update(self::TABLE_T_SHARE_GROUP,$data);
        $affect = $this->db->affected_rows();

        return [$shareUrl, $data['sPwd'],$insert_id];
    }

    //查询分享信息
    public function verifyShare($shareId)
    {
        $rows = $this->db->from(self::TABLE_T_SHARE_GROUP)->where('id',$shareId)->get()->result_array();
        return $rows;
    }

    //保存制版间
    public function saveDesignImg($userId, $templateId, $renderUrl, $renderInfo)
    {
        $table = self::TABLE_T_DESIGN_TEMPLATE;
        $data = [
            'iUserId' => $userId,
            'iTemplateId' => $templateId,
            'sTemplateRender' => $renderUrl,
            'sTemplateSvgFile' => $renderInfo,
            'dCreateTime' => date('Y-m-d H:i:s')
        ];
        $this->db->insert($table,$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;

    }

    //判断分组中是否已存在此效果图,没有则插入
    public function isExistGroupDesignId($userId, $newGroupId, $imgIdArr)
    {
        $table = self::TABLE_T_GROUP_TEMPLATE_RELATION;
        $result = $this->db->where(['iUserId' => $userId, 'iGroupId' => $newGroupId])->from($table)->get()->result_array();
        $iDesignId =$data= [];
        foreach ($result as $value) {
            $iDesignId[] = $value['iDesignId'];
        }
        //取差集，获取不在分组中的模拟图id插入
        $iDesignId = array_diff(array_unique($imgIdArr), array_filter($iDesignId));
        foreach ($iDesignId as $id){
            $data[] = [
                'iUserId' => $userId,
                'iDesignId' => $id,
                'iGroupId' => $newGroupId,
                'dCreateTime' => date('Y-m-d H:i:s'),
            ];
        }
        if(!empty($data)) {
            $this->db->insert_batch($table, $data);
            return $this->db->insert_id();
        }
        return true;
    }

    //保存普通用户选择的模板站点-多选
    public function saveTplSite($userId,$tplSiteArr)
    {
        $table = 'pop136.fashion_user';
        $template_site = [];
        foreach ($tplSiteArr as $site){
            $template_site[$site] = [];
        }
        $tplSite = !empty($tplSiteArr) ? json_encode($template_site) : '';
        $this->db->where(['id'=>$userId])->update($table,['template_2d'=>$tplSite]);
        return $this->db->affected_rows();
    }


}