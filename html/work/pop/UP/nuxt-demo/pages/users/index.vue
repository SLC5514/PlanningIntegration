<template>
  <section class="users-index">
    Users-index
  </section>
</template>

<script>
export default {
  name: 'UsersIndex',
  mounted() {
    console.log(this.$route)
  }
}
</script>

<style lang="less">
// 页面过渡动效
.page-enter-active,
.page-leave-active {
  transition: opacity 0.5s;
}
.page-enter,
.page-leave-active {
  opacity: 0;
}
</style>
