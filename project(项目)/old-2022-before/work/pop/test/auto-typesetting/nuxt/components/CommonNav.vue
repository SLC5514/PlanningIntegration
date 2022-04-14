<template>
  <div class="common-nav-com">
    <el-collapse v-model="commonData.collapse">
      <el-collapse-item title="位置与旋转" name="1">
        <ul class="list-box">
          <li>
            <label>位置：</label>
            <el-input-number v-model="commonData.left" controls-position="right" size="mini"></el-input-number>
            <el-input-number v-model="commonData.top" controls-position="right" size="mini"></el-input-number>
          </li>
          <li>
            <label>旋转：</label>
            <el-slider v-model="commonData.rotate" :min="0" :max="360" show-input input-size="mini"></el-slider>
          </li>
        </ul>
      </el-collapse-item>
      <el-collapse-item title="边框边距" name="2">
        <ul class="list-box">
          <li>
            <label>边框颜色：</label>
            <el-color-picker v-model="commonData.borderColor" show-alpha size="mini"></el-color-picker>
          </li>
          <li>
            <label>边框圆角：</label>
            <el-input-number v-model="commonData.borderRadius" :min="0" :max="50" controls-position="right" size="mini"></el-input-number>
          </li>
          <li>
            <label>边框：</label>
            <el-input-number v-model="commonData.borderWidth" :min="0" :max="9" controls-position="right" size="mini"></el-input-number>
            <div class="border-width-preview"><div :style="{
              'border-width': commonData.borderWidth + 'px',
              'border-radius': commonData.borderRadius + 'px',
              'border-color': commonData.borderColor,
            }"></div></div>
          </li>
          <li>
            <label>上下边距：</label>
            <el-input-number v-model="commonData.paddingTop" controls-position="right" size="mini"></el-input-number>
            <el-input-number v-model="commonData.paddingBottom" controls-position="right" size="mini"></el-input-number>
          </li>
          <li>
            <label>左右边距：</label>
            <el-input-number v-model="commonData.paddingLeft" controls-position="right" size="mini"></el-input-number>
            <el-input-number v-model="commonData.paddingRight" controls-position="right" size="mini"></el-input-number>
          </li>
        </ul>
      </el-collapse-item>
      <el-collapse-item title="阴影样式" name="3">
        <ul class="list-box">
          <li>
            <label>阴影颜色：</label>
            <el-color-picker v-model="commonData.shadowColor" show-alpha size="mini"></el-color-picker>
          </li>
          <li>
            <label>阴影偏移：</label>
            <el-input-number v-model="commonData.shadowH" controls-position="right" size="mini"></el-input-number>
            <el-input-number v-model="commonData.shadowV" controls-position="right" size="mini"></el-input-number>
          </li>
          <li>
            <label>阴影大小：</label>
            <el-input-number v-model="commonData.shadowBlur" controls-position="right" size="mini"></el-input-number>
          </li>
        </ul>
      </el-collapse-item>
      <el-collapse-item title="其他" name="4">
        <ul class="list-box">
          <li>
            <label>跳转链接：</label>
            <el-input v-model="commonData.link" placeholder="请输入跳转链接" size="mini"></el-input>
          </li>
        </ul>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
export default {
  name: "CommonNavCom",
  props: ['commonData'],
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
