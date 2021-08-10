<template>
  <aside id="aside-menu" :class="{ close: asideClose }">
    <router-link class="logo" to="/" @click="selectedKeys.pop()">
      <i class="icon">♡</i>
      <span ref="logoCtnRef">LOGO</span>
    </router-link>
    <div class="scroll-warp">
      <Menu
        ref="menuRef"
        :aside-close="asideClose"
        :menu-data="menuData"
        :open-keys="openKeys"
        :selected-keys="selectedKeys"
        :toggle-open-keys="toggleOpenKeys"
        :toggle-selected-keys="toggleSelectedKeys"
      />
    </div>
    <div class="aside-close" @click="asideCloseFn">
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
  </aside>
</template>

<script lang="ts">
import { ref, reactive, defineComponent, onMounted } from "vue";
import router from '@/router'
import tween from "@/lib/tween";

const { easeInOutQuad } = tween;

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
    let logoCtnRef = ref(null);
    let menuRef = ref(null);

    /* 侧栏菜单逻辑 */
    let asideClose = ref(false);
    let menuData = reactive(props.menuData);
    let openKeys = reactive([]);
    let selectedKeys = reactive([]);

    let anTime = 300,
      anEle,
      isClose;

    const toggleOpenKeys = (key, refs, index) => {
      anEle = refs.children[index].querySelector(".menu");
      const idx = openKeys.indexOf(key);
      if (idx !== -1) {
        isClose = true;
        openKeys.splice(idx, 1);
      } else {
        isClose = false;
        openKeys.push(key);
      }
      toggleSubmenu(isClose, anEle, anTime);
    };
    const toggleSelectedKeys = (key, item) => {
      selectedKeys.push(key);
      selectedKeys.splice(0, selectedKeys.length - 1);
      if (item.url) router.push(item.url);
    };

    const asideCloseFn = () => {
      asideClose.value = !asideClose.value;
      for (let i = 0; i < menuRef.value?.menuRef.children.length; i++) {
        const item = menuRef.value?.menuRef.children[i];
        const subClose = item.classList.contains("menu-submenu-close");
        const menu = item.querySelector(".menu");
        if (subClose) continue;
        toggleSubmenu(asideClose.value, menu, anTime);
      }
    };

    const toggleSubmenu = (isClose, element, time) => {
      if (!element) return;
      const cloneEl = element.cloneNode(true);
      cloneEl.style.height = "auto";
      document.body.appendChild(cloneEl);
      const height = cloneEl.clientHeight;
      document.body.removeChild(cloneEl);
      let start = undefined;
      const step = (timestamp) => {
        if (!start) start = timestamp;
        const elapsed = timestamp - start;
        const pos = elapsed / time;
        if (!isClose) {
          element.style.height = easeInOutQuad(pos) * height + "px";
        } else {
          element.style.height = height - easeInOutQuad(pos) * height + "px";
        }
        if (elapsed < time) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
      const timer = setTimeout(() => {
        element.style.height = "";
        clearTimeout(timer);
      }, time + 50);
    };

    /* 生命周期 */
    onMounted(() => {
      logoCtnRef.value.style.width = logoCtnRef.value.clientWidth
        ? logoCtnRef.value.clientWidth + "px"
        : "auto";
    });

    return {
      logoCtnRef,
      menuRef,
      menuData,
      asideClose,
      openKeys,
      selectedKeys,
      toggleOpenKeys,
      toggleSelectedKeys,
      asideCloseFn,
    };
  },
});
</script>

<style lang="scss" scoped>
#aside-menu {
  // position: fixed;
  // top: 0;
  // left: 0;
  // z-index: 100;
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
    overflow: hidden auto;
    box-sizing: border-box;
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
    color: inherit;
    background-color: #2b2f3a;
    text-align: center;
    text-decoration: none;
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
  ::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  ::-webkit-scrollbar-thumb {
    background: hsla(0, 0%, 100%, 0.2);
    border-radius: 3px;
    box-shadow: inset 0 0 5px hsl(0deg 0% 100% / 5%);
  }
  ::-webkit-scrollbar-track {
    background: hsla(0, 0%, 100%, 0.15);
    border-radius: 3px;
    box-shadow: inset 0 0 5px rgb(37 37 37 / 5%);
  }
  ::selection {
    color: #fff;
    background: #1890ff;
  }
}
</style>
