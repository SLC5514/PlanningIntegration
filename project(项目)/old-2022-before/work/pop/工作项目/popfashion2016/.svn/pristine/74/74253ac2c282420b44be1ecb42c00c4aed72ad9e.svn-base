<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Test extends POP_Controller
{

    public function score()
    {

        $keyword = '趋势男';
        $arCond = array(
            'iColumnId' => 1,
            'dCreateTime' => '[2013-10-01T00:00:00Z TO 2019-07-12T07:16:26Z]',
        );
        $result = array();

        $offset = 0;
        $limit = 10;

        // $arSort = array(
        //     'dCreateTime' => 'DESC',
        //     'pri_id' => 'DESC',
        // );

        $arSort = [];

        $params = array(
            // 指定返回的响应结果集中包含哪些域，注意：指定返回的域必须是stored=true或者docValues=true
            'fl' => array(
                'pop_id',
                'score',
                'combine'
            ),
            'fq' => array(), // 即filter query的缩写，表示对查询结果集再次发起一次过滤查询
            'defType' => 'edismax', // 指定Query Parser 查询解析器类型 默认solr标准解析器 dismax|edismax

            // 即query field的缩写，用于指定在哪个域上执行查询，若此参数未指定，
            // 那么默认取df（ps.用于指定查询的默认域，当查询文本未指定查询域，那么会使用默认域，你同样可以在schema.xml中配置默认域）参数
            // eg: qf="fieldOne^2.3 fieldTwo fieldThree^0.4"
            'qf' => 'combine',
            'tie' => 0.1,// 取值范围[0,1}当值为1，就是取每个字条件查询的总评分作为最后得分，值为0，就是取每个之条件查询的最大评分作为最后得分，一般情况推荐设为0.1更有用
            // 'bq' => 'date:[NOW/DAY-1YEAR TO NOW/DAY]', // 当你希望对某部分索引文档加权，比如希望最近一年内发布的数据靠前显示

            // 'debug' => 'true' // (all|true)|query|timing|results  query、返回查询解析器相关信息，timing返回查询各个阶段的耗时情况，results返回查询的执行计划相关信息
        );


        POPSearch::wrapQueryPopFashionMerger($keyword, $arCond, $result, $offset, $limit, $arSort, $params);

        var_dump($result);
    }


    public function index()
    {
        $this->load->config('solr');
        $config = config_item('solr');

        $client = new Solarium\Client($config);
        $query = $client->createSelect();

        $edismax = $query->getEDisMax();
        // $edismax->setTie(0.1)
        //     ->setQueryFields('combine');

        $query->setFields([
            'pop_id', 'score'
        ]);

        $query->addParam('debug', 'true');

        $query->createFilterQuery('iColumnId:1')->setQuery('combine:中国');

        $resultset = $client->select($query);

        var_dump($resultset);


        echo 'NumFound: ' . $resultset->getNumFound();


    }

}