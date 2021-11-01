<template>
  <section class="container">
    <div>
      <!-- <Init/> -->

      <el-container class="con-box">
        <el-header>
          <router-link
            to="/"
            class="logo"
          >
            <Logo />
          </router-link>
          <el-menu
            class="header-right"
            mode="horizontal"
            background-color="#252a2f"
            text-color="#fff"
            active-text-color="#fff"
            @select="handleSelect"
          >
            <div class="search-box">
              <el-input
                v-model="searchVal"
                placeholder="请输入内容"
                clearable
              />
              <div class="search-icon">
                <i class="el-icon-search" />
              </div>
            </div>
            <el-submenu
              index="1"
              class="lang-box"
            >
              <template slot="title">{{ langVal }}</template>
              <el-menu-item
                v-for="(v, i) in langOptions"
                v-show="langVal !== v.label"
                :index="v.value"
                :key="i"
              >{{ v.label }}</el-menu-item>
            </el-submenu>
            <div class="user-box">
              <div
                :class="{
                  'user-avatar-box': true,
                  'user-avatar-hover': userMenuStatus,
                  'user-menu-hover': userMenuHover
                }"
                @mouseenter="() => userMenuStatus = true"
                @mouseleave="() => userMenuStatus = false"
              >
                <div class="user-avatar" />
              </div>
              <div
                :class="{
                  'user-avatar-menu': true,
                  'user-avatar-hover': !userMenuStatus
                }"
                @mouseenter="() => {
                  userMenuStatus = true
                  userMenuHover = true
                }"
                @mouseleave="() => {
                  userMenuStatus = false
                  userMenuHover = false
                }"
              >
                <div class="user-menu-avatar-header">
                  <div class="user-menu-avatar">
                    <div class="user-avatar" />
                    <span class="user-name">slc</span>
                  </div>
                  <ul class="user-menu-bar">
                    <li>
                      <router-link to="/">基本资料</router-link>
                    </li>
                    <li>
                      <router-link to="/">实名认证</router-link>
                    </li>
                    <li>
                      <router-link to="/">安全设置</router-link>
                    </li>
                  </ul>
                </div>
                <div class="user-menu-avatar-body">
                  <ul class="user-body-list">
                    <li>
                      <router-link to="/">
                        <i class="el-icon-tickets" />
                        <span>安全管控</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/">
                        <i class="el-icon-tickets" />
                        <span>访问控制</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/">
                        <i class="el-icon-tickets" />
                        <span>会员权限</span>
                      </router-link>
                    </li>
                  </ul>
                </div>
                <div class="user-menu-avatar-footer">
                  <router-link to="/">退出</router-link>
                </div>
              </div>
            </div>
          </el-menu>
        </el-header>
        <el-container>
          <el-aside width="auto">
            <el-menu
              :collapse="isCollapse"
              :default-active="asideDefault"
              class="el-menu-vertical-demo"
              background-color="#545c64"
              text-color="#fff"
              active-text-color="#ffd04b"
              @open="handleOpen"
              @close="handleClose"
              @select="asideSelect"
            >
              <el-submenu index="1">
                <template slot="title">
                  <i class="el-icon-location" />
                  <span slot="title">导航一</span>
                </template>
                <el-menu-item-group>
                  <span slot="title">分组一</span>
                  <el-menu-item index="1-1">选项1</el-menu-item>
                  <el-menu-item index="1-2">选项2</el-menu-item>
                </el-menu-item-group>
                <el-menu-item-group title="分组2">
                  <el-menu-item index="1-3">选项3</el-menu-item>
                </el-menu-item-group>
                <el-submenu index="1-4">
                  <span slot="title">选项4</span>
                  <el-menu-item index="1-4-1">选项1</el-menu-item>
                </el-submenu>
              </el-submenu>
              <el-menu-item index="2">
                <i class="el-icon-menu" />
                <span slot="title">导航二</span>
              </el-menu-item>
              <el-menu-item
                index="3"
                disabled
              >
                <i class="el-icon-document" />
                <span slot="title">导航三</span>
              </el-menu-item>
              <el-menu-item index="4">
                <i class="el-icon-setting" />
                <span slot="title">导航四</span>
              </el-menu-item>
              <el-menu-item index="5">
                <i class="el-icon-edit-outline" />
                <span slot="title">Feedback</span>
              </el-menu-item>
              <el-menu-item index="6">
                <i class="el-icon-question" />
                <span slot="title">About</span>
              </el-menu-item>
            </el-menu>
            <div
              class="toggle-icon"
              @click="toggleCollapse"
            >
              <i :class="{'el-icon-arrow-right': isCollapse, 'el-icon-arrow-left': !isCollapse}" />
            </div>
          </el-aside>
          <el-container>
            <el-main>
              <slot />
            </el-main>
            <el-footer>Footer</el-footer>
          </el-container>
        </el-container>
      </el-container>
    </div>
  </section>
</template>

<script>
export default {
  components: {
    Init: () => import('~/components/Init.vue'),
    Logo: () => import('~/components/Logo.vue')
  },
  data() {
    return {
      isCollapse: true,
      userMenuStatus: false,
      userMenuHover: false,
      langOptions: [
        {
          value: '1',
          label: '简体中文'
        },
        {
          value: '2',
          label: 'English'
        }
      ],
      langVal: '简体中文',
      searchVal: '',
      asideRoute: ['/', '/', '/', '/', '/feedback/123', '/about/aaa/456']
    }
  },
  computed: {
    asideDefault() {
      return this.asideRoute.indexOf(this.$route.path) + 1 + ''
    }
  },
  mounted() {},
  methods: {
    handleOpen(key, keyPath) {
      console.log(key, keyPath)
    },
    handleClose(key, keyPath) {
      console.log(key, keyPath)
    },
    toggleCollapse() {
      console.log(this.isCollapse)
      this.isCollapse = !this.isCollapse
    },
    handleSelect(key, keyPath) {
      console.log(key, keyPath)
      this.langVal = this.langOptions[key - 1].label
    },
    asideSelect(key, keyPath) {
      console.log(key, keyPath)
      this.$router.push(this.asideRoute[key[0] - 1])
    }
  }
}
</script>

