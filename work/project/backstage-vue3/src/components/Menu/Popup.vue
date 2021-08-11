<template>
  <div class="menu-submenu-popup" v-if="show">
    <ul class="menu">
      <li class="menu-item" v-for="(v, i) in menuList" :key="i">{{v.name}}</li>
    </ul>
  </div>
</template>

<script lang="ts">
import { ref, reactive, toRefs, defineComponent } from "vue";

export default defineComponent({
  name: "Popup",
  setup: () => {
    let data = reactive({
      show: false,
      menuList: [],
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
  & > ul {
    & > li {}
  }
}
</style>