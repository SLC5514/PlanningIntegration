<template>
  <el-dialog :title="addSubReportForm.idx > -1 ? '编辑子报告' : '新增子报告'" :visible="addSubReportVisible" width="815px" class="add-report-dialog" top="10vh" header-border @close="setAddSubReport(false)">
    <el-form :model="addSubReportForm" :rules="rules" ref="addSubReportForm" label-width="100px" @keyup.enter.native="addSubReportConform">
      <el-form-item label="子报告标题:" prop="title">
        <el-input v-model="addSubReportForm.title"></el-input>
      </el-form-item>
      <el-form-item label="选择模板:" prop="grid" class="content-visible">
        <el-tabs v-model="addSubReportForm.type">
          <el-tab-pane label="模板中心" name="0">
            <div class="tab-tpl-box">
              <el-radio-group v-model="addSubReportForm.tag">
                <el-radio-button label="0">全部</el-radio-button>
                <el-radio-button label="1">标签1</el-radio-button>
                <el-radio-button label="2">标签2</el-radio-button>
                <el-radio-button label="3">标签3</el-radio-button>
              </el-radio-group>
              <div class="card-box">
                <el-card :class="{on: addSubReportForm.grid == v.id}" shadow="hover" v-for="v in templates" :key="v.id" @click.native="selGrids(v)">
                  <el-image :src="v.thumb" fit="contain"></el-image>
                </el-card>
              </div>
            </div>
          </el-tab-pane>
          <el-tab-pane label="我的模板" name="1">我的模板</el-tab-pane>
        </el-tabs>
      </el-form-item>
      <el-form-item label="子报告标签:" prop="tag" class="arrangement-v">
        <el-input v-model="addSubReportForm.tag">
          <el-button slot="append" type="primary">添加</el-button>
        </el-input>
        <div class="tag-box">
          <el-tag>标签1</el-tag>
          <el-tag>标签2</el-tag>
          <el-tag>标签3</el-tag>
        </div>
      </el-form-item>
      <el-form-item label="添加水印:" prop="watermark">
        <el-radio-group v-model="addSubReportForm.watermark">
          <el-radio :label="0">无水印</el-radio>
          <el-radio :label="1">有水印</el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>
    <span slot="footer" style="display:block;text-align:center;">
      <el-button @click="setAddSubReport(false)">取 消</el-button>
      <el-button type="primary" :loading="loading" v-preventReClick @click="addSubReportConform">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import defStyle from '@/common/elementModel';

const { eleDefCommonStyle, pageCoverStyle, titleCoverStyle, infoCoverStyle, gridCoverStyle } = defStyle;

const defAddSubReportForm = {
  idx: -1,
  title: '',
  type: '0',
  tag: '',
  grid: '',
  watermark: 0
};

