<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class POP_MakePage
{
	/**页码**/
	public $pageNo = 1;
	/**页大小**/
	public $pageSize = 20;
	/**共多少页**/
	public $pageCount = 0;
	/**总记录数**/
	public $totalNum = 0;
	/**偏移量,当前页起始行**/
	public $offSet = 0;

	/**是否有上一页**/
	public $hasPrePage = true;
	/**是否有下一页**/
	public $hasNextPage = true;

	public $url ='#';
	public $urlsuffix = '/';
	/**
	 *
	 * @param unknown_type $count 总行数
	 * @param unknown_type $size 分页大小
	 * @param unknown_type $string
	 */
	public function __construct($params){

		$this->totalNum = $params['count'];//总记录数
		$this->pageSize = $params['size'];//每页大小
		$this->pageNo = $params['pageNo'];
		//计算总页数
		$this->pageCount = ceil($this->totalNum/$this->pageSize);
		$this->pageCount = ($this->pageCount<=0)?1:$this->pageCount;
		//检查pageNo
		$this->pageNo = $this->pageNo == 0 ? 1 : $this->pageNo;
		$this->pageNo = $this->pageNo > $this->pageCount? $this->pageCount : $this->pageNo;

		//计算偏移
		$this->offset = ( $this->pageNo - 1 ) * $this->pageSize;
		//计算是否有上一页下一页
		$this->hasPrePage = $this->pageNo == 1 ?false:true;
		$this->hasNextPage = $this->pageNo >= $this->pageCount ?false:true;
		$this->url = $params['url'];

	}
	/**
	 * 分页算法
	 */
	private function generatePageList(){
		$pageList = array();
		if($this->pageCount <= 5){
			for($i=0;$i<$this->pageCount;$i++){
				array_push($pageList,$i+1);
			}
		}else{
			if($this->pageNo <= 4){
				for($i=0;$i<5;$i++){
					array_push($pageList,$i+1);
				}
				array_push($pageList,-1);
				array_push($pageList,$this->pageCount);

			}else if($this->pageNo > $this->pageCount - 4){
				array_push($pageList,1);

				array_push($pageList,-1);
				for($i=5;$i>0;$i--){
					array_push($pageList,$this->pageCount - $i+1);
				}
			}else if($this->pageNo > 4 && $this->pageNo <= $this->pageCount - 4){
				array_push($pageList,1);
				array_push($pageList,-1);

				array_push($pageList,$this->pageNo -2);
				array_push($pageList,$this->pageNo -1);
				array_push($pageList,$this->pageNo);
				array_push($pageList,$this->pageNo + 1);
				array_push($pageList,$this->pageNo + 2);

				array_push($pageList,-1);
				array_push($pageList,$this->pageCount);

			}
		}
		return $pageList;
	}

	/***
	 * 创建分页控件
	 * @param
	 * @return String
	 */
	public function echoPageAsDiv(){
		$pageList = $this->generatePageList();

		$pageString ="<div class='pages'>";

		if(!empty($pageList)){
			if($this->pageCount >1){
				$keyword = get_instance()->input->get_post('key', TRUE);
				$keywords = $keyword ? '?key='.rawurlencode(rawurlencode($keyword)) : '';
				if($this->hasPrePage){
					$pageString = $pageString ."<a class='prevPage' href=\"" .$this->url . "page_" . ($this->pageNo-1) . $this->urlsuffix . $keywords . "#anchor\"></a>";
				}
				foreach ($pageList as $k=>$p){
					if($this->pageNo == $p){
						$pageString = $pageString ."<span class='hov'>" . $this->pageNo . "</span>";
						continue;
					}
					if($p == -1){
						$pageString = $pageString ."<span class='ellipsis' style='border:0'>...</span>";
						continue;
					}
					$pageString = $pageString ."<a href=\"" .$this->url . "page_" . $p . $this->urlsuffix . $keywords . "#anchor\">" . $p . "</a>";
				}

				if($this->hasNextPage){
					$pageString = $pageString ."<a class='nextPage' href=\"" .$this->url . "page_" . ($this->pageNo+1) . $this->urlsuffix . $keywords . "#anchor\"></a>";
				}
				$pageString = $pageString."<input id='_pageCount' type='hidden' value='".$this->pageCount."'/>";
				$pageString = $pageString."<input id=\"J_GoPage\" type=\"text\" value=\" ".$this->pageNo." \" onkeyup=\"this.value=this.value.replace(/[^0-9-]+/,'');\">";
				$pageString = $pageString."<a class=\"goBtn\" href=\"javascript:void(0);\" id=\"goBtn\" data=\"".$this->url."\" search=\"".$keywords."#anchor\">跳转</a>";
			}
		}
		$pageString = $pageString .("</div>");
		return $pageString;
	}
	/***
	 * 创建分页控件
	 * @param
	 * @return String   < 1/207 >
	 */
	public function echoSimplePageAsDiv(){

		$pageString ="<div class='btn_page'>";
		if($this->pageCount >1){
			$keyword = get_instance()->input->get_post('key', TRUE);
			$keywords = $keyword ? '?key='.rawurlencode(rawurlencode($keyword)) : '';
			if ($this->pageNo <= 1) {
				$pageString .= "<a class='leftBtn neiyeList' href='javascript:void(0);'></a>";
			}else {
				$pageString .= "<a class='leftBtn neiyeList' href=\"" .$this->url . "page_" . ($this->pageNo-1) . $this->urlsuffix . $keywords . "#anchor\"></a>";
			}			
			$pageString .= "<span class='pageN'>" . $this->pageNo . "</span>";
			$pageString .= "/";
			$pageString .= "<span class='totalN' title='".$this->pageCount."'>" .$this->pageCount ."</span>";
			if ($this->pageNo >= $this->pageCount) {
				$pageString .= "<a class='rightBtn neiyeList' href='javascript:void(0);'></a>";
			}else {
				$pageString .= "<a class='rightBtn neiyeList' href=\"" .$this->url . "page_" . ($this->pageNo+1) . $this->urlsuffix . $keywords . "#anchor\"></a>";
			}			
		}
		$pageString .= "</div>";
		return $pageString;
	}
}