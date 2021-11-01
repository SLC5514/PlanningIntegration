<template>
  <div class="container about-name-id">
    <Common>
      <div
        slot="title"
        class="title"
      >
        About-name-id
      </div>
      <div slot="subtitle">
        A about-name-id page
      </div>
    </Common>
  </div>
</template>

<script>
export default {
  name: 'AboutNameId',
  head: {
    title: 'About_name_id page',
    meta: [
      {
        hid: 'keywords',
        name: 'keywords',
        content: 'About-name-id,about-name-id'
      },
      {
        hid: 'description',
        name: 'description',
        content: 'A about-name-id page'
      }
    ]
  },
  components: {
    Common: () => import('~/components/Common')
  },
  // 路由参数校验
  validate({ params }) {
    if (params.name && !/^[A-Za-z]+$/.test(params.name)) {
      return false
    }
    if (params.id && !/^\d+$/.test(params.id)) {
      return false
    }
    return true
  },
  mounted() {
    console.log(this.$route)
  }
}
</script>

<style lang="less" scoped>
.about-name-id {
  .title {
    color: #666;
  }
}
</style>
