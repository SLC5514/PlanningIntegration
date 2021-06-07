<template>
  <a-layout class="home-control">
    <a-layout-sider theme="light" v-model:collapsed="collapsed" collapsible class="noselect">
      <router-link class="logo" to="/"></router-link>
      <a-menu theme="light" mode="inline" v-model:selectedKeys="selectedKeys">
        <template v-for="item in menuList">
          <a-sub-menu v-if="item.children" :key="item.value">
            <template #title>
              <span>
                <Icon :name="item.icon" />
                <span>{{item.name}}</span>
              </span>
            </template>
            <a-menu-item v-for="sub in item.children" :key="sub.value">{{sub.name}}</a-menu-item>
          </a-sub-menu>
          <a-menu-item v-else :key="item.value">
            <Icon :name="item.icon" />
            <span>{{item.name}}</span>
          </a-menu-item>
        </template>
      </a-menu>
    </a-layout-sider>
    <a-layout>
      <a-layout-header class="noselect" />
      <a-layout-content>
        <a-breadcrumb class="noselect">
          <a-breadcrumb-item>User</a-breadcrumb-item>
          <a-breadcrumb-item>Bill</a-breadcrumb-item>
        </a-breadcrumb>
        <div class="content">
          <router-link to="/">首页</router-link>
          <router-link to="/html">HTML</router-link>
          <a-button type="primary">按钮</a-button>
          <router-view></router-view>
        </div>
      </a-layout-content>
      <a-layout-footer class="noselect">Ant Design ©2018 Created by Ant UED</a-layout-footer>
    </a-layout>
  </a-layout>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import Icon from '/@/components/Icon.vue'
import {
  PieChartOutlined,
  DesktopOutlined,
  UserOutlined,
} from '@ant-design/icons-vue'

const menuList = [
  {
    name: 'HTML',
    value: '1',
    icon: 'PieChartOutlined'
  },
  {
    name: 'CSS',
    value: '2',
    icon: 'DesktopOutlined'
  },
  {
    name: 'JavaScript',
    value: '3',
    icon: 'DesktopOutlined'
  },
  {
    name: '框架',
    value: '4',
    icon: 'UserOutlined',
    children: [
      {
        name: 'Vue',
        value: '4-1'
      },
      {
        name: 'React',
        value: '4-2'
      },
    ]
  },
  {
    name: 'NodeJs',
    value: '5',
    icon: 'DesktopOutlined'
  },
  {
    name: 'WEB安全',
    value: '6',
    icon: 'DesktopOutlined'
  },
  {
    name: '练习&试验',
    value: '7',
    icon: 'DesktopOutlined'
  },
]

export default defineComponent({
  name: 'Home',
  components: {
    Icon,
    PieChartOutlined,
    DesktopOutlined,
    UserOutlined,
  },
  setup() {
    const data = {
      collapsed: ref<boolean>(false),
      selectedKeys: ref<string[]>(['1']),
      menuList,
    }
    return data
  },
})
</script>

<style lang="less" scoped>
.home-control {
  min-height: 100vh;
  .logo {
    position: relative;
    display: block;
    height: 32px;
    padding: 16px;
    box-sizing: content-box;
    box-shadow: 0 2px 5px rgb(0 0 0 / 8%);
    &::after {
      content: '';
      display: block;
      width: 100%;
      height: 100%;
      background: #eee;
    }
  }
  .ant-layout-sider {
    overflow: hidden;
    background-color: #fafafa;
    box-shadow: 0 2px 5px rgb(0 0 0 / 8%);
  }
  .ant-layout-header {
    position: relative;
    padding: 0px;
    background-color: #fafafa;
    box-shadow: 0 2px 5px rgb(0 0 0 / 8%);
  }
  .ant-layout-content {
    margin: 0 16px;
    display: flex;
    flex-direction: column;
    .ant-breadcrumb {
      margin: 16px 0;
    }
    .content {
      flex: 1;
      padding: 24px;
      background: #fff;
      min-height: 360px
    }
  }
  .ant-layout-footer {
    text-align: center;
  }
}
</style>
<style lang="less">
.home-control {
  .ant-menu {
    color: #666;
    background-color: #fafafa;
  }
  .ant-menu-submenu > .ant-menu {
    background-color: #fafafa;
  }
  .ant-layout-sider-trigger {
    background-color: #fafafa;
    &:hover {
      background-color: #fff;
    }
  }
}
</style>
