<template>
  <div class="fashion-add">
    <FashionMsg :fashionMsgData="fashionMsgData"/>
  </div>
</template>

<script>
export default {
  name: "FashionAdd",
  components: {
    FashionMsg: () => import('../components/FashionMsg')
  },
  data() {
    return {
      fashionMsgData: {
        type: 'add',
        title: '新增文章'
      }
    }
  }
};
</script>
