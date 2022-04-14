<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
  + QQ资讯服务
*/

class Service extends POP_Controller
{
    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 公司地图上的地理位置
     */
    public function address()
    {
        $this->display('service/address.html');
    }

    /**
     * 售前
     */
    public function presales()
    {
        $this->assign('sales', 'pre');
        $this->display('sales.html');
    }

    /**
     * 售后
     */
    public function aftersales()
    {
        $this->assign('sales', 'after');
        $this->display('sales.html');
    }

    /**
     * 关于我们
     */
    public function aboutus()
    {
        $seo = $this->getSeoArray('aboutus');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/about_us.html');
    }

    /**
     * 加入会员
     */
    public function joinmember()
    {
        $seo = $this->getSeoArray('joinmember');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/join_member.html');
    }

    /**
     * 会员须知
     */
    public function membernotice()
    {
        $seo = $this->getSeoArray('membernotice');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/member_notice.html');
    }

    /**
     * 付款方式
     */
    public function payment()
    {
        $seo = $this->getSeoArray('payment');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/payment.html');
    }

    /**
     * 收费一览
     */
    public function tolllist()
    {
        $seo = $this->getSeoArray('tolllist');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/toll_list.html');
    }

    /**
     * 联系我们
     */
    public function contactUs()
    {
        $seo = $this->getSeoArray('contact_us');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/contact_us.html');
    }

    /**
     * 法律声明
     */
    public function declaration()
    {
        $seo = $this->getSeoArray('declaration');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/legal_declaration.html');
    }

    /**
     * 我们的用户
     */
    public function ouruser()
    {
        $seo = $this->getSeoArray('ouruser');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/our_user.html');
    }

    /**
     * 我们的产品
     */
    public function ourproducts()
    {
        $seo = $this->getSeoArray('ourproducts');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display('service/our_products.html');
    }

    /**
     * 网站地图
     */
    public function sitemap()
    {
        //TDK暂定
        $seo = $this->getSeoArray('sitemap');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display("service/sitemap.html");
    }

    /**
     * 友情链接
     */
    public function friendlylink()
    {
        $this->load->model('FriendlyLink_model');
        $aFriendlyLink = $this->FriendlyLink_model->getFriendlyLinkLists();
        $aFriendlyLink = array_chunk($aFriendlyLink, 2);
        $this->assign('aFriendlyLink', $aFriendlyLink);
        //TDK暂定
        $seo = $this->getSeoArray('friendlylink');
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
        $this->display("service/friend_link.html");
    }

    /**
     * 获取TKD
     * @param string $entry
     *
     * @return array
     */
    public function getSeoArray($entry = '')
    {

        $title = $keywords = $description = '';

        switch ($entry) {
            case 'contact_us':
                $title = '联系我们-POP服装趋势网';
                $keywords = '';
                $description = 'POP服装趋势网是国内专业的高端服装设计资源网站，专为设计提供独立设计师作品，时装周高清图片和时尚杂志书籍，包括趋势，色彩，面料，图案，款式，灵感，主题，廓形等多方面内容，为设计提供最前沿的服装资讯。请联系我们！';
                break;
            case 'aboutus':
                $title = '关于我们-POP服装趋势网';
                $keywords = '关于我们,关于POP';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和关于我们上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'ouruser':
                $title = '我们的用户-POP服装趋势网';
                $keywords = '我们的用户,POP用户';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和我们的用户上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'ourproducts':
                $title = '我们的产品-POP服装趋势网';
                $keywords = '我们的产品,POP产品';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和我们的产品上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'joinmember':
                $title = '加入会员-POP服装趋势网';
                $keywords = '加入会员,免费会员,VIP会员';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和加入会员上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'membernotice':
                $title = '会员须知-POP服装趋势网';
                $keywords = '会员须知,接受条款,注册义务';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和会员须知上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'tolllist':
                $title = '收费一览-POP服装趋势网';
                $keywords = '收费一览,会员类型,会员权限,收费价格';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和收费一览上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'payment':
                $title = '付款方式-POP服装趋势网';
                $keywords = '付款方式,网上支付,对公汇款,对公转账';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和付款方式上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'declaration':
                $title = '法律声明-POP服装趋势网';
                $keywords = '法律声明,版权,免责条款,隐私声明';
                $description = 'POP服装趋势是逸尚云联成立于2004年，首家“互联网时尚设计供应链”上市公司，网站方面在资讯和法律声明上全方位提供了情报分析、海量素材及详细阐述，在功能上致力打造趋势、款式、面料——所见即所得的“服饰研发必备平台”。';
                break;
            case 'sitemap':
                $title = '网站地图-POP服装趋势网';
                $keywords = '网站地图,网站导航,sitemap';
                $description = 'POP服装趋势的网站地图，详细诠释了POP全站的导航模块，为客服方便查询POP服装趋势内容提供了便捷的查询通道。';
                break;
            case 'friendlylink':
                $title = '友情链接-POP服装趋势网';
                $keywords = '友情链接,friendlylink';
                $description = 'POP服装趋势的友情链接。';
                break;
        }

        return ['title' => $title, 'keywords' => $keywords, 'description' => $description];
    }
}