<script lang="ts" setup>
import { routes } from "~/router";
import Menu from './menu.vue';
import logo from '~/assets/logo.png';
</script>

<template>
  <div id="sidebar">
    <a class="logo" href="/">
      <img :src="logo" alt="">研发实验室
    </a>
    <Menu :list="routes" />
  </div>
</template>

<style lang="scss" scoped>
#sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: $sidebar-width-open;
  height: 100%;
  background-color: #fff;
  box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
  .logo {
    display: block;
    height: 50px;
    line-height: 50px;
    margin-bottom: 2px;
    text-align: center;
    background-color: #eee;
    border-bottom: 1px solid #ddd;
    box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
    img {
      height: 32px;
      margin-right: 12px;
      vertical-align: middle;
    }
  }
}
</style>
