<script lang="ts" setup>
import { Head } from '@vueuse/head'
import { useAppStore } from '~/stores/app'

const appStore = useAppStore()
</script>

<route lang="yaml">
name: Home
meta:
  layout: default
</route>

<template>
  <Head>
    <title>首页-{{ appStore.title }}</title>
  </Head>
  <el-container direction="vertical">
    <Header />
    <el-container>
      <AsideNav />
      <el-container>
        <el-main id="main">
          Main
          <T k="intro.desc" />
        </el-main>
        <el-footer id="footer">Footer</el-footer>
      </el-container>
      <el-aside id="controller-nav">Aside</el-aside>
    </el-container>
  </el-container>
</template>

<style lang="scss" scoped>
#footer {
  height: 55px;
  line-height: 55px;
  background: #fff;
  border-top: 1px solid #ececec;
  position: relative;
  z-index: 7;
}

#controller-nav {
  width: 276px;
  box-shadow: 0 0 2px 2px hsl(0deg 0% 93% / 50%);
  background: #fff;
  position: relative;
  z-index: 8;
  transition: width 0.3s;
}

#main {
  height: calc(100vh - 55px * 2);
}
</style>
