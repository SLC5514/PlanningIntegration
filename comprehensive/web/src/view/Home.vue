<template>
  <a-layout class="home-control">
    <a-layout-sider v-model:collapsed="collapsed" collapsible class="noselect">
      <div class="logo" />
      <a-menu theme="dark" v-model:selectedKeys="selectedKeys" mode="inline">
        <a-menu-item key="1">
          <PieChartOutlined />
          <span>HTML</span>
        </a-menu-item>
        <a-menu-item key="2">
          <DesktopOutlined />
          <span>CSS</span>
        </a-menu-item>
        <a-menu-item key="3">
          <DesktopOutlined />
          <span>JavaScript</span>
        </a-menu-item>
        <a-sub-menu key="sub1">
          <template #title>
            <span>
              <UserOutlined />
              <span>框架</span>
            </span>
          </template>
          <a-menu-item key="4">Tom</a-menu-item>
          <a-menu-item key="5">Bill</a-menu-item>
          <a-menu-item key="6">Alex</a-menu-item>
        </a-sub-menu>
        <a-menu-item key="7">
          <DesktopOutlined />
          <span>Nodejs</span>
        </a-menu-item>
        <a-menu-item key="8">
          <DesktopOutlined />
          <span>WEB安全</span>
        </a-menu-item>
      </a-menu>
    </a-layout-sider>
    <a-layout>
      <a-layout-header style="background: #fff; padding: 0" />
      <a-layout-content style="margin: 0 16px">
        <a-breadcrumb style="margin: 16px 0">
          <a-breadcrumb-item>User</a-breadcrumb-item>
          <a-breadcrumb-item>Bill</a-breadcrumb-item>
        </a-breadcrumb>
        <div class="content">
          Bill is a cat.
        </div>
      </a-layout-content>
      <a-layout-footer style="text-align: center">
        Ant Design ©2018 Created by Ant UED
      </a-layout-footer>
    </a-layout>
  </a-layout>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import {
  PieChartOutlined,
  DesktopOutlined,
  UserOutlined,
} from '@ant-design/icons-vue';

export default defineComponent({
  name: 'Home',
  components: {
    PieChartOutlined,
    DesktopOutlined,
    UserOutlined,
  },
  setup() {
    const data = {
      collapsed: ref<boolean>(false),
      selectedKeys: ref<string[]>(['1']),
    }
    return data
  },
})
</script>

<style lang="less" scoped>
.home-control {
  min-height: 100vh;
  .logo {
    height: 32px;
    margin: 16px;
    background: #eee;
  }
  // .ant-menu {
  //   color: #999;
  //   background-color: #fafafa;
  // }
  // .ant-layout-sider {
  //   background-color: #fafafa;
  // }
  .ant-layout-content {
    display: flex;
    flex-direction: column;
    .content {
      flex: 1;
      padding: 24px;
      background: #fff;
      min-height: 360px
    }
  }
}
</style>
<style lang="less">
// .home-control {
//   .ant-menu-submenu > .ant-menu {
//     background-color: #fafafa;
//   }
//   .ant-menu:not(.ant-menu-horizontal) .ant-menu-item-selected {
//     color: #333;
//     background-color: #f5f5f5;
//   }
//   .ant-menu-submenu-selected, .ant-menu-item:hover, .ant-menu-item-active, .ant-menu:not(.ant-menu-inline) .ant-menu-submenu-open, .ant-menu-submenu-active, .ant-menu-submenu-title:hover {
//     color: #333;
//   }
//   .ant-menu-item:active, .ant-menu-submenu-title:active {
//     background-color: initial;
//   }
//   .ant-menu-vertical .ant-menu-item::after, .ant-menu-vertical-left .ant-menu-item::after, .ant-menu-vertical-right .ant-menu-item::after, .ant-menu-inline .ant-menu-item::after {
//     border-color: #333;
//   }
//   .ant-menu-submenu-inline > .ant-menu-submenu-title .ant-menu-submenu-arrow::before,
//   .ant-menu-submenu-inline > .ant-menu-submenu-title .ant-menu-submenu-arrow::after {
//     background: linear-gradient(to right, #999, #999);
//   }
//   .ant-menu-submenu-inline.ant-menu-submenu-active > .ant-menu-submenu-title .ant-menu-submenu-arrow::before,
//   .ant-menu-submenu-inline.ant-menu-submenu-active > .ant-menu-submenu-title .ant-menu-submenu-arrow::after {
//     background: linear-gradient(to right, #333, #333);
//   }
//   .ant-layout-sider-trigger {
//     color: #002140;
//     background-color: #fafafa;
//   }
// }
</style>
