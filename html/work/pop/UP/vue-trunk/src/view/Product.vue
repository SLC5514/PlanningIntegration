<template>
    <div class="product">
      <router-view v-if="$route.path == '/product/product_compile' || $route.path == '/product/product_add'"/>
      <el-container v-else>
        <el-header>
          <h1 class="title">产品管理</h1>
          <el-form :inline="true" :model="searchForm">
            <el-form-item label="选择查询时间">
              <el-date-picker
                v-model="searchtime"
                type="datetimerange"
                @change="timeChange"
                :picker-options="pickerOptions"
                value-format="yyyy-MM-dd HH:mm"
                format="yyyy-MM-dd HH:mm"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                :default-time="['09:00:00', '18:00:00']">
              </el-date-picker>
            </el-form-item>
            <el-form-item>
              <el-button @click="onSearch">搜索</el-button>
              <el-button type="primary" @click="onNewlyIncreased">新增</el-button>
            </el-form-item>
          </el-form>
        </el-header>
        <el-table
          v-loading="loading"
          :data="productData"
          border
          height="100%">
          <el-table-column
            fixed
            sortable
            prop="sort"
            label="排序"
            align="center"
            width="80">
          </el-table-column>
          <el-table-column
            prop="id"
            label="商品ID"
            align="center">
          </el-table-column>
          <el-table-column
            prop="name"
            label="产品名称">
          </el-table-column>
          <el-table-column
            prop="status"
            label="产品状态"
            :formatter="stateFormatter">
          </el-table-column>
          <el-table-column
            prop="cover"
            label="产品图片"
            width="120">
            <template slot-scope="scope">
              <div class="product-picture">
                <img :src="STATIC_URL + productData[scope.$index].cover" alt="">
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="start_time"
            label="上架时间"
            min-width="160">
          </el-table-column>
          <el-table-column
            prop="end_time"
            label="下架时间"
            min-width="160">
          </el-table-column>
          <el-table-column
            prop="create_time"
            label="创建时间"
            min-width="160">
          </el-table-column>
          <el-table-column
            fixed="right"
            label="操作"
            align="center"
            width="100">
            <template slot-scope="scope">
              <el-button
                @click.native.prevent="onCompileRow(scope)"
                type="text"
                size="small">
                编辑
              </el-button>
              <el-button
                @click.native.prevent="onChangeStateRow(scope)"
                type="text"
                size="small">
                {{scope.row.state == 0 ? "上架" : 
                  (scope.row.state == 1) ? "下架" : ""}}
              </el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="staff-pagination" v-if="Math.floor(total/20)">
          <el-pagination
            background
            @current-change="handleCurrentChange"
            layout="prev, pager, next"
            :page-size="20"
            :current-page="searchForm.page"
            :total="total">
          </el-pagination>
        </div>
      </el-container>
    </div>
</template>

<script>
export default {
  name: "Product",
  data() {
    return {
      loading: false,
      STATIC_URL: '',
      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      searchType: false,
      searchtime: '',
      searchForm: {
        page: 1,
        start_time: '',
        end_time: ''
      },
      productData: [],
      total: 0,
    }
  },
  created() {
    this.getProductList();
  },
  methods: {
    timeChange(val) {// 选择时间
      console.log('changeTime!');
      this.searchForm.start_time = val && val[0] + ':' + new Date().getSeconds();
      this.searchForm.end_time = val && val[1] + ':' + new Date().getSeconds();
    },
    onSearch() {//搜索
      console.log('onSearch!');
      this.searchType = true;
      this.searchForm.page = 1;
      this.getProductList(this.searchType && this.searchForm);
    },
    onNewlyIncreased() {//新增
      console.log('onNewlyIncreased!');
      this.$router.push({
        name: 'product_add'
      })
    },
    onCompileRow(scope) {//编辑
      console.log('onCompileRow!');
      this.$router.push({
        name: 'product_compile',
        query: {
          id: scope.row.id
        }
      })
    },
    onChangeStateRow(scope) {//改变状态
      console.log('onCompileRow!', scope.$index);
    },
    sortChange() {
      console.log(arguments)
    },
    handleCurrentChange(val) {//选择页数
      console.log(`当前页: ${val}`);
      this.searchForm.page = val;
      this.getProductList(this.searchType ? this.searchForm : {page: val});
    },
    stateFormatter(row) {//格式化状态
      if (row.start_time && row.end_time) {
        const start_time = new Date(row.start_time.replace(/-/g, '/')).getTime();
        const end_time = new Date(row.end_time.replace(/-/g, '/')).getTime();
        const now_time = new Date().getTime();
        if (now_time > start_time && now_time < end_time) {
          return '已上架';
        } else {
          return '已下架';
        }
      }
    },
    getProductList(data) {// 获取产品列表
      this.loading = true;
      this.$get('/product/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '产品列表获取失败！');
        } else {
          this.STATIC_URL = response.info.STATIC_URL;
          this.productData = response.data;
          this.total = response.info.total;
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：产品列表获取失败！');
      })
    },
  },
  watch: {
    '$route' (to, from) {
      if (to.name == 'product' && (from.name == 'product_compile' || from.name == 'product_add') && to.params.load) {
        console.log('load');
        this.getProductList(this.searchType && this.searchForm);
      }
    }
  }
};
</script>

<style scoped lang="less">
  .product {
    height: 100%;
    color: #303133;
    .el-container {
      height: 100%;
      .el-header {
        height: auto!important;
        padding: 0;
        .title {
          font-size: 30px;
          margin-bottom: 20px;
        }
      }
      .el-pagination {
        float: right;
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .product-picture {
        max-width: 100px;
        overflow: hidden;
        img {
          width: 100%;
        }
      }
    }
  }
</style>
