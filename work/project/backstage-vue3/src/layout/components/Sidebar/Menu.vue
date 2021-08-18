<template>
  <ul
    :class="{ menu: true, popup: isPopup, cor: level === 1, close: !isPopup ? asideClose : false }"
    ref="menuRef"
  >
    <template v-for="(item, index) in menuData">
      <li
        v-if="item.children && item.children.length > 0"
        :key="getMenuKey(parentKey, index)"
        :class="{
          'menu-submenu': true,
          'menu-submenu-selected':
            !isPopup ? selectedKeys[0]?.indexOf(getMenuKey(parentKey, index) + '-') === 0 : false,
          'menu-submenu-close':
            !isPopup ? openKeys.indexOf(getMenuKey(parentKey, index)) === -1 : false,
        }"
      >
        <div
          class="menu-submenu-title"
          :style="!isPopup ? `padding-left: ${level * 16}px` : ''"
          @click="toggleMenu(getMenuKey(parentKey, index), menuRef, index)"
          @mouseenter="submenuPopup($event, item, getMenuKey(parentKey, index))"
        >
          <i v-if="!parentKey" class="icon">♡</i>
          <span class="menu-title">{{ item.name }}</span>
          <i class="menu-submenu-arrow"></i>
        </div>
        <Menu
          :is-popup="isPopup"
          :level="level + 1"
          :parent-key="getMenuKey(parentKey, index)"
          :menu-data="item.children"
          :aside-close="asideClose"
          :open-keys="openKeys"
          :selected-keys="selectedKeys"
          :toggleMenu="toggleMenu"
          :toggleSelected="toggleSelected"
          :submenu-popup="submenuPopup"
        />
      </li>
      <li
        v-else
        :key="getMenuKey(parentKey, index)"
        :style="!isPopup ? `padding-left: ${level * 16}px` : ''"
        :class="{
          'menu-item': true,
          'menu-item-selected':
            selectedKeys.indexOf(getMenuKey(parentKey, index)) !== -1,
        }"
        @click="toggleSelected(getMenuKey(parentKey, index), item)"
      >
        <i v-if="!parentKey" class="icon">♡</i>
        <span class="menu-title">{{ item.name }}</span>
      </li>
    </template>
  </ul>
</template>

<script lang="ts">
import { ref, reactive, defineComponent } from "vue";

export default defineComponent({
  name: "AsideMenu",
  props: {
    isPopup: {
      type: Boolean,
      default: false,
    },
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
    toggleMenu: {
      type: Function,
      default: () => {},
    },
    toggleSelected: {
      type: Function,
      default: () => {},
    },
    submenuPopup: {
      type: Function,
      default: () => {},
    },
  },
  setup: () => {
    /* ref元素声明 */
    const menuRef = ref(null);

    /* 菜单逻辑 */
    const getMenuKey = (parentKey, index) => {
      return parentKey ? parentKey + "-" + (index + 1) : String(index + 1);
    };

    return {
      menuRef,

      getMenuKey,
    };
  },
});
</script>

<style lang="scss" scoped>
.menu {
  flex: 1;
  font-size: 14px;
  color: rgba(255, 255, 255, 0.65);
  background-color: #001529;
  user-select: none;
  overflow: hidden;
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
      width: 80%;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    * {
      pointer-events: none;
    }
  }
  .menu-submenu {
    .menu-submenu-title {
      color: #fff;
      .menu-submenu-arrow {
        display: inline-block;
        width: 10px;
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
        height: 0;
      }
    }
    &.menu-submenu-selected {
      & > .menu-submenu-title {
        color: #fff;
        .menu-submenu-arrow {
          opacity: 1;
        }
      }
    }
    .popup {
      position: absolute;
      left: calc(100% + 4px);
      top: -4px;
      display: none;
      .menu-item {
        padding-left: 16px;
      }
      &:before {
        content: " ";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: -7px;
        z-index: -1;
        width: 100%;
        height: 100%;
        opacity: .0001;
      }
    }
  }
  &.close {
    .menu-item,
    .menu-submenu-title {
      color: inherit;
      &:hover {
        color: #fff;
      }
      .menu-title {
        opacity: 0;
      }
    }
    .menu-submenu {
      .menu-submenu-title .menu-submenu-arrow {
        right: -146px;
      }
    }
  }
  &.cor.close > .menu-submenu > .menu {
    height: 0;
  }
  &.popup {
    width: $PopupWidth;
    overflow: initial;
    & > .menu-submenu {
      position: relative;
      &:hover > .popup {
        display: block;
      }
    }
    .menu-submenu {
      .menu-submenu-title {
        color: rgba(255, 255, 255, 0.65);
        .menu-submenu-arrow {
          opacity: .45;
          &::before {
            transform: rotate(45deg) translateY(-2.5px);
          }
          &::after {
            transform: rotate(-45deg) translateY(2.5px);
          }
        }
      }
      &:hover > .menu-submenu-title {
        color: #fff;
        & > .menu-submenu-arrow {
          opacity: 1;
        }
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
