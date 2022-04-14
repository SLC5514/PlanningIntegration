<template>
    <div class="product-compile">
      <ProductMsg :productMsgData="productMsgData"/>
    </div>
</template>

<script>
export default {
  name: "ProductCompile",
  components: {
    Tinymce: () => import('../components/Tinymce'),
    ProductMsg: () => import('../components/ProductMsg')
  },
  data() {
    return {
      productMsgData: {
        type: 'compile',
        title: '编辑产品信息',
      }
    }
  }
};
</script>
