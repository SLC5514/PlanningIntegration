<template>
  <section class="container">
    <Common>
      Home
    </Common>
  </section>
</template>

<script>
export default {
  name: 'Home',
  layout: 'blog',
  components: {
    Common: () => import('~/components/Common.vue')
  },
  data() {
    return {}
  },
  computed: {},
  mounted() {},
  methods: {}
}
</script>

<style lang="less">
</style>
