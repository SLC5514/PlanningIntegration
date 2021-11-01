<template>
  <div :class="{'footer-com': true, 'no-collapse': !isCollapse}">
    <div class="og-size">
      <input type="text" value="1200" readonly> X <input type="text" value="820" readonly> PX
    </div>
    <div class="paper-size">
      <el-input-number v-model="iptScale" :min="50" :max="200" :step="10" @change="handleChangeScale" label="画布尺寸"></el-input-number>
      <el-button @click="ogSize">实际大小</el-button>
      <el-button @click="autoSize">合适画布</el-button>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';

export default {
  name: "FooterCom",
  data() {
    return {
      iptScale: 100
    };
  },
  computed: {
    ...mapGetters(['isCollapse', 'scale']),
  },
  watch: {
    scale() {
      this.iptScale = this.scale.toFixed(2);
    }
  },
  mounted() {
    this.iptScale = this.scale.toFixed(2);
  },
  methods: {
    ...mapMutations(['scaleChange', 'isResizeChange']),
    handleChangeScale(value) {
      this.isResizeChange(false);
      this.scaleChange(value);
    },
    ogSize() {
      this.scaleChange(100);
    },
    autoSize() {
      this.isResizeChange(true);
      this.$bus.$emit('autoSize');
    }
  }
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";

.footer-com {
  height: $footer-height;
  line-height: $footer-height;
  position: absolute;
  left: $aside-width;
  right: $aside-width;
  bottom: 0;
  padding: 0 20px;
  background: #fff;
  box-shadow: $box-shadow;
  z-index: 7;
  overflow: hidden;
  display: flex;
  justify-content: space-between;
  transition: left 0.3s ease;
  &.no-collapse {
    left: $aside-nocollapse-width;
  }
  .og-size {
    input {
      width: 40px;
      height: 28px;
      line-height: 28px;
      outline: none;
      border: none;
      color: #fff;
      background: #333;
      text-align: center;
      border-radius: 5px;
    }
  }
  .paper-size {
    .el-input-number {
      width: 150px;
    }
    .el-button + .el-button {
      margin-left: 0px;
    }
  }
}
</style>
