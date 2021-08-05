<template>
  <div id="aside-menu" :class="{ close: asideClose }">
    <a class="logo"><i class="icon">♡</i> <span ref="logoCtnRef">LOGO</span></a>
    <div class="scroll-warp">
      <Menu
        :aside-close="asideClose"
        :menu-data="menuData"
        :open-keys="openKeys"
        :selected-keys="selectedKeys"
        :toggleOpenKeys="toggleOpenKeys"
        :toggleSelectedKeys="toggleSelectedKeys"
      />
    </div>
    <div class="aside-close" @click="asideClose = !asideClose">
      <svg
        v-if="asideClose"
        viewBox="64 64 896 896"
        focusable="false"
        width="1em"
        height="1em"
        fill="currentColor"
        aria-hidden="true"
      >
        <path
          d="M408 442h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8zm-8 204c0 4.4 3.6 8 8 8h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56zm504-486H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm0 632H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zM115.4 518.9L271.7 642c5.8 4.6 14.4.5 14.4-6.9V388.9c0-7.4-8.5-11.5-14.4-6.9L115.4 505.1a8.74 8.74 0 000 13.8z"
        ></path>
      </svg>
      <svg
        v-else
        viewBox="64 64 896 896"
        focusable="false"
        width="1em"
        height="1em"
        fill="currentColor"
        aria-hidden="true"
      >
        <path
          d="M408 442h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8zm-8 204c0 4.4 3.6 8 8 8h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56zm504-486H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm0 632H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zM142.4 642.1L298.7 519a8.84 8.84 0 000-13.9L142.4 381.9c-5.8-4.6-14.4-.5-14.4 6.9v246.3a8.9 8.9 0 0014.4 7z"
        ></path>
      </svg>
    </div>
  </div>
</template>

<script lang="ts">
import { ref, reactive, defineComponent, onMounted } from "vue";

export default defineComponent({
  name: "AsideMenu",
  props: {
    menuData: {
      type: Array,
      default: [],
    },
  },
  setup: (props) => {
    /* ref元素声明 */
    const logoCtnRef = ref(null);

    /* 侧栏菜单逻辑 */
    let timer = null;
    const asideClose = ref(false);
    const menuData = reactive(props.menuData);
    const openKeys = reactive(["1"]);
    const selectedKeys = reactive(["1-1"]);

    const toggleOpenKeys = (val, refs) => {
      const el = refs.cloneNode(true);
      document.body.appendChild(el);
      const height = el.clientHeight;
      document.body.removeChild(el);
      console.log(height);
      const idx = openKeys.indexOf(val);
      if (idx !== -1) {
        openKeys.splice(idx, 1);
        // if (refs?.style.height === "auto" && refs?.clientHeight) {
        //   refs.style.height = refs.clientHeight
        //     ? refs.clientHeight + "px"
        //     : "auto";
        // }
      } else {
        openKeys.push(val);
        // refs.style.height = refs.getAttribute("data-height") + "px";
      }
      refs.style.height = height + "px";
      clearTimeout(timer);
      timer = setTimeout(() => {
        refs.style.height = "";
      }, 300);
    };
    const toggleSelectedKeys = (val) => {
      selectedKeys.push(val);
      selectedKeys.splice(0, selectedKeys.length - 1);
    };

    /* 生命周期 */
    onMounted(() => {
      logoCtnRef.value.style.width = logoCtnRef.value.clientWidth
        ? logoCtnRef.value.clientWidth + "px"
        : "auto";
    });

    return {
      logoCtnRef,
      menuData,
      asideClose,
      openKeys,
      selectedKeys,
      toggleOpenKeys,
      toggleSelectedKeys,
    };
  },
});
</script>

<style lang="scss" scoped>
#aside-menu {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 100;
  display: flex;
  flex-direction: column;
  width: $AsideMenuWidth;
  height: 100vh;
  color: rgba(255, 255, 255, 0.65);
  background-color: #001529;
  box-shadow: 2px 0 8px 0 rgb(29 35 41 / 5%);
  transition: width 0.3s cubic-bezier(0.2, 0, 0, 1);
  user-select: none;
  &.close {
    width: 48px;
    .logo span {
      width: 0 !important;
      opacity: 0;
    }
  }
  .scroll-warp {
    flex: 1;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: auto;
    overflow-x: hidden;
  }
  .logo {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 64px;
    padding: 16px;
    font-size: 18px;
    font-weight: bold;
    background-color: #2b2f3a;
    text-align: center;
    white-space: nowrap;
    box-sizing: border-box;
    overflow: hidden;
    span {
      margin-left: 10px;
      transition: width 0.3s, opacity 0.3s;
    }
  }
  .aside-close {
    position: relative;
    display: flex;
    align-items: center;
    height: 40px;
    line-height: 40px;
    margin: 4px 0;
    padding: 0 16px;
    box-sizing: border-box;
    cursor: pointer;
    transition: color 0.3s, background-color 0.3s;
    &:hover {
      color: #fff;
      background-color: #2b2f3a;
    }
  }
}
</style>
