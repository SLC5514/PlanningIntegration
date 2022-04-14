<template>
    <div class="account">
      <el-container>
        <el-header>
          <h1 class="title">账号管理</h1>
          <el-form :inline="true" :model="searchForm">
            <el-form-item label="员工姓名">
              <el-input v-model="searchForm.staff_name" placeholder="员工姓名"></el-input>
            </el-form-item>
            <el-form-item label="工号">
              <el-input v-model="searchForm.job_number" placeholder="工号"></el-input>
            </el-form-item>
            <el-form-item label="部门">
              <el-select v-model="searchForm.department" placeholder="部门">
                <el-option v-for="(v, i) in departmentArr" :key="i" :label="v" :value="v"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="状态">
              <el-select v-model="searchForm.status" placeholder="状态">
                <el-option v-for="(v, i) in stateArr" :key="i" :label="v.name" :value="v.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button @click="onSearch">搜索</el-button>
              <el-button type="primary" @click="onAddStaff">新增员工</el-button>
            </el-form-item>
          </el-form>
        </el-header>
        <el-table
          v-loading="loading"
          :data="staffData"
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
            prop="staff_name"
            align="center"
            label="员工姓名"
            width="100">
          </el-table-column>
          <el-table-column
            prop="job_number"
            align="center"
            label="工号"
            width="100">
          </el-table-column>
          <el-table-column
            prop="company_address"
            align="center"
            label="公司地址">
          </el-table-column>
          <el-table-column
            prop="department"
            align="center"
            label="部门"
            width="100">
          </el-table-column>
          <el-table-column
            prop="position"
            align="center"
            label="职位"
            width="100">
          </el-table-column>
          <el-table-column
            prop="wechat_openid"
            align="center"
            label="微信uuid">
          </el-table-column>
          <el-table-column
            prop="wechat_nickname"
            align="center"
            label="微信昵称"
            width="100">
          </el-table-column>
          <el-table-column
            prop="status"
            label="状态"
            align="center"
            width="100"
            :formatter="stateFormatter">
          </el-table-column>
          <el-table-column
            fixed="right"
            label="操作"
            header-align="center"
            width="90">
            <template slot-scope="scope">
              <el-button
                @click.native.prevent="onCompileRow(scope)"
                type="text"
                size="small"
                v-if="scope.row.status != 2">
                {{(scope.row.status == 0) || (scope.row.status == 1) ? '编辑' : ''}}
              </el-button>
              <el-button
                @click.native.prevent="onChangeStateRow(scope)"
                type="text"
                size="small"
                v-if="scope.row.status != 2">
                {{scope.row.status == 0 ? "启用" : 
                  (scope.row.status == 1) ? "停用" : ""}}
              </el-button>
              <el-button
                @click.native.prevent="onAuditRow(scope)"
                type="text"
                size="small"
                v-if="scope.row.status == 2">
                审核
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
      <CodePopUp
        v-if="codePopUpType"
        :codePopUpType="codePopUpType"
        :codePopUpData="codePopUpData"
        :onCodePopUpClose="() => codePopUpType = false"/>
      <MsgPopUp
        v-if="msgPopUpType"
        :msgPopUpType="msgPopUpType"
        :msgPopUpData="msgPopUpData"
        :departmentArr="departmentArr"
        :changeHeadPortrait="onChangeHeadPortrait"
        :weChatReplace="onWeChatReplace"
        :onMsgBtnClick="msgPopUpData.title == '信息编辑' ? onCompileSave : onAuditPass"
        :onMsgPopUpClose="() => msgPopUpType = false"/>
    </div>
</template>