<style lang="less">
.el-menu--horizontal {
  .el-menu {
    .el-menu-item {
      background-color: #191d21 !important;
      &:hover {
        color: #00c1de !important;
        background-color: #131619 !important;
      }
    }
    .el-submenu {
      .el-submenu__title {
        background-color: #191d21 !important;
        &:hover {
          background-color: #131619 !important;
        }
      }
    }
  }
}

.container {
  .el-container.con-box {
    height: 100vh;
    .el-header,
    .el-footer {
      background-color: #252a2f;
      color: #fff;
      padding: 0;
      line-height: 60px;
      &.el-footer {
        padding: 0 10px;
      }
    }
    .logo {
      float: left;
      padding-left: 12px;
      padding-right: 12px;
      &:hover {
        background-color: #131619;
      }
    }
    .header-right {
      float: right;
      .search-box {
        width: 150px;
        height: 30px;
        line-height: 30px;
        float: left;
        margin: 15px 10px;
        outline: none;
        position: relative;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 5px;
        transition: all 0.4s ease-out;
        &:hover {
          width: 220px;
          .el-input__inner {
            border: 1px solid #00c1de;
          }
        }
        .el-input__inner {
          height: 30px;
          line-height: 30px;
          color: #fff;
          background: none;
          border: none;
          padding-right: 50px;
          border-color: #00c1de;
        }
        .el-input__suffix {
          right: 25px;
        }
        .search-icon {
          width: 26px;
          text-align: center;
          position: absolute;
          top: 0;
          right: 0;
          color: #131619;
          i {
            cursor: pointer;
            color: #fff;
          }
          &:hover {
            i {
              color: #00c1de;
            }
          }
          &::before {
            content: '';
            border-left: 1px solid #666;
            position: absolute;
            height: 20px;
            left: 0;
            top: 5px;
          }
        }
      }
      .el-submenu.lang-box {
        .el-submenu__title {
          border: none;
        }
        &:hover > .el-submenu__title {
          background-color: #131619 !important;
        }
        i {
          display: none;
        }
      }
      .user-box {
        float: right;
        .user-avatar-box {
          width: 60px;
          height: 60px;
          text-align: center;
          cursor: pointer;
          &.user-avatar-hover {
            background-color: #131619;
          }
          &.user-menu-hover {
            // background-color: #191d21;
            background-color: rgba(0, 0, 0, 0.25);
          }
          .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: url(../static/slc.jpg) no-repeat center;
            background-size: auto 100%;
            display: inline-block;
            vertical-align: middle;
            position: relative;
            top: -2px;
          }
        }
        .user-avatar-menu {
          position: fixed;
          top: 60px;
          right: 0;
          width: 280px;
          color: #fff;
          background-color: #191d21;
          font-size: 12px;
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
          z-index: 1;
          opacity: 1;
          visibility: visible;
          transition: opacity 0.15s, visibility 0s 0.15s;
          &.user-avatar-hover {
            opacity: 0;
            visibility: hidden;
          }
          a {
            width: 100%;
            color: #fff;
            display: inline-block;
            transition: all 0.15s;
            &:hover {
              color: #00c1de;
            }
          }
          .user-menu-avatar-header {
            padding: 10px;
            border-bottom: 1px solid hsla(0, 0%, 100%, 0.1);
            .user-menu-avatar {
              height: 30px;
              line-height: 30px;
              .user-avatar {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                background: url(../static/slc.jpg) no-repeat center;
                background-size: auto 100%;
                float: left;
              }
              .user-name {
                margin-left: 5px;
              }
            }
            .user-menu-bar {
              height: 20px;
              line-height: 20px;
              margin-top: 10px;
              text-align: center;
              li {
                width: 100% / 3;
                float: left;
                border-left: 1px solid hsla(0, 0%, 100%, 0.1);
                &:first-child {
                  border-left: none;
                }
              }
            }
          }
          .user-menu-avatar-body {
            padding: 10px 0;
            .user-body-list {
              li {
                line-height: 30px;
                padding: 0 15px;
                i {
                  margin-right: 5px;
                  color: hsla(0, 0%, 100%, 0.65);
                  transition: all 0.15s;
                }
                &:hover {
                  background-color: #131619;
                  i {
                    color: #fff;
                  }
                }
              }
            }
          }
          .user-menu-avatar-footer {
            line-height: 40px;
            text-align: center;
            border-top: 1px solid hsla(0, 0%, 100%, 0.1);
            a {
              height: 40px;
            }
          }
        }
      }
    }
    .el-aside {
      position: relative;
      .el-menu {
        border: none;
        &.el-menu-vertical-demo {
          min-height: 100%;
          &:not(.el-menu--collapse) {
            width: 200px;
          }
        }
        .el-menu-item {
          min-width: auto;
        }
      }
      .toggle-icon {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 60px;
        line-height: 60px;
        text-align: center;
        color: #909399;
        cursor: pointer;
        font-size: 24px;
        background-color: #545c64;
        &:hover {
          background-color: #434a50;
        }
      }
    }
    .el-main {
      background-color: #e9eef3;
      color: #333;
    }
  }
}
</style>
