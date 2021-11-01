<template>
  <section class="users-id">
    Users-id
  </section>
</template>

<script>
export default {
  name: 'UsersId',
  mounted() {
    console.log(this.$route)
    // 页面加载进度条
    this.$nextTick(() => {
      this.$nuxt.$loading.start()
      setTimeout(() => this.$nuxt.$loading.finish(), 500)
    })
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
