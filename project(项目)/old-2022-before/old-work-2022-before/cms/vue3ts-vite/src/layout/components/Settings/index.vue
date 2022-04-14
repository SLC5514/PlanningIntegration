<script lang="ts" setup>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
// import ThemePicker from '@/components/ThemePicker'

const $store = useStore();

let isOpen = ref(false);

const theme = computed({
  get: () => $store.state.settings.theme,
  set: val => {
    $store.dispatch('settings/changeSetting', {
      key: 'theme',
      value: val
    })
  }
})

const fixedHeader = computed({
  get: () => $store.state.settings.fixedHeader,
  set: val => {
    $store.dispatch('settings/changeSetting', {
      key: 'fixedHeader',
      value: val
    })
  }
})

const tagsView = computed({
  get: () => $store.state.settings.tagsView,
  set: val => {
    $store.dispatch('settings/changeSetting', {
      key: 'tagsView',
      value: val
    })
  }
})

const sidebarLogo = computed({
  get: () => $store.state.settings.sidebarLogo,
  set: val => {
    $store.dispatch('settings/changeSetting', {
      key: 'sidebarLogo',
      value: val
    })
  }
})

// function themeChange(val: EventTarget | null) {
//   const ipt = val as HTMLInputElement;
//   $store.dispatch('settings/changeSetting', {
//     key: 'theme',
//     value: ipt.value
//   })
// }
</script>

<template>
  <div :class="['drawer-container', isOpen && 'open']">
    <i class="icon-setting" @click="isOpen = !isOpen">{{isOpen ? '&gt;' : '&lt;'}}</i>
    <div>
      <h3 class="drawer-title">系统布局配置</h3>

      <div class="drawer-item">
        <span>主题色</span>{{theme}}
        <input type="color" name="" id="" class="drawer-switch" v-model="theme">
        <!-- @change="themeChange($event?.target)" -->
        <!-- <theme-picker style="float: right;height: 26px;margin: -3px 8px 0 0;" @change="themeChange" /> -->
      </div>

      <div class="drawer-item">
        <span>开启 Tags-View</span>
        <input type="checkbox" name="" id="" class="drawer-switch" v-model="tagsView">
        <!-- <el-switch v-model="tagsView" class="drawer-switch" /> -->
      </div>

      <div class="drawer-item">
        <span>固定 Header</span>
        <input type="checkbox" name="" id="" class="drawer-switch" v-model="fixedHeader">
        <!-- <el-switch v-model="fixedHeader" class="drawer-switch" /> -->
      </div>

      <div class="drawer-item">
        <span>侧边栏 Logo</span>
        <input type="checkbox" name="" id="" class="drawer-switch" v-model="sidebarLogo">
        <!-- <el-switch v-model="sidebarLogo" class="drawer-switch" /> -->
      </div>

      <div class="drawer-item">
        <span>菜单支持拼音搜索</span>
        <input type="checkbox" name="" id="" class="drawer-switch">
      </div>

    </div>
  </div>
</template>

<style lang="scss" scoped>
.drawer-container {
  position: fixed;
  top: 0;
  right: -260px;
  width: 260px;
  height: 100%;
  padding: 24px;
  font-size: 14px;
  line-height: 1.5;
  word-wrap: break-word;
  box-sizing: border-box;
  background: #fff;
  box-shadow: 0 0 15px 0 rgb(0 0 0 / 5%);
  transition: all .25s cubic-bezier(.7,.3,.1,1);
  z-index: 9;
  &.open {
    right: 0;
  }

  .icon-setting {
    position: absolute;
    left: -50px;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    line-height: 50px;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    text-align: center;
    color: #fff;
    background-color: skyblue;
    font-style: normal;
    font-size: 16px;
    cursor: pointer;
  }

  .drawer-title {
    margin-bottom: 12px;
    color: rgba(0, 0, 0, .85);
    font-size: 14px;
    line-height: 22px;
  }

  .drawer-item {
    color: rgba(0, 0, 0, .65);
    font-size: 14px;
    padding: 12px 0;
  }

  .drawer-switch {
    float: right
  }
}
</style>
