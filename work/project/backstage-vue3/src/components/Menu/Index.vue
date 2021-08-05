<template>
  <ul
    :class="{ menu: true, close: asideClose }"
    ref="menuRef"
    style="height: auto !important"
  >
    <template v-for="(item, index) in menuData">
      <li
        v-if="item.children && item.children.length > 0"
        :key="getMenuKey(parentKey, index)"
        :class="{
          'menu-submenu': true,
          'menu-submenu-close': isMenuInit
            ? openKeys.indexOf(getMenuKey(parentKey, index)) === -1
            : false,
        }"
      >
        <div
          class="menu-submenu-title"
          :style="`padding-left: ${level * 16}px`"
          @click="
            toggleOpenKeys(getMenuKey(parentKey, index), submenuRef?.menuRef)
          "
        >
          <i v-if="!parentKey" class="icon">♡</i>
          <span class="menu-title">{{ item.name }} - {{menuHeight}}</span>
          <i class="menu-submenu-arrow"></i>
        </div>
        <Menu
          ref="submenuRef"
          :level="level + 1"
          :parent-key="getMenuKey(parentKey, index)"
          :aside-close="asideClose"
          :menu-data="item.children"
          :open-keys="openKeys"
          :selected-keys="selectedKeys"
          :toggleOpenKeys="toggleOpenKeys"
          :toggleSelectedKeys="toggleSelectedKeys"
        />
      </li>
      <li
        v-else
        :key="getMenuKey(parentKey, index)"
        :style="`padding-left: ${level * 16}px`"
        :class="{
          'menu-item': true,
          'menu-item-selected':
            selectedKeys.indexOf(getMenuKey(parentKey, index)) !== -1,
        }"
        @click="toggleSelectedKeys(getMenuKey(parentKey, index))"
      >
        <i v-if="!parentKey" class="icon">♡</i>
        <span class="menu-title">{{ item.name }} - {{menuHeight}}</span>
      </li>
    </template>
  </ul>
  <!-- <div class="menu-submenu-popup">
    666
  </div> -->
</template>

<script lang="ts">
import { ref, defineComponent, onMounted } from "vue";

export default defineComponent({
  name: "AsideMenu",
  props: {
    asideClose: {
      type: Boolean,
      default: true,
    },
    menuData: {
      type: Array,
      default: [],
    },
    level: {
      type: Number,
      default: 1,
    },
    parentKey: {
      type: String,
    },
    openKeys: {
      type: Array,
      default: [],
    },
    selectedKeys: {
      type: Array,
      default: [],
    },
    toggleOpenKeys: {
      type: Function,
    },
    toggleSelectedKeys: {
      type: Function,
    },
  },
  setup: () => {
    /* ref元素声明 */
    const menuRef = ref(null);
    const submenuRef = ref(null);

    /* 菜单逻辑 */
    const isMenuInit = ref(false);
    const menuHeight = ref(0);
    const getMenuKey = (parentKey, index) => {
      return parentKey ? parentKey + "-" + (index + 1) : String(index + 1);
    };

    /* 生命周期 */
    onMounted(() => {
      menuHeight.value = menuRef.value.clientHeight || 0;
      // menuRef.value.style.height = menuRef.value.clientHeight
      //   ? menuRef.value.clientHeight + "px"
      //   : "auto";
      menuRef.value.setAttribute('data-height', menuRef.value.clientHeight || 0)
      menuRef.value.style.height = '';
      isMenuInit.value = true;
    });

    return {
      menuRef,
      submenuRef,
      isMenuInit,
      menuHeight,
      getMenuKey,
    };
  },
});
</script>

<style lang="scss" scoped>
.menu {
  flex: 1;
  font-size: 14px;
  user-select: none;
  overflow: hidden;
  transition: height 0.3s;
  .menu-item,
  .menu-submenu-title {
    position: relative;
    display: flex;
    align-items: center;
    height: 40px;
    line-height: 40px;
    margin: 4px 0;
    padding: 0 16px;
    box-sizing: border-box;
    white-space: nowrap;
    overflow: hidden;
    cursor: pointer;
    transition: color 0.3s, background-color 0.3s;
    &:hover {
      color: #fff;
      background-color: #2b2f3a;
    }
    &.menu-item-selected {
      color: #fff;
      background-color: #1890ff;
    }
    .menu-title {
      opacity: 1;
      margin-left: 10px;
      transition: opacity 0.3s;
    }
  }
  .menu-submenu {
    .menu-submenu-title {
      color: #fff;
      .menu-submenu-arrow {
        display: inline-block;
        width: 10px;
        color: rgba(0, 0, 0, 0.85);
        opacity: 1;
        position: absolute;
        top: 50%;
        right: 16px;
        width: 10px;
        color: rgba(0, 0, 0, 0.85);
        transform: translateY(-50%);
        transition: all 0.3s;
        &::before {
          content: "";
          position: absolute;
          width: 6px;
          height: 1.5px;
          background-color: #fff;
          border-radius: 2px;
          transform: rotate(45deg) translateX(2.5px);
          transition: background 0.3s cubic-bezier(0.645, 0.045, 0.355, 1),
            transform 0.3s cubic-bezier(0.645, 0.045, 0.355, 1),
            top 0.3s cubic-bezier(0.645, 0.045, 0.355, 1),
            color 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
        }
        &::after {
          content: "";
          position: absolute;
          width: 6px;
          height: 1.5px;
          background-color: #fff;
          border-radius: 2px;
          transform: rotate(-45deg) translateX(-2.5px);
          transition: background 0.3s cubic-bezier(0.645, 0.045, 0.355, 1),
            transform 0.3s cubic-bezier(0.645, 0.045, 0.355, 1),
            top 0.3s cubic-bezier(0.645, 0.045, 0.355, 1),
            color 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
        }
      }
      &:hover {
        background-color: transparent;
      }
    }
    .menu .menu-item {
      padding-left: 32px;
    }
    &.menu-submenu-close {
      & > .menu-submenu-title {
        color: inherit;
        .menu-submenu-arrow {
          opacity: 0.45;
          &::before {
            transform: rotate(-45deg) translateX(2.5px);
          }
          &::after {
            transform: rotate(45deg) translateX(-2.5px);
          }
        }
        &:hover {
          color: #fff;
          .menu-submenu-arrow {
            opacity: 1;
          }
        }
      }
      & > .menu {
        height: 0 !important;
      }
    }
  }
  &.close {
    .menu-item,
    .menu-submenu-title {
      .menu-title {
        opacity: 0;
      }
    }
    .menu-submenu {
      .menu {
        height: 0 !important;
      }
      .menu-submenu-title .menu-submenu-arrow {
        right: -146px;
      }
    }
  }
}
.menu-submenu-popup {
  position: absolute;
  top: 0;
  left: 210px;
  border-radius: 2px;
  background-color: #001529;
}
</style>
