<template>
  <section class="users">
    <nuxt-child />
    <Common>
      <div slot="title">
        Users
      </div>
      <div slot="subtitle">
        A users page
      </div>
    </Common>
  </section>
</template>

<script>
export default {
  name: 'Users',
  components: {
    Common: () => import('~/components/Common')
  },
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
.users {
  .title {
    color: #aaa;
  }
}
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