export default {
  data() {
    return {
      loading: false,
      // 新增子报告弹框
      addSubReportForm: {
        ...defAddSubReportForm
      },
      copySubReportData: null, // 复制子报告
      // 验证规则
      rules: {
        title: [
          { required: true, message: '请输入子报告标题', trigger: 'blur' }
        ],
        grid: [
          { required: true, message: '请选择模板', trigger: 'change' }
        ],
      },
    };
  },
  computed: {
    ...mapGetters(['templates', 'addSubReportVisible', 'reportIdx', 'gridScale', 'scale']),
  },
  mounted() {
    this.$bus.$on('showAddSubReportDialog', this.showAddSubReportDialog);
    this.$bus.$on('editSubReport', this.editSubReport);
    this.$bus.$on('removeSubReport', this.removeSubReport);
    this.$bus.$on('copySubReport', this.copySubReport);
  },
  methods: {
    ...mapMutations(['setAddSubReport', 'setReportItemData']),
    // 新增子报告弹窗
    showAddSubReportDialog() {
      this.setAddSubReport(true);
      // 格式化
      this.$nextTick(() => {
        this.$refs['addSubReportForm'] && this.$refs['addSubReportForm'].resetFields();
        this.addSubReportForm = Object.assign(this.addSubReportForm, defAddSubReportForm);
      })
    },
    // 新增子报告
    addSubReportConform(e) {
      if (this.loading) {
        return false;
      }
      this.$refs['addSubReportForm'].validate((valid) => {
        if (valid) {
          this.loading = true;
          this.setAddSubReport(false);
          // 获取选中格子数据
          let ogGrids = [];
          for (let i = 0; i < this.templates.length; i++) {
            if (this.templates[i].id == this.addSubReportForm.grid) {
              ogGrids = this.templates[i].grids;
              break;
            }
          }
          // 格式化格子数据
          let grids = this.$parent.formatGrids(ogGrids);
          // 获取报告elements中格子的下标
          const eleGridIdx = this.$parent.getEleGridIdx(this.addSubReportForm.idx);
          // 操作
          if (this.addSubReportForm.idx > -1) {
            // 其余修改
            this.setReportItemData({ keys: [this.addSubReportForm.idx], key: 'title', val: this.addSubReportForm.title });
            if (eleGridIdx > -1) { // 格子修改
              this.setReportItemData({ keys: [this.addSubReportForm.idx, 'elements', eleGridIdx], key: 'id', val: this.addSubReportForm.grid });
              this.setReportItemData({ keys: [this.addSubReportForm.idx, 'elements', eleGridIdx], key: 'grids', val: grids });
            } else { // 格子新增
              this.setReportItemData({ keys: [this.addSubReportForm.idx, 'elements'], val: {
                id: this.addSubReportForm.grid,
                type: 'grid-box',
                grids: grids
              } });
            }
          } else { // 新增
            this.setReportItemData({ val: {
              uuid: this.$createUUID(),
              type: 'page',
              title: this.addSubReportForm.title,
              style: {
                ...pageCoverStyle
              },
              elements: [
                {
                  uuid: this.$createUUID(),
                  type: 'text',
                  value: this.addSubReportForm.title,
                  valueType: 'String',
                  style: {
                    ...eleDefCommonStyle,
                    ...titleCoverStyle
                  }
                },
                {
                  uuid: this.$createUUID(),
                  type: 'text',
                  value: '简单的线条和色块构成特点十足的牛牛形象图案受到小朋友们的喜爱。该类型柔和色调的图案适合运用于针织类面料的满版印花，适合呈现在如家居服，连衣裙，婴幼童爬服哈衣等款式上。',
                  valueType: 'String',
                  style: {
                    ...eleDefCommonStyle,
                    ...infoCoverStyle
                  }
                },
                {
                  id: this.addSubReportForm.grid,
                  type: 'grid-box',
                  grids: grids
                },
                {
                  uuid: this.$createUUID(),
                  type: 'image',
                  style: {
                    ...eleDefCommonStyle,
                    position: 'absolute',
                    left: 40,
                    top: 40,
                    width: 300,
                    height: 200,
                    lineHeight: 1,
                  },
                  clip: {
                    w: 0,
                    h: 0,
                    x: 0,
                    y: 0,
                    scale: 1,
                    image: 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg'
                  }
                },
              ]
            } });
            this.$nextTick(() => {
              this.$parent.windowResize();
            })
          }
          this.loading = false;
        } else {
          console.log('error submit!!');
          this.loading = false;
          return false;
        }
      });
    },
    // 编辑子报告
    editSubReport(val, idx) {
      this.setAddSubReport(true);
      // 格式化
      const eleGridIdx = this.$parent.getEleGridIdx(idx);
      this.addSubReportForm.idx = idx;
      this.addSubReportForm.title = val.title || '';
      this.addSubReportForm.grid = eleGridIdx > -1 && val.elements[eleGridIdx].id || '';
    },
    // 删除子报告
    removeSubReport(idx) {
      this.setReportItemData({ key: idx });
      this.$nextTick(() => {
        this.$parent.windowResize();
      })
    },
    // 复制子报告
    copySubReport(val) {
      this.copySubReportData = val;
      this.$message.success('复制成功！');
    },
    // 选择模板
    selGrids(val) {
      this.addSubReportForm.grid = val.id;
    },
  }
};
</script>

<style lang="scss" scoped>
.add-report-dialog {
  .tab-tpl-box {
    margin-left: -100px;
    padding: 20px;
    border-radius: 4px;
    border: 1px solid #DCDFE6;
    .card-box {
      max-height: 300px;
      display: flex;
      flex-wrap: wrap;
      margin-left: -10px;
      overflow: auto;
      .el-card {
        width: 64px;
        margin-left: 10px;
        margin-top: 10px;
        cursor: pointer;
        &.on {
          border: 1px solid #409EFF;
        }
      }
    }
  }
}
</style>

<style lang="scss">
.el-dialog__wrapper[header-border] {
  .el-dialog__header {
    border-bottom: 1px solid #DCDFE6;
  }
}
.add-report-dialog {
  .content-visible {
    .el-tabs__content {
      overflow: visible;
    }
  }
  .tab-tpl-box {
    .el-card .el-card__body {
      display: flex;
      padding: 0;
    }
  }
  .arrangement-v .el-form-item__content {
    display: flex;
    .el-input {
      flex: 1;
      margin-right: 10px;
    }
  }
}
</style>
