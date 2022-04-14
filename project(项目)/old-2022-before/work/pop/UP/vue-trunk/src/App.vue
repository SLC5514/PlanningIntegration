<template>
    <div id="app">
        <router-view v-if="$route.path == '/login'"/>
        <el-container v-else class="app-box">
            <el-header class="header">
                <el-row :gutter="24">
                    <el-col :span="6" class="head-left">
                        <div class="head-log">
                            <img src="./assets/logo.png">
                        </div>
                        <div>销售微信分发平台</div>
                    </el-col>
                    <el-col :span="14">
                        <div class="grid-content"></div>
                    </el-col>
                    <el-col :span="4" class="head-right">
                        <div class="login-type">
                            <el-button type="text" style="color:#fff;" @click="loginOut">登出</el-button>
                        </div>
                        <div class="user-name">admin</div>
                    </el-col>
                </el-row>
            </el-header>
            <el-container style="margin-top:60px;">
                <el-aside class="aside">
                    <el-menu class="menu" :default-active="menuDefActive" background-color="#606266" text-color="#fff" active-text-color="#ffd04b">
                        <el-menu-item index="home">
                            <router-link tag="div" to="/">账号管理</router-link>
                        </el-menu-item>
                        <el-menu-item index="business">
                            <router-link tag="div" to="/business">客户表单</router-link>
                        </el-menu-item>
                        <el-menu-item index="product">
                            <router-link tag="div" to="/product">产品管理</router-link>
                        </el-menu-item>
                        <el-menu-item index="fashion">
                            <router-link tag="div" to="/fashion">今日时尚</router-link>
                        </el-menu-item>
                    </el-menu>
                </el-aside>
                <el-main class="main">
                    <router-view />
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
import getData from "./assets/getData";
export default {
    name: "App",
    data () {
      return {
        menuDefActive: this.$route.path.split("/")[1] || "home"
      };
    },
    watch: {
      $route (to, from) {
        if (to.fullPath.indexOf('product_compile') != -1 || to.fullPath.indexOf('product_add') != -1) {
          $('#app').css('height', 'auto');
        } else {
          $('#app').css('height', '100%');
        }
        this.menuDefActive = to.fullPath.split("/")[1] || "home";
      }
    },
    methods: {
      loginOut() {
        this.$post('/user/logout/').then((response) => {
          if (response.code !== 0) {
            this.$message.error(response.message || '登出失败！');
          } else {
            this.$message.success('登出成功！');
            this.$router.replace({
              name: 'login'
            })
          }
        }).catch((error) => {
          this.$message.error('错误：登出失败！');
        })
      }
    }
};
</script>

<style lang="less">
* {
  margin: 0;
  padding: 0;
}
html,
body {
  width: 100%;
  height: 100%;
}
a {
  cursor: pointer;
  text-decoration: none;
}
img {
  border: none;
}
#app {
  min-width: 1200px;
  height: 100%;
  .app-box {
    height: 100%;
    .header {
      background-color: #303133;
      color: #fff;
      text-align: center;
      line-height: 60px;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 2000;
      .head-left {
        & > div {
          float: left;
        }
        .head-log {
          margin-right: 10px;
          img {
            width: 50px;
            height: 50px;
            vertical-align: middle;
          }
        }
      }
      .grid-content {
        min-height: 36px;
      }
      .head-right {
        & > div {
          float: right;
          margin: 0 10px;
        }
        .login-type a {
          color: #fff;
        }
      }
    }
    .aside {
      width: 200px !important;
      background-color: #e4e7ed;
      color: #333;
      text-align: center;
      line-height: 200px;
      position: fixed;
      left: 0;
      height: 100%;
      .menu {
        height: 100%;
        .el-menu-item {
          padding: 0 !important;
        }
      }
    }
    .main {
      margin-left: 200px;
      color: #333;
      background-color: #ebeef5;
    }
  }
}
// tinymce
.checkbox-button.on { background-color:#666; color:#fff; }
#zwb_upload { display:block;height:30px; text-align:center;}
#zwb_upload .add_box { cursor: pointer; display:block;color: #888;height:30px;line-height: 30px;background: #fafafa;border: 1px solid #ddd;border-radius: 4px;position: relative; }
#zwb_upload input { width: 100%;height: 100%; }
#zwb_upload .add_box:hover { color: #444;background: #eee;border-color: #ccc;text-decoration: none; }
#mce-modal-block.mce-in { z-index: 1999!important; }
.mce-window.mce-in { z-index: 2000!important; }
div.mce-menubtn.mce-opened { z-index: 999!important;}
.mce-container.mce-panel.mce-floatpanel.mce-menu.mce-animate.mce-menu-align.mce-in { z-index: 999!important;}
// el样式调整(全局)
.el-table--border {
  min-height: 200px;
}
.el-dialog {
  .el-dialog__header {
    height: 25px;
    background: #303133;
    .el-dialog__title {
      color: #c0c4cc;
      position: absolute;
      margin-top: -4px;
    }
    .el-dialog__headerbtn {
      font-size: 25px;
      top: 15px;
    }
  }
  .el-dialog__body {
    padding: 20px;
  }
}
// el样式调整(局部)
.account {
  .el-dialog {
    margin-bottom: 2vh;
  }
}
.product-add, .fashion-add {
  .el-textarea__inner {
    overflow: hidden;
  }
}
</style>
