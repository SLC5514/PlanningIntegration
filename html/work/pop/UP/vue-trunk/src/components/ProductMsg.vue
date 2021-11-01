<template>
    <div class="product-msg">
      <el-container>
        <el-header>
          <h1 class="title">{{productMsgData.title}}</h1>
        </el-header>
        <el-form v-loading="loading" :model="productForm" :rules="rules" ref="productForm" label-width="110px">
          <el-form-item label="产品名称" prop="name">
            <el-input v-model="productForm.name" maxlength="10"></el-input>
          </el-form-item>
          <el-form-item label="产品描述" prop="abstract">
            <el-input
              type="textarea"
              style="width:385px;"
              :autosize="{ minRows: 3, maxRows: 3}"
              maxlength="75"
              resize="none"
              v-model="productForm.abstract"></el-input>
          </el-form-item>
          <el-row>
            <el-col :span="9" style="width:340px;">
              <el-form-item label="自动上架时间" prop="start_time">
                <el-date-picker
                  v-model="productForm.start_time"
                  type="datetime"
                  @change="timeChange($event, 'start_time')"
                  value-format="yyyy-MM-dd HH:mm"
                  format="yyyy-MM-dd HH:mm"
                  placeholder="选择日期时间">
                </el-date-picker>
              </el-form-item>
            </el-col>
            <el-col :span="9">
              <el-form-item label="自动下架时间" prop="end_time">
                <el-date-picker
                  v-model="productForm.end_time"
                  type="datetime"
                  @change="timeChange($event, 'end_time')"
                  value-format="yyyy-MM-dd HH:mm"
                  format="yyyy-MM-dd HH:mm"
                  placeholder="选择日期时间">
                </el-date-picker>
              </el-form-item>
            </el-col>
          </el-row>
          <el-form-item label="产品排序" prop="sort">
            <el-input v-model.number="productForm.sort" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))"></el-input>
          </el-form-item>
          <el-form-item label="产品图片" prop="cover">
            <div class="product-upload-banner">
              <el-upload
                class="avatar-uploader"
                :action="$productUploadImg + '/product/uplaodCover/'"
                name="cover"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :on-error="handleAvatarError"
                :before-upload="beforeAvatarUpload">
                <el-button size="small" type="info">上传banner图</el-button>
              </el-upload>
              <div class="product-upload-memo">
                <span class="picture-memo picm1">尺寸 690*390</span>
                <span class="picture-memo picm2">大小 5M 以内</span>
              </div>
            </div>
            <div class="product-banner-box" v-loading="loadingImg">
              <img v-if="productForm.cover" :src="STATIC_URL + productForm.cover" @click="handlePictureCardPreview">
            </div>
            <el-dialog :visible.sync="dialogVisible" title="预览">
              <img width="100%" :src="STATIC_URL + productForm.cover" alt="">
            </el-dialog>
          </el-form-item>
          <el-form-item label="产品详情" prop="content">
            <Tinymce ref="tinymceRef" v-if="tinymceType" :tinymceData="tinymceData" :content="productForm.content"/>
          </el-form-item>
          <el-form-item style="text-align:right;">
            <el-button @click="cancelBack">取消并返回</el-button>
            <el-button type="primary" @click="saveAll('productForm')">保存全部</el-button>
          </el-form-item>
        </el-form>
      </el-container>
    </div>
</template>