<script>
  export default {
    name: "Account",
    components: {
      CodePopUp: () => import('../components/CodePopUp.vue'),
      MsgPopUp: () => import('../components/MsgPopUp.vue')
    },
    data() {
      return {
        loading: false,
        searchType: false,
        searchForm: {
          staff_name: '',
          job_number: '',
          department: '',
          status: '',
          page: 1
        },
        departmentArr: [],
        stateArr: [
          {
            id: '2',
            name: '待审核'
          },{
            id: '0',
            name: '停用'
          },{
            id: '1',
            name: '启用'
          }
        ],
        staffData: [],
        codePopUpType: false,
        codePopUpData: {},
        addStaffData: {
          title: '新增员工账号',
          codesrc: '../assets/add_staff.png',
          content: ['请将该码截图给销售人员扫码补充信息，',
            '信息录入成功后，在账号管理页面，',
            '管理员对其审核通过后，销售可开始启用自己的推广账号']
        },
        weChatReplaceData: {
          title: '微信绑定替换',
          codesrc: '',
          content: ['每个员工二维码不同']
        },
        msgPopUpType: false,
        msgPopUpData: {},
        nowMsgPopUpIdx: 0,
        total: 0
      }
    },
    created() {
      this.getDepartments();
      this.getStaffList();
    },
    methods: {
      onSearch() {// 搜索
        console.log('onSearch!');
        this.searchType = true;
        this.searchForm.page = 1;
        this.getStaffList(this.searchType && this.searchForm);
      },
      onAddStaff() {// 新增员工
        console.log('onAddStaff!');
        this.codePopUpData = this.addStaffData;
        this.codePopUpType = true;
      },
      onCompileRow(scope) {// 编辑
        console.log('onCompileRow');
        this.nowMsgPopUpIdx = scope.$index;
        this.msgPopUpData = {title: '信息编辑', btnTxt: '保存', btnMsg: '保存成功后，1小时后生效', id: scope.row.id};
        this.msgPopUpType = true;
      },
      onAuditRow(scope) {// 审核
        console.log('onAuditRow', scope.$index);
        this.nowMsgPopUpIdx = scope.$index;
        this.msgPopUpData = Object.assign({title: '审核信息', btnTxt: '审核通过', btnMsg: '审核通过后，该推广账号将立即启用'}, scope.row);
        this.msgPopUpType = true;
      },
      onChangeStateRow(scope) {// 改变状态
        console.log('onChangeStateRow', scope.row.status);
        this.$confirm('此操作将改变状态, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.changeStatus({
            staff_id: scope.row.id,
            status: scope.row.status == 0 ? 1 : (scope.row.status == 1 ? 0 : (scope.row.status == 2 ? 1 : -1))
          });
        });
      },
      onChangeHeadPortrait() {// 更换图片
        console.log('onChangeHeadPortrait');
      },
      onWeChatReplace() {// 重新绑定
        console.log('onWeChatReplace');
        this.reBindWxcode({ staff_id: this.msgPopUpData.id });
      },
      onCompileSave(data) {// 编辑保存
        console.log('onCompileSave');
        this.doEdit(data);
      },
      onAuditPass(data) {// 审核通过
        console.log('onAuditPass');
        this.doEdit(Object.assign(data, { status: this.staffData[this.nowMsgPopUpIdx].status == 0 ? 1 : (this.staffData[this.nowMsgPopUpIdx].status == 1 ? 0 : (this.staffData[this.nowMsgPopUpIdx].status == 2 ? 1 : -1)) }));
        // this.changeStatus({
        //   staff_id: this.staffData[this.nowMsgPopUpIdx].id,
        //   status: this.staffData[this.nowMsgPopUpIdx].status == 0 ? 1 : (this.staffData[this.nowMsgPopUpIdx].status == 1 ? 0 : (this.staffData[this.nowMsgPopUpIdx].status == 2 ? 1 : -1))
        // });
      },
      handleCurrentChange(val) {// 选择页数
        console.log(`当前页: ${val}`);
        this.searchForm.page = val;
        this.getStaffList(this.searchType ? this.searchForm : {page: val});
      },
      stateFormatter(row) {// 格式化状态
        switch (row.status) {
          case '0': return '停用'; break;
          case '1': return '启用'; break;
          case '2': return '待审核'; break;
          default: break;
        }
      },
      getDepartments() {// 获取所有职位
        this.$get('/staff/getDepartments/').then((response) => {
            if (response.code !== 0) {
              this.$message.error(response.message || '职位获取失败！');
            } else {
              this.departmentArr = response.data;
            }
        }).catch((error) => {
          this.$message.error('错误：职位获取失败！');
        })
      },
      getStaffList(data) {// 销售员工后台列表
        this.loading = true;
        this.$get('/staff/', data).then((response) => {
          if (response.code !== 0) {
            this.$message.error(response.message || '销售员工后台列表获取失败！');
          } else {
            this.staffData = response.data;
            this.total = response.info.total;
          }
          this.loading = false;
        }).catch((error) => {
          this.$message.error('错误：销售员工后台列表获取失败！');
        })
      },
      changeStatus(data) {// 员工状态改变
        this.loading = true;
        this.$get('/staff/changeStatus/', data).then((response) => {
          if (response.code !== 0) {
            this.$message.error(response.message || '员工状态改变失败！');
          } else {
            this.$message.success('员工状态改变成功！');
            this.getStaffList(this.searchType ? this.searchForm : {});
            this.msgPopUpType = false;
          }
          this.loading = false;
        }).catch((error) => {
          this.$message.error('错误：员工状态改变失败！');
        })
      },
      doEdit(data) {// 修改员工信息
        this.loading = true;
        this.$post('/staff/doEdit/', data).then((response) => {
          if (response.code !== 0) {
            this.$message.error(response.message || '修改员工信息失败！');
          } else {
            this.$message.success('保存成功！');
            this.getStaffList(this.searchType ? this.searchForm : {});
            this.msgPopUpType = false;
          }
          this.loading = false;
        }).catch((error) => {
          this.$message.error('错误：修改员工信息失败！');
        })
      },
      reBindWxcode(data) {// 获取绑定二维码
        this.$get('/staff/reBindWxcode', data).then((response) => {
          if (response.code !== 0) {
            this.$message.error(response.message || '绑定二维码获取失败！');
          } else {
            this.codePopUpData = Object.assign(this.weChatReplaceData, { codesrc: response.data.WXACode });
            this.codePopUpType = true;
          }
        }).catch((error) => {
          this.$message.error('错误：绑定二维码获取失败！');
        })
      }
    }
  };
</script>

<style scoped lang="less">
  .account {
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
    .wechat-replace {
      height: 280px;
      text-align: center;
      .code-box {
        margin-bottom: 30px;
        img {
          width: 155px;
          height: 155px;
        }
      }
      .content-txt {
        line-height: 30px;
      }
    }
    .wechat-replace {
      height: 250px;
    }
  }
</style>
