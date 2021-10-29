<script lang="ts" setup>
import Sidebar from './Sidebar/index.vue';
import Navbar from './Navbar/index.vue';
import Tagbar from './Tagbar/index.vue';
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const cachedViews = computed(() => store.state.tagsView.cachedViews)
</script>

<template>
  <Sidebar />
  <div id="main">
    <header id="header">
      <Navbar />
      <Tagbar />{{cachedViews.length}}
    </header>
    <router-view v-slot="{ Component }">
      <transition name="fade-transform" mode="out-in" :appear="true">
        <keep-alive :include="cachedViews">
          <component :is="Component" />
        </keep-alive>
      </transition>
    </router-view>
  </div>
</template>

<style lang="scss" scoped>
#header {
  position: fixed;
  left: $sidebar-width-open;
  right: 0;
  top: 0;
  z-index: 9;
}
#main {
  margin-left: $sidebar-width-open;
  padding: $navbar-height + $tagbar-height + 10px 10px 10px;
  overflow: hidden;
}
</style>
