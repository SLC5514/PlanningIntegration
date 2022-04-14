<template>
    <div class="business">
      <el-container>
        <el-header>
          <h1 class="title">客户表单</h1>
          <el-form :inline="true" :model="searchForm">
            <el-form-item label="选择查询时间">
              <el-date-picker
                v-model="searchTime"
                type="datetimerange"
                :picker-options="pickerOptions"
                @change="timeChange"
                value-format="yyyy-MM-dd HH:mm"
                format="yyyy-MM-dd HH:mm"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                :default-time="['09:00:00', '18:00:00']">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="员工姓名">
              <el-input v-model="searchForm.staff_name" placeholder="员工姓名"></el-input>
            </el-form-item>
            <el-form-item label="微信uuid">
              <el-input v-model="searchForm.wechat_openid" placeholder="微信uuid"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button @click="onSearch">搜索</el-button>
              <el-button type="primary" @click="onDerive">导出</el-button>
            </el-form-item>
          </el-form>
        </el-header>
        <el-table
          v-loading="loading"
          :data="customerData"
          border
          height="100%">
          <el-table-column
            fixed
            prop="id"
            label="序号"
            align="center"
            width="50">
          </el-table-column>
          <el-table-column
            prop="create_time"
            label="时间"
            align="center"
            width="160">
          </el-table-column>
          <el-table-column
            prop="staff_name"
            label="员工姓名"
            align="center"
            width="100">
          </el-table-column>
          <el-table-column
            prop="wechat_openid"
            align="center"
            label="微信uuid">
          </el-table-column>
          <el-table-column
            prop="wechat_nickname"
            label="微信昵称"
            align="center"
            width="100">
          </el-table-column>
          <el-table-column
            prop="name"
            label="姓名"
            align="center"
            width="100">
          </el-table-column>
          <el-table-column
            prop="telephone"
            label="电话"
            align="center"
            width="120">
          </el-table-column>
          <el-table-column
            prop="position"
            label="职位"
            align="center"
            width="100">
          </el-table-column>
          <el-table-column
            prop="company"
            align="center"
            label="公司名称">
          </el-table-column>
          <el-table-column
            prop="interested_product"
            align="center"
            label="感兴趣的产品">
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
  name: "Business",
  data() {
    return {
      loading: false,
      searchType: false,
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
      searchTime: [],
      searchForm: {
        page: 1,
        staff_name: '',
        wechat_openid: '',
        start_time: '',
        end_time: ''
      },
      customerData: [],
      total: 0,
    }
  },
  created() {
    this.getCustomerList();
  },
  methods: {
    timeChange(val) {// 选择时间
      console.log('changeTime!');
      this.searchForm.start_time = val && val[0] + ':' + new Date().getSeconds();
      this.searchForm.end_time = val && val[1] + ':' + new Date().getSeconds();
    },
    onSearch() {// 搜索
      console.log('onSearch!');
      this.searchType = true;
      this.searchForm.page = 1;
      this.getCustomerList(this.searchType && this.searchForm);
    },
    onDerive() {// 导出
      console.log('onDerive!');
      let searchStr = '';
      Object.keys(this.searchForm).forEach((v, i) => {
        searchStr += (i === 0 ? '?' : '&') + v + '=' +  this.searchForm[v];
      })
      window.location.href = this.$productUploadImg + '/customer/export/' + searchStr;
    },
    handleCurrentChange(val) {// 选择页数
      console.log(`当前页: ${val}`);
      this.searchForm.page = val;
      this.getCustomerList(this.searchType ? this.searchForm : {page: val});
    },
    getCustomerList(data) {// 获取客户表单列表
      this.loading = true;
      this.$get('/customer/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '客户表单列表获取失败！');
        } else {
          this.customerData = response.data;
          this.total = response.info.total;
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：客户表单列表获取失败！');
      })
    }
  }
};
</script>

<style scoped lang="less">
  .business {
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
    }
  }
</style>
