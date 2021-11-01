<template>
    <div class="product-add">
      <ProductMsg :productMsgData="productMsgData"/>
    </div>
</template>

<script>
export default {
  name: "ProductAdd",
  components: {
    Tinymce: () => import('../components/Tinymce'),
    ProductMsg: () => import('../components/ProductMsg')
  },
  data() {
    return {
      productMsgData: {
        type: 'add',
        title: '新增产品信息',
      }
    }
  }
};
</script>
