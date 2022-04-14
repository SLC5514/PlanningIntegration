<template>
  <section class="container">
    <Header />
  </section>
</template>

<script>
export default {
  components: {
    Header: () => import('~/components/Header')
  }
}
</script>

<style lang="less" scoped>
.container {
  min-width: 1200px;
  min-height: 100vh;
  height: 200vh;
  background: url('~assets/img/home-bg.jpg') no-repeat center top;
  background-size: 100%;
  background-attachment: fixed;
}
</style>
