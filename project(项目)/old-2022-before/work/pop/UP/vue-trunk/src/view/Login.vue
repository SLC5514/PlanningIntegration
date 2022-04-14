<template>
  <div class="loginForm">
    <el-form ref="form" :model="form" label-width="60px" :rules="rules" @submit.native.prevent>
      <el-form-item label="账号" prop="account">
        <el-input v-model="form.account"></el-input>
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="form.password" type="password"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button class="submit" type="primary" native-type="submit" @click="onSubmit('form')">确认</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import getData from "../assets/getData";
  export default {
    data() {
      return {
        redirect: '',
        form: {
          account: '',
          password: ''
        },
        rules: {
          account: [
            { required: true, message: '请输入账号', trigger: 'blur' }
          ],
          password: [
            { required: true, message: '请输入密码', trigger: 'blur' }
          ]
        }
      }
    },
    created() {
      this.redirect = this.$router.currentRoute.query.redirect;
    },
    methods: {
      onSubmit(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.$post('/user/login/', this.form).then((response) => {
              if (response.code !== 0) {
                this.$message.error(response.message || '登陆失败！');
              } else {
                this.$message.success('登陆成功！');
                this.$router.push({
                  name: this.redirect || 'account'
                })
              }
            }).catch((error) => {
              this.$message.error('错误：登陆失败！');
            })
          } else {
            return false;
          }
        });
      }
    }
  }
</script>

<style lang="less" scoped>
  .loginForm {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: #ccc;
    .el-form {
      width: 500px;
      padding: 50px 40px 20px 30px;
      box-sizing: border-box;
      background: #fff;
      box-shadow: 0 0 5px rgba(0, 0, 0, .5);
      position: absolute;
      top: 50%;
      left: 50%;
      margin-top: -150px;
      margin-left: -250px;
      .submit {
        float: right;
      }
    }
  }
</style>
