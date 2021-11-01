<template>
  <div class="header-com">
    <el-page-header @back="goBack" title="返回列表"></el-page-header>
    <el-button>撤销</el-button>
    <el-button>前进</el-button>
    <el-button>图片</el-button>
    <el-button>导入PSD</el-button>
    <el-button>下载</el-button>
    <el-button>另存为</el-button>
    <el-button>预览</el-button>
    <el-button type="primary">保存</el-button>
  </div>
</template>

<script>
export default {
  name: "HeaderCom",
  data() {
    return {};
  },
  methods: {
    goBack() {
      console.log("go back");
    }
  }
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";

.header-com {
  height: $header-height;
  line-height: $header-height;
  padding: 0 20px;
  text-align: center;
  box-shadow: $box-shadow;
  position: relative;
  background: #fff;
  z-index: 9;
  .el-page-header {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
  }
}
</style>
