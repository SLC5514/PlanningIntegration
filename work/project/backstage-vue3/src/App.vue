<template>
  <AsideMenu ref="asideMenuRef" :menu-data="menuData" />
  <div class="layout">
    <router-view v-slot="{ Component, route }">
      <transition :name="route.meta.transition || 'fade'" mode="out-in">
        <keep-alive>
          <component
            :is="Component"
            :key="route.meta.usePathKey ? route.path : undefined"
          />
        </keep-alive>
      </transition>
    </router-view>
    <router-link to="/page1">page1</router-link>
    <router-link to="/page2">page2</router-link>
  </div>
</template>

<script lang="ts">
import { ref, reactive, defineComponent, watch, onMounted } from "vue";
import AsideMenu from "@/components/AsideMenu.vue";

export default defineComponent({
  name: "App",
  components: {
    AsideMenu,
  },
  setup: () => {
    /* ref元素声明 */
    const asideMenuRef = ref(null);

    /* 侧栏菜单逻辑 */
    const menuData = reactive([
      {
        name: "菜单一",
        children: [
          { name: "子菜单1", url: "/page1" },
          { name: "子菜单2", url: "/page2" },
        ],
      },
      {
        name: "菜单二",
        children: [
          {
            name: "子菜单1",
            children: [
              { name: "孙菜单1" },
              {
                name: "孙菜单2",
                children: [{ name: "孙子菜单1" }, { name: "孙子菜单2" }],
              },
            ],
          },
          { name: "子菜单2" },
        ],
      },
      { name: "菜单三" },
    ]);

    /* 生命周期 */
    // onMounted(() => {
    //   console.log(asideMenuRef.value.openKeys);
    //   console.log(asideMenuRef.value.selectedKeys);
    //   watch(
    //     () => asideMenuRef.value.selectedKeys[0],
    //     (count, prevCount) => {
    //       console.log(count, prevCount);
    //     }
    //   );
    // });

    return {
      asideMenuRef,
      menuData,
    };
  },
});
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  min-height: 100vh;
  display: flex;
  flex: auto;
  flex-direction: row;
  background: #f0f2f5;
  .layout {
    min-height: 100vh;
    display: flex;
    flex: auto;
    flex-direction: column;
    background: #f0f2f5;
  }
}
</style>
