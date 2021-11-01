<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 图搜图控制器
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/2 13:39
 */

class SimilarPatterns extends POP_Controller
{
	const COL = 2;
    //用户图片上传表
    const T_FASHION_FM_UPLOAD_PIC_MATERIAL = '`fashion`.`fm_upload_pic_material`';
    //图案区域表
    const T_FASHION_FM_DESIGN_AREA = '`fashion`.`fm_design_area`';

    /*------------------------------------------------------------------------------------------------------------------
    * 构造函数，权限判断
    *-----------------------------------------------------------------------------------------------------------------*/
    function __construct(){
        parent::__construct();
        $this->assign("col",self::COL);
        $this->checkPower(self::COL,"yuntu:similar");
        $this->get_use_type(self::COL);
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 切图界面
    *-----------------------------------------------------------------------------------------------------------------*/
    public function index($params="")
    {
        $paramsArr = decodeParams($params);
        $t =  $paramsArr["t"];
        $id = intval($paramsArr["id"]);
        $imgPath = urldecode( $this->input->get_post( 'path' , TRUE ) );    // 图片url
        $photo_url = parse_url( $imgPath, PHP_URL_PATH ); //去域名（有些url带域名有些不带）
        $this->setTDK("similarpatterns");
        $this->assign( 'photo_url', $photo_url ); //图片url不带域名
        $this->assign( 'tb_id', "{$t}_{$id}" );
        $this->display("similar/similar-patterns.html");
    }

    /*------------------------------------------------------------------------------------------------------------------
     * [异步接口]上传图片
     *----------------------------------------------------------------------------------------------------------------*/
    public function uploadPic()
    {
        if(empty($_FILES['userfile']["tmp_name"])){
            outPrintApiJson(1011,"参数错误上传图片不存在");
        }

        //图片上传
        $this->load->library('POP_Uploadpic');
        $savePath = '/yuntu/upload_pic_material/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';	//图片路径
        $imgPath = $this->pop_uploadpic->uploadPic($_FILES['userfile'],$savePath);

        //处理返回值
        if( $imgPath == false ){
            outPrintApiJson(1021,"上传失败，错误：".$this->pop_uploadpic->getError());
        }else{
            //插入数据库记录
            $data = array('sAccountId'=>getUserId(),'sUploadPicImg'=>$imgPath,"dCreateTime"=>date( 'Y-m-d H:i:s' ) );
            $this->db->insert( self::T_FASHION_FM_UPLOAD_PIC_MATERIAL, $data , TRUE );
            $id = $this->db->insert_id();

            $result = array(
                "imgPath" => $imgPath,
                "url" => '/similarpatterns/t_uploadpicmaterial-id_' . $id . '/?path='.urlencode($imgPath),
            );
            outPrintApiJson(0,"OK",$result);
        }
    }
    /*------------------------------------------------------------------------------------------------------------------
     * [异步接口]切图 参数 Post =>[ photo_url =>[（string）原图片路径] ，cut_pos => [（string）切图位置x,y,w,h参数如：0,0,100,100] ，tb_id =>（假表名id） ]
     *----------------------------------------------------------------------------------------------------------------*/
    public function doCutPic()
    {
        //参数处理
        $post = $this->input->get_post( NULL, TRUE );
        $source_image = preg_replace('/^http[s]?:\/\/(.*?)\//','/',$post['photo_url']);
        $cut_pos = explode( ',', $post['cut_pos'] );
        $dst_x = intval($cut_pos[0]);
        $dst_y = intval($cut_pos[1]);
        $dst_w = intval($cut_pos[2]);
        $dst_h = intval($cut_pos[3]);
		$cut_w = intval($cut_pos[4]);
		$cut_h = intval($cut_pos[5]);
        if(empty($source_image) || count($cut_pos)<4){
            outPrintApiJson(1011,"参数错误！");
        }

        //取表名ID
        $p = explode( '_' , $post['tb_id'] );
        $t = $p[0];
        $id = intval($p[1]);
        $table = getProductTableName($t);

        //切图
        $this->load->library('POP_Uploadpic');
        $path = $this->pop_uploadpic->cutPic($source_image,$dst_x,$dst_y,$dst_w,$dst_h,$cut_w,$cut_h);

        //处理返回值
        if($path){
            if(!empty($table)){
                $data = array(
                    "sAccountId" => getUserId(),
                    "sDesignAreaImg" => $path,
                    "iSourceType"=>$t == 'uploadpicmaterial' ? 2 : 1 ,
                    "sSourceID" =>"{$table}_{$id}"
                );
                $this->db->insert( self::T_FASHION_FM_DESIGN_AREA , $data, TRUE );
            }
            $result = array(
                'url'    => '/similarpatterns/similarList/?path=' . urlencode( $path ),
                'imgPath' => urlencode(STATIC_URL1 . $path),
            );
            outPrintApiJson(0,"OK",$result);
        }else{
            outPrintApiJson(1021,"切图失败，错误：".$this->pop_uploadpic->getError());
        }
    }
    public function similarList(){
        $this->setTDK("similarpatterns","similarlist");
        $this->display("similar/similar-list.html");
    }
    /*------------------------------------------------------------------------------------------------------------------
     *[异步接口]切图完成后，匹配显示图列表
     *----------------------------------------------------------------------------------------------------------------*/
    public function  getSimilarList(){

        $this->load->model("SimilarPatterns_model","SimilarPatterns");
        $path = $this->input->get_post('path', true);
        $cat = $this->input->get_post('cat', true);
        $page = $this->input->get_post('page',true);
        $pageSize = $this->input->get_post('pageSize',true);
        $page = $page<=1 ? 1:$page;
        $pageSize = empty($pageSize)?0:intval($pageSize);

        if(empty($path)){
            outPrintApiJson(1011,"参数错误！");
        }else{
            $stime=microtime(true);
            $offset = ($page-1) * $pageSize;
            //$path = "http://img2.fm.pop-fashion.com/fashion/fashion_shows/coverimage/sCoverImg_1474508481.jpg";
            // $path = "https://imgyt3.pop-fashion.com/fashion/graphic/2019082804_SH5259/mbig/194efb7f00724a4f831c236414153b62.jpg";
            $path = preg_replace('/^http[s]?:\/\/(.*?)\//','/',$path);
            $imgUrl = STATIC_URL1 . $path;
            $this->load->model('User_model');
            list($status,$total,$used) = $this->User_model->add_try_out_num("yuntu:similar",$path);
            $info = ["total"=>$total,"free"=>$total-$used];
            if($status){
                $result = $this->SimilarPatterns->getfabriclist($imgUrl,$cat,$offset,$pageSize);
                $etime=microtime(true);
                $result['usetime'] = number_format( ($etime-$stime) , 2, '.', '');
                outPrintApiJson(0,"OK",$result,$info);
            }else{
                outPrintApiJson(2001,"使用次数已用尽",[],$info);
            }
        }
    }
}