<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @todo 所有属性所在类
 * @描述 取属性值请用此方法
 */
class Attr_model extends POP_Model
{
    const POP_MEMCACHE_PREF = "FM_CLOUD_M_Attr_";   // memcache前缀
    private $MemTime = 3600;

    //可以直接用$this->sPatternUse这种方式获取数组中字段的数据
    private $fileds = [
        "Attribute",        //新属性t_dict_attribute全表数据([id=>中文名])
        "sPatternUse",      //图案应用([id=>中文名])
        "sPatternFormat",   //图案格式([id=>中文名])
        "sGender",
    ];

    //构造方法
    function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->refresh = $this->input->get_post("refresh",TRUE);
    }
    /*------------------------------------------------------------------------------------------------------------------
    * @ todo 魔术方法 可以用$this调用 fileds 中的变量（例如：$this->sPatternUse）
    * @ 描述 此方法可以自动调用函数,帮助获取需要的变量，避免重复调用函数
    *-----------------------------------------------------------------------------------------------------------------*/
    public function __get($name){
       if(in_array($name,$this->fileds)){
            if(empty($this->$name)){
                $fun = "get_".$name;
                return $this->$name=$this->$fun();
            }else{
                return $this->$name;
            }
       }else{
           return get_instance()->$name;
       }
    }

    /**********************************获取 `fashion`.`t_dict_color` 数据相关方法*********************************/
    /*------------------------------------------------------------------------------------------------------------------
     * @todo  获取t_dict_color表数据(特殊)
     * @return [array] (数据以 [id=>中文名] 键值的形式)
     *----------------------------------------------------------------------------------------------------------------*/
    public function getColorDict($colorid = 0, $format = '')
    {
        $this->load->driver('cache');
        $memcacheKey = self::POP_MEMCACHE_PREF . 't_dict_color';
        $res = $this->cache->memcached->get($memcacheKey);
        if (empty($res) || $this->input->get_post('refresh')) {
            $res = array();
            //查询颜色字典表做memcache缓存
            $sql = "SELECT * FROM `fashion`.`t_dict_color` WHERE `iStatus`=1";
            $colors = $this->query($sql);
            foreach ($colors as $color) {
                $color['sColor']=$this->rgbToColor($color['iRed'],$color['iGreen'],$color['iBlue']);
                $res[$color['id']] = $color;
            }
            $this->cache->memcached->save($memcacheKey, $res, 86400);
        }
        if (!empty($colorid)) {
            $resFormat = [];
            $resFormat['sName'] = $res[$colorid]['sName'];
            $resFormat['sAlias'] = $res[$colorid]['sColor'];
            if(!empty($res[$res[$colorid]['iPid']])){
                $resFormat['PsName'] = $res[$res[$colorid]['iPid']]['sName'];
                $resFormat['PsAlias'] = $res[$res[$colorid]['iPid']]['sColor'];
                $resFormat['Pid'] = $res[$colorid]['iPid'];
            }
        }
        return !empty($colorid) ? ($format == 'sAssortColor') ? $resFormat : $res[$colorid] : $res;
    }
    //通过RGB获取HTML色号
    private function rgbToColor( $R ,$G = -1 , $B = -1 ) {
        if ( is_array( $R ) && sizeof( $R ) == 3 ) {
            list( $R, $G, $B ) = $R;
        }
        $R = intval( $R );
        $G = intval( $G );
        $B = intval( $B );
        $R = dechex( $R < 0 ? 0 : ( $R > 255 ? 255 : $R ) );
        $G = dechex( $G < 0 ? 0 : ( $G > 255 ? 255 : $G ) );
        $B = dechex( $B < 0 ? 0 : ( $B > 255 ? 255 : $B ) );
        $COLOR  = ( strlen( $R ) < 2 ? '0' : '' ).$R;
        $COLOR .= ( strlen( $G ) < 2 ? '0' : '' ).$G;
        $COLOR .= ( strlen( $B ) < 2 ? '0' : '' ).$B;
        return '#'.$COLOR;
    }

    /**********************************获取 `fashion`.`t_dict_attribute` 数据相关方法*********************************/
    /*------------------------------------------------------------------------------------------------------------------
     * @todo  获取t_dict_attribute全表数据
     * @return [array] (数据以 [id=>中文名] 键值的形式)
     *----------------------------------------------------------------------------------------------------------------*/
    private function get_Attribute(){
        $memcacheKey = self::POP_MEMCACHE_PREF . 't_dict_attribute';
        $result = $this->cache->memcached->get($memcacheKey);
        if (empty($result) || $this->refresh == true) {
            $result = [];
            $rows = $this->db->select(["iAttributeId","sName","sEnName"])->where(["iDisplay"=>1])
             ->order_by('iAttributeId DESC')->get("`fashion`.`t_dict_attribute`")->result_array();
            foreach ($rows as $val) {
                $result[ $val['iAttributeId'] ] = $val['sName'];
            }
            $this->cache->memcached->save($memcacheKey, $result, $this->MemTime);
        }
        return $result;
    }
    private function get_sGender(){
        return GetCategory::getGender();
    }
    /**************************************获取 `fashion`.`category` 数据相关方法*************************************/
    /*------------------------------------------------------------------------------------------------------------------
     *  图案应用
     *----------------------------------------------------------------------------------------------------------------*/
    private function get_sPatternUse(){
        return $this->getCategoryData(14101);
    }

    /*------------------------------------------------------------------------------------------------------------------
     *  获取图案格式（高清、矢量、位图）
     *----------------------------------------------------------------------------------------------------------------*/
    private function get_sPatternFormat(){
        return $this->getCategoryData(10959);
    }

    /*------------------------------------------------------------------------------------------------------------------
     * @todo  通过 parent_id 获取`fashion`.`category`表数据
     * @param  $parent_id  [int]
     * @return [array] (数据以 [id=>中文名] 键值的形式)
     *----------------------------------------------------------------------------------------------------------------*/
    private function getCategoryData($parent_id){
        $memcacheKey = self::POP_MEMCACHE_PREF . 'category_'.$parent_id;
        $result = $this->cache->memcached->get($memcacheKey);
        if (empty($result) || $this->refresh == true) {
            $result = [];
            $rows = $this->db->select(["cat_id","cat_name"])->where(["parent_id"=>$parent_id,"is_show"=>1])
                ->order_by('orderby DESC')->get("`fashion`.`category`")->result_array();
            foreach ($rows as $val) {
                $result[ $val['cat_id'] ] = $val['cat_name'];
            }
            $this->cache->memcached->save($memcacheKey, $result, $this->MemTime);
        }
        return $result;
    }
}