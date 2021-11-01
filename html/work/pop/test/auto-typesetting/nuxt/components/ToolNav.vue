<template>
  <div class="tool-nav-com" @click.stop="() => { return false }">
    <el-button-group type="vertical">
      <el-tooltip effect="dark" content="加粗" placement="right">
        <el-button size="mini">
          <i class="iconfont iconzitijiacu"></i>
        </el-button>
      </el-tooltip>
      <el-tooltip effect="dark" content="左对齐" placement="right">
        <el-button size="mini">
          <i class="iconfont iconzuoduiqi"></i>
        </el-button>
      </el-tooltip>
      <el-tooltip effect="dark" content="中对齐" placement="right">
        <el-button size="mini">
          <i class="iconfont iconjuzhongduiqi"></i>
        </el-button>
      </el-tooltip>
      <el-tooltip effect="dark" content="右对齐" placement="right">
        <el-button size="mini">
          <i class="iconfont iconyouduiqi"></i>
        </el-button>
      </el-tooltip>
    </el-button-group>
  </div>
</template>

<script>
export default {
  name: "ToolNavCom",
  data() {
    return {
    };
  },
  computed: {
  },
  mounted() {
  },
  methods: {
  }
};
</script>

<style lang="scss" scoped>
.tool-nav-com {
  position: absolute;
  top: 0;
  right: -40px;
  z-index: 7;
  transform-origin: 0 0;
  .el-button-group[type=vertical] {
    display: flex;
    flex-direction: column;
    & > .el-button {
      padding: 7px 10px;
      &:first-child {
        border-top-right-radius: 3px;
        border-bottom-left-radius: 0;
      }
      &:last-child {
        border-top-right-radius: 0;
        border-bottom-left-radius: 3px;
      }
      &:not(:last-child) {
        margin-right: 0;
        margin-bottom: -1px;
      }
    }
  }
}
</style>