<script>
export default {
  name: "ProductMsg",
  props: ['productMsgData'],
  components: {
    Tinymce: () => import('../components/Tinymce')
  },
  data() {
    const validateSort = (rule, value, callback) => {
      if (value === '' || value > 999) {
        callback(new Error(rule.message));
      } else {
        callback();
      }
    }
    return {
      tinymceData: {
        img: '/product/uploadImg/',
        video: '/product/uploadVideo/'
      },
      loading: false,
      loadingImg: false,
      tinymceType: false,
      STATIC_URL: '',
      productForm: {
        name: '',
        abstract: '',
        start_time: '',
        end_time: '',
        sort: 0,
        cover: '',//https://cn.vuejs.org/images/logo.png',
        content: ''
      },
      rules: {
        name: [
          { required: true, message: '产品名称限制在10个字以内，10个字以上的缩略图中会被隐藏', trigger: 'blur' },
          { min: 1, max: 10, trigger: 'blur' }
        ],
        abstract: [
          { required: true, message: '产品描述作为产品简介，字数限制在3行75个字以内，每行25个字', trigger: 'blur' },
          { min: 1, max: 75, trigger: 'blur' }
        ],
        start_time: [
          { required: true, message: '请选择上架时间', trigger: 'blur' },
        ],
        end_time: [
          { required: true, message: '请选择下架时间', trigger: 'blur' },
        ],
        sort: [
          {  required: true, validator: validateSort, message: '请输入0~999，数字大的排在最前面，数字相同的按照创建时间倒序排在最前面，默认为0', trigger: 'blur' }
        ],
        cover: [
          { required: true, message: '请上传图片' }
        ],
        content: [
          { required: true, message: '请输入产品详情', trigger: 'blur' }
        ]
      },
      dialogVisible: false,
    }
  },
  created() {
    this.productMsgData.type == 'compile' ? this.getProductList({ id: this.$router.currentRoute.query.id }) : this.tinymceType = true;
  },
  methods: {
    timeChange(time, type) {// 选择时间
      console.log('changeTime!');
      this.productForm[type] = time && time + ':' + new Date().getSeconds();
    },
    handleAvatarSuccess(res, file) {//上传成功
      this.STATIC_URL = file.response.data.STATIC_URL;
      this.productForm.cover = file.response.data.path;
      this.loadingImg = false;
    },
    handleAvatarError(err, file) {//上传失败
      this.$message.error('上传失败!');
      this.loadingImg = false;
    },
    handlePictureCardPreview() {//预览
      this.dialogVisible = true;
    },
    beforeAvatarUpload(file) {//上传之前
      const isImg = file.type.indexOf('image') !== -1;
      const isLt5M = file.size / 1024 / 1024 < 5;
      if (!isImg) {
        this.$message.error('请上传图片!');
        return isImg;
      }
      if (!isLt5M) {
        this.$message.error('上传头像图片大小不能超过 5MB!');
        return isLt5M;
      }
      this.loadingImg = true;
      return isImg && isLt5M;
    },
    cancelBack() {// 取消并返回
      console.log('cancelBack');
      this.$router.go(-1);
    },
    saveAll(formName) {// 保存全部
      console.log('saveAll');
      this.productForm.content = this.$refs.tinymceRef.getContent();
      this.$refs[formName].validate((valid) => {
        if (valid) {
          switch (this.productMsgData.type) {
            case 'add':
              this.addProductMsg(this.productForm);
            break;
            case 'compile':
              this.compileProductMsg(this.productForm);
            break;
            default: break;
          }
        } else {
          console.log('error saveAll!');
          return false;
        }
      });
    },
    addProductMsg(data) {// 新增产品
      this.loading = true;
      this.$post('/product/doAdd/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '产品信息新增失败！');
        } else {
          this.$message.success('产品信息新增成功！');
          this.$router.replace({
            name: 'product',
            params: {
              load: true
            }
          })
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：产品信息新增失败！');
      })
    },
    getProductList(data) {// 获取产品信息
      this.loading = true;
      this.$get('/product/getProduct/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '产品信息获取失败！');
        } else {
          this.STATIC_URL = response.info.STATIC_URL;
          this.productForm = response.data;
          if (new Date(response.data.start_time) == 'Invalid Date') {
            this.productForm.start_time = '';
          }
          if (new Date(response.data.end_time) == 'Invalid Date') {
            this.productForm.end_time = '';
          }
          this.tinymceType = true;
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：产品信息获取失败！');
      })
    },
    compileProductMsg(data) {// 修改产品信息
      this.loading = true;
      this.$post('/product/doEdit/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '产品信息修改失败！');
        } else {
          this.$message.success('产品信息修改成功！');
          this.$router.replace({
            name: 'product',
            params: {
              load: true
            }
          })
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：产品信息修改失败！');
      })
    }
  }
};
</script>

<style scoped lang="less">
  .product-msg {
    height: 100%;
    color: #303133;
    .el-container {
      height: 100%;
      .el-header {
        height: auto!important;
        padding: 0;
        .title {
          font-size: 30px;
          margin-bottom: 20px;
        }
      }
      .el-form {
        max-width: 1200px;
        .product-upload-banner {
          display: inline-block;
          position: relative;
          margin-bottom: 20px;
          .product-upload-memo {
            width: 200px;
            position: absolute;
            top: 0;
            left: 110px;
            .picture-memo {
              margin-left: 10px;
              color: #909399;
            }
          }
        }
        .product-banner-box {
          width: 250px;
          height: 200px;
          border: 1px dashed #ccc;
          background: #fff;
          vertical-align: middle;
          display: table-cell;
          text-align: center;
          img {
            width: 100%;
            height: 100%;
            display: inherit;
            cursor: pointer;
          }
        }
      }
    }
  }
</style>
