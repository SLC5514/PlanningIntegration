<template>
  <div class="app-wrapper">
    <AsideMenu ref="asideMenuRef" :menu-data="menuData" />
    <div class="main-container">
      <div class="fixed-header">fixed-header</div>
      <section class="app-main">
        <router-view v-slot="{ Component, route }">
          <transition :name="route.meta.transition || 'fade'" mode="out-in">
            <keep-alive :include="[]">
              <component :is="Component" :key="route.path" />
            </keep-alive>
          </transition>
        </router-view>
      </section>
    </div>
  </div>
</template>

<script lang="ts">
import { ref, reactive, defineComponent } from "vue";
import AsideMenu from "@/components/AsideMenu.vue";

export default defineComponent({
  name: "Layout",
  components: {
    AsideMenu
  },
  setup(props) {
    /* ref元素声明 */
    const asideMenuRef = ref(null);

    /* 侧栏菜单逻辑 */
    const menuData = reactive([
      {
        name: "菜单一",
        children: [
          { name: "菜单1-1", url: "/page1" },
          { name: "菜单1-2", url: "/page2" },
        ],
      },
      {
        name: "菜单二",
        children: [
          {
            name: "菜单2-1",
            children: [
              { name: "菜单2-1-1" },
              {
                name: "菜单2-1-2",
                children: [{ name: "菜单2-1-2-1" }, { name: "菜单2-1-2-2" }],
              },
            ],
          },
          { name: "菜单2-2" },
        ],
      },
      { name: "菜单三" },
    ]);

    return {
      asideMenuRef,
      menuData,
    };
  }
})
</script>

<style lang="scss" scoped>
.app-wrapper {
  display: flex;
  .main-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100%;
    position: relative;
    .fixed-header {
      // width: calc(100% - 210px);
      // position: absolute;
      // left: 0;
      // right: 0;
      // top: 0;
      // z-index: 9;
    }
    .app-main {
      flex: 1;
      width: 100%;
      // min-height: 100vh;
      position: relative;
      overflow: hidden;
      // padding-top: 50px;
    }
  }
}
</style>
