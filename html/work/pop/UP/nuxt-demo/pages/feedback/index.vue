<template>
  <div class="container feedback">
    <Common>
      <div
        slot="title"
        class="title"
      >
        Feedback
      </div>
      <div slot="subtitle">
        A feedback page
      </div>
    </Common>
  </div>
</template>

<script>
export default {
  name: 'Feedback',
  layout: 'individuation',
  head: {
    title: 'Feedback page',
    meta: [
      { hid: 'keywords', name: 'keywords', content: 'Feedback,feedback' },
      { hid: 'description', name: 'description', content: 'A feedback page' }
    ]
  },
  components: {
    Common: () => import('~/components/Common')
  },
  mounted() {
    console.log(this.$route)
  }
}
</script>

<style lang="less" scoped>
.feedback {
  .title {
    color: #999;
  }
}
</style>
