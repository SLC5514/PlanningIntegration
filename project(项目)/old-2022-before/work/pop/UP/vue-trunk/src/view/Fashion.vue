<template>
    <div class="fashion">
      <router-view v-if="$route.path == '/fashion/fashion_compile' || $route.path == '/fashion/fashion_add'"/>
      <el-container v-else>
        <el-header>
          <h1 class="title">今日时尚-文章列表</h1>
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
            <el-form-item label="栏目">
              <el-select v-model="searchForm.type" placeholder="选择栏目">
                <el-option v-for="(v, i) in typeArr" :key="i" :label="v.name" :value="v.type"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="来源">
              <el-input v-model="searchForm.origin" placeholder="来源" maxlength="10"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button @click="onSearch">搜索</el-button>
              <el-button type="primary" @click="onAddArticle">新增</el-button>
            </el-form-item>
          </el-form>
        </el-header>
        <el-table
          v-loading="loading"
          :data="articleData"
          border
          height="100%">
          <el-table-column
            fixed
            prop="id"
            label="ID"
            align="center"
            width="80">
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="title"
            align="center"
            label="文章标题"
            width="120">
          </el-table-column>
          <el-table-column
            prop="type"
            align="center"
            label="栏目"
            :formatter="typeFormatter">
          </el-table-column>
          <el-table-column
            prop="origin"
            align="center"
            label="来源">
          </el-table-column>
          <el-table-column
            prop="views"
            align="center"
            label="阅读量">
          </el-table-column>
          <el-table-column
            prop="status"
            align="center"
            label="状态"
            :formatter="stateFormatter">
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
            sortable
            :sort-method="sortFn"
            prop="sort"
            label="排序"
            align="center"
            width="80">
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
  name: "Fashion",
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
        end_time: '',
        type: '',
        origin: ''
      },
      articleData: [],
      total: 0,
      typeArr: [
        {
          type: '0',
          name: '全部'
        },
        {
          type: '1',
          name: '时尚头条'
        },
        {
          type: '2',
          name: '流行动态'
        },
        {
          type: '3',
          name: '信息荟萃'
        },
        {
          type: '4',
          name: '朝闻天下'
        }
      ]
    }
  },
  created() {
    this.getArticleList();
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
      this.getArticleList(this.searchForm);
    },
    onAddArticle() {//新增
      console.log('onAddArticle!');
      this.$router.push({
        name: 'fashion_add'
      })
    },
    onCompileRow(scope) {//编辑
      console.log('onCompileRow!');
      this.$router.push({
        name: 'fashion_compile',
        query: {
          id: scope.row.id
        }
      })
    },
    handleCurrentChange(val) {//选择页数
      console.log(`当前页: ${val}`);
      this.searchForm.page = val;
      this.getArticleList(this.searchType ? this.searchForm : {page: val});
    },
    typeFormatter(row) {//格式化栏目
      switch (row.type) {
        case '1':
          return '时尚头条';
        break;
        case '2':
          return '流行动态';
        break;
        case '3':
          return '信息荟萃';
        break;
        case '4':
          return '朝闻天下';
        break;
        default: break;
      }
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
    getArticleList(data) {// 获取文章列表
      this.loading = true;
      this.$get('/article/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '文章列表获取失败！');
        } else {
          this.STATIC_URL = response.info.STATIC_URL;
          this.articleData = response.data;
          this.total = response.info.total;
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：文章列表获取失败！');
      })
    },
    sortFn(a, b) {// 排序
      return a - b;
    }
  },
  watch: {
    '$route' (to, from) {
      if (to.name == 'fashion' && (from.name == 'fashion_compile' || from.name == 'fashion_add') && to.params.load) {
        console.log('load');
        this.getArticleList(this.searchType && this.searchForm);
      }
    }
  }
};
</script>

<style scoped lang="less">
  .fashion {
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
