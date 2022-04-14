<template>
  <div class="menu-submenu-popup" v-if="show" :style="{ left: x + 4 + 'px', top: y + 'px' }">
    <Menu :is-popup="true"
      :parent-key="parentKey"
      :menu-data="menuList"
      :aside-close="$parent.asideClose"
      :open-keys="$parent.openKeys"
      :selected-keys="$parent.selectedKeys"
      :toggle-menu="$parent.toggleMenu"
      :toggle-selected="$parent.toggleSelected"
      :submenu-popup="$parent.submenuPopup" />
  </div>
</template>

<script lang="ts">
import { ref, reactive, toRefs, defineComponent } from "vue";
import Menu from "./Menu.vue";

export default defineComponent({
  name: "Popup",
  components: {
    Menu
  },
  setup: () => {
    let data = reactive({
      show: false,
      menuList: [],
      parentKey: '1',
      x: 0,
      y: 0
    });
    let dataAsRefs = toRefs(data);

    return {
      ...dataAsRefs,
    };
  },
});
</script>

<style lang="scss" scoped>
.menu-submenu-popup {
  position: fixed;
  left: 50%;
  top: 50%;
  z-index: 99;
  color: rgba(255, 255, 255, 0.65);
  background-color: #001529;
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
</style>
