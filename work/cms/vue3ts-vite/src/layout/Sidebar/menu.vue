<script lang="ts" setup>
defineProps<{
  list: Array<AnyObject>;
}>();
</script>

<template>
  <ul class="sidebar-menu">
    <template v-for="(item, index) in list">
      <li v-if="!item?.meta?.hidden && !item?.meta?.hiddenitem" :key="index">
        <router-link :to="item.path">{{ item.name }}</router-link>
        <Menu
          v-if="item.children && item.children.length > 0"
          :list="item.children"
        />
      </li>
      <Menu :key="index"
        v-if="item?.meta?.hiddenitem && item.children && item.children.length > 0"
        :list="item.children"
      />
    </template>
  </ul>
</template>

<style lang="scss" scoped>
.sidebar-menu {
  & > li > .sidebar-menu {
    padding-left: 10px;
  }
  li {
    margin: 2px 0;
  }
  a {
    display: block;
    line-height: 35px;
    padding: 0 10px;
    border-radius: 5px;
    text-overflow: ellipsis;
    overflow: hidden;
    transition: color 0.3s, background-color 0.3s;
    &:hover {
      background-color: #eee;
    }
    &.router-link-active {
      background-color: #eee;
    }
    &.router-link-exact-active {
      background-color: #ddd;
    }
  }
}
</style>
