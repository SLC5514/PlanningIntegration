<template>
  <el-dialog
    :title="msgPopUpData.title"
    top="2vh"
    width="600px"
    :visible.sync="msgPopUpType"
    :before-close="() => this.onMsgPopUpClose()">
    <el-form
      class="msg-compile"
      label-position="right"
      label-width="80px"
      v-loading="loading"
      :model="staffData">
      <el-form-item label="身份">
        <el-select v-model="staffData.user_type" placeholder="身份">
          <el-option v-for="(v, i) in identityArr" :key="i" :label="v.name" :value="v.id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="姓名">
        <el-input v-model="staffData.staff_name" placeholder="姓名"></el-input>
      </el-form-item>
      <el-form-item label="工号">
        <el-input v-model="staffData.job_number" placeholder="工号"></el-input>
      </el-form-item>
      <el-form-item label="部门">
        <el-select v-model="staffData.department" placeholder="部门">
          <el-option v-for="(v, i) in departmentArr" :key="i" :label="v" :value="v"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="职位">
        <el-input v-model="staffData.position" placeholder="职位"></el-input>
      </el-form-item>
      <el-form-item label="手机号">
        <el-input v-model="staffData.mobile" placeholder="手机号"></el-input>
      </el-form-item>
      <el-form-item label="邮箱">
        <el-input v-model="staffData.e_mail" placeholder="邮箱"></el-input>
      </el-form-item>
      <el-form-item label="微信">
        <el-input v-model="staffData.wechat_account" placeholder="微信"></el-input>
      </el-form-item>
      <el-form-item label="公司地址">
        <el-input v-model="staffData.company_address" placeholder="公司地址"></el-input>
      </el-form-item>
      <el-form-item label="名片头像">
        <el-col class="head-portrait" v-loading="imgLoading">
          <img :src="STATIC_URL + staffData.photograph" alt="">
        </el-col>
        <el-upload
          class="avatar-uploader"
          :action="$productUploadImg + '/staff/uploadphoto/'"
          name="photo"
          :show-file-list="false"
          :on-success="handleAvatarSuccess"
          :on-error="handleAvatarError"
          :before-upload="beforeAvatarUpload">
          <el-button type="text" @click="changeHeadPortrait">更换图片</el-button>
        </el-upload>
      </el-form-item>
      <el-form-item label="微信昵称">
        <span>{{staffData.wechat_nickname}}</span>
      </el-form-item>
      <el-form-item label="微信uuid">
        <span class="uuid-txt">{{staffData.wechat_openid}}</span>
        <el-button type="text" @click="weChatReplace">重新绑定</el-button>
      </el-form-item>
    </el-form>
    <div class="save">
      <el-button type="primary" @click="onMsgBtnClick(staffData)">{{msgPopUpData.btnTxt}}</el-button>
      <div class="save-hint">{{msgPopUpData.btnMsg}}</div>
    </div>
  </el-dialog>
</template>

<script>
  export default {
    name: 'MsgPopUp',
    props: {
      msgPopUpType: {
        type: Boolean,
        default: false
      },
      departmentArr: {
        type: Array,
        default: ['营销中心','客服中心','优料宝','设界','趋势中心','运维中心','人行财中心','技术中心','总裁办']
      },
      msgPopUpData: {
        type: Object,
        default: {
          id: '',
          title: '信息编辑',
          btnTxt: '保存',
          btnMsg: '保存成功后，1小时后生效'
        }
      },
      changeHeadPortrait: Function,
      weChatReplace: Function,
      onMsgBtnClick: Function,
      onMsgPopUpClose: Function
    },
    data() {
      return {
        loading: false,
        imgLoading: false,
        STATIC_URL: '',
        identityArr: [
          {
            id: '1',
            name: '逸尚云联员工'
          },{
            id: '2',
            name: '其他'
          }
        ],
        staffData: {}
      };
    },
    created() {
      this.getStaffList({
        staff_id: this.msgPopUpData.id
      });
    },
    methods: {
      handleAvatarSuccess(res, file) {//上传成功
        this.STATIC_URL = file.response.data.STATIC_URL;
        this.staffData.photograph = file.response.data.path;
        this.imgLoading = false;
      },
      handleAvatarError(err, file) {//上传失败
        this.$message.error(err.message || '上传失败!');
        this.imgLoading = false;
      },
      beforeAvatarUpload(file) {//上传之前
        const isImg = file.type.indexOf('image') !== -1;
        const isLt5M = file.size / 1024 / 1024 < 5;
        if (!isImg) {
          this.$message.error('请上传图片!');
          return isImg;
        }
        if (!isLt5M) {
          this.$message.error('上传头像图片大小不能超过 5MB!');
          return isLt5M;
        }
        this.imgLoading = true;
        return isImg && isLt5M;
      },
      getStaffList(data) {// 获取员工信息
        this.loading = true;
        this.$get('/staff/getStaff/', data).then((response) => {
          if (response.code !== 0) {
            this.$message.error(response.message || '员工信息获取失败！');
          } else {
            this.STATIC_URL = response.info.STATIC_URL;
            this.staffData = response.data;
          }
          this.loading = false;
        }).catch((error) => {
          this.$message.error('错误：员工信息获取失败！');
        })
      }
    }
  }
</script>

<style scoped lang="less">
  .msg-compile {
    width: 70%;
    margin: 0 auto;
    .el-select,.el-input {
      width: 100%;
    }
    .head-portrait {
      width: 100px;
      height: 100px;
      margin-right: 10px;
      border: 1px dashed #ccc;
      overflow: hidden;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .uuid-txt {
      margin-right: 10px;
    }
  }
  .save {
    text-align: center;
    .save-hint {
      font-size: 12px;
      margin-top: 5px;
    }
  }
</style>
