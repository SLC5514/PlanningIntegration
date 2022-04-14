<template>
  <el-dialog :title="codePopUpData.title"
    :visible.sync="codePopUpType"
    :before-close="() => this.onCodePopUpClose()">
    <div class="CodePopUp">
      <div class="code-box">
        <img v-if="codePopUpData.title == '新增员工账号'" src="../assets/add_staff.png" alt="code">
        <img v-else :src="codePopUpData.codesrc" alt="code">
      </div>
      <div class="content-txt">
        <div v-for="(v, i) in codePopUpData.content" :key="i">{{v}}</div>
      </div>
    </div>
  </el-dialog>
</template>

<script>
  export default {
    name: 'CodePopUp',
    props: {
      codePopUpType: {
        type: Boolean,
        default: false
      },
      codePopUpData: Object,
      onCodePopUpClose: Function
    }
  }
</script>

<style scoped lang="less">
  .CodePopUp {
    text-align: center;
    .code-box {
      margin-bottom: 30px;
      img {
        width: 155px;
        height: 155px;
      }
    }
    .content-txt {
      line-height: 30px;
    }
  }
</style>
