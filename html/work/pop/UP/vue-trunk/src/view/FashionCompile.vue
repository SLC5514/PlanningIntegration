<template>
  <div class="fashion-compile">
    <FashionMsg :fashionMsgData="fashionMsgData"/>
  </div>
</template>

<script>
export default {
  name: "FashionCompile",
  components: {
    FashionMsg: () => import('../components/FashionMsg')
  },
  data() {
    return {
      fashionMsgData: {
        type: 'compile',
        title: '编辑文章信息'
      }
    }
  }
};
</script>
