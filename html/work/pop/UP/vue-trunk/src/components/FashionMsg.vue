<template>
    <div class="fashion-msg">
      <el-container>
        <el-header>
          <h1 class="title">{{fashionMsgData.title}}</h1>
        </el-header>
        <el-form v-loading="loading" :model="fashionForm" :rules="rules" ref="fashionForm" label-width="110px">
          <el-form-item label="文章标题" prop="title">
            <el-input v-model="fashionForm.title" maxlength="64"></el-input>
          </el-form-item>
          <el-form-item label="文章描述" prop="abstract">
            <el-input
              type="textarea"
              style="width:595px;"
              :autosize="{ minRows: 3, maxRows: 3}"
              maxlength="120"
              resize="none"
              v-model="fashionForm.abstract"></el-input>
          </el-form-item>
          <el-form-item label="文章来源" prop="origin">
            <el-input v-model="fashionForm.origin" maxlength="10"></el-input>
          </el-form-item>
          <el-form-item label="文章栏目" prop="type">
            <el-select v-model="fashionForm.type" placeholder="选择栏目">
              <el-option v-for="(v, i) in typeArr" :key="i" :label="v.name" :value="v.type"></el-option>
            </el-select>
          </el-form-item>
          <el-row>
            <el-col :span="9" style="width:340px;">
              <el-form-item label="自动上线时间" prop="start_time">
                <el-date-picker
                  v-model="fashionForm.start_time"
                  type="datetime"
                  @change="timeChange($event, 'start_time')"
                  value-format="yyyy-MM-dd HH:mm"
                  format="yyyy-MM-dd HH:mm"
                  placeholder="选择日期时间">
                </el-date-picker>
              </el-form-item>
            </el-col>
            <el-col :span="9">
              <el-form-item label="自动下线时间" prop="end_time">
                <el-date-picker
                  v-model="fashionForm.end_time"
                  type="datetime"
                  @change="timeChange($event, 'end_time')"
                  value-format="yyyy-MM-dd HH:mm"
                  format="yyyy-MM-dd HH:mm"
                  placeholder="选择日期时间">
                </el-date-picker>
              </el-form-item>
            </el-col>
          </el-row>
          <el-form-item label="文章排序" prop="sort">
            <el-input v-model.number="fashionForm.sort" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))"></el-input>
          </el-form-item>
          <el-form-item label="文章封面" prop="cover">
            <div class="fashion-upload-banner">
              <el-upload
                class="avatar-uploader"
                :action="$productUploadImg + '/article/uplaodCover/'"
                name="cover"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :on-error="handleAvatarError"
                :before-upload="beforeAvatarUpload">
                <el-button size="small" type="info">上传封面图</el-button>
              </el-upload>
              <div class="fashion-upload-memo">
                <span class="picture-memo picm1">尺寸 690*390</span>
                <span class="picture-memo picm2">大小 5M 以内</span>
              </div>
            </div>
            <div class="fashion-banner-box" v-loading="loadingImg">
              <img v-if="fashionForm.cover" :src="STATIC_URL + fashionForm.cover" @click="handlePictureCardPreview">
            </div>
            <el-dialog :visible.sync="dialogVisible" title="预览">
              <img width="100%" :src="STATIC_URL + fashionForm.cover" alt="">
            </el-dialog>
          </el-form-item>
          <el-form-item label="文章详情" prop="content">
            <Tinymce ref="tinymceRef" v-if="tinymceType" :tinymceData="tinymceData" :content="fashionForm.content"/>
          </el-form-item>
          <el-form-item style="text-align:right;">
            <el-button @click="cancelBack">取消并返回</el-button>
            <el-button type="primary" @click="saveAll('fashionForm')">保存全部</el-button>
          </el-form-item>
        </el-form>
      </el-container>
    </div>
</template>

<script>
export default {
  name: "FashionMsg",
  props: ['fashionMsgData'],
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
        img: '/article/uploadImg/',
        video: '/article/uploadVideo/'
      },
      loading: false,
      loadingImg: false,
      tinymceType: false,
      STATIC_URL: '',
      typeArr: [
        {
          type: '1',
          name: '时尚头条'
        },
        {
          type: '2',
          name: '流行动态'
        },
        {
          type: '3',
          name: '信息荟萃'
        },
        {
          type: '4',
          name: '朝闻天下'
        }
      ],
      fashionForm: {
        id: '',
        title: '',
        abstract: '',
        origin: '',
        type: '',
        start_time: '',
        end_time: '',
        sort: 0,
        cover: '',//https://cn.vuejs.org/images/logo.png',
        content: ''
      },
      rules: {
        title: [
          { required: true, message: '文章标题字数限制在64个字以内', trigger: 'blur' },
          { min: 1, max: 64, trigger: 'blur' }
        ],
        abstract: [
          { required: true, message: '时尚头条最多可显示120字，流行动态/信息荟萃/朝闻天下最多可显示2排', trigger: 'blur' },
          { min: 1, max: 120, trigger: 'blur' }
        ],
        origin: [
          { required: true, message: '文章来源字数限制在10个字以内', trigger: 'blur' },
          { min: 1, max: 10, trigger: 'blur' }
        ],
        type: [
          { required: true, message: '请选择文章栏目', trigger: 'change' }
        ],
        start_time: [
          { required: true, message: '请选择上架时间', trigger: 'blur' }
        ],
        end_time: [
          { required: true, message: '请选择下架时间', trigger: 'blur' }
        ],
        sort: [
          {  required: true, validator: validateSort, message: '请输入0~999，数字大的排在最前面，数字相同的按照创建时间倒序排在最前面，默认为0', trigger: 'blur' }
        ],
        cover: [
          { required: true, message: '请上传图片' }
        ],
        content: [
          { required: true, message: '请输入文章详情', trigger: 'blur' }
        ]
      },
      dialogVisible: false,
    }
  },
  created() {
    this.fashionMsgData.type == 'compile' ? this.getFashionList({ id: this.$router.currentRoute.query.id }) : this.tinymceType = true;
  },
  methods: {
    timeChange(time, type) {// 选择时间
      console.log('changeTime!');
      this.fashionForm[type] = time && time + ':' + new Date().getSeconds();
    },
    handleAvatarSuccess(res, file) {//上传成功
      this.STATIC_URL = file.response.data.STATIC_URL;
      this.fashionForm.cover = file.response.data.path;
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
      this.fashionForm.content = this.$refs.tinymceRef.getContent();
      this.$refs[formName].validate((valid) => {
        if (valid) {
          switch (this.fashionMsgData.type) {
            case 'add':
              this.addFashionMsg(this.fashionForm);
            break;
            case 'compile':
              this.compileFashionMsg(this.fashionForm);
            break;
            default: break;
          }
        } else {
          console.log('error saveAll!');
          return false;
        }
      });
    },
    addFashionMsg(data) {// 新增文章
      this.loading = true;
      this.$post('/article/doAdd/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '文章信息新增失败！');
        } else {
          this.$message.success('文章信息新增成功！');
          this.$router.replace({
            name: 'fashion',
            params: {
              load: true
            }
          })
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：文章信息新增失败！');
      })
    },
    getFashionList(data) {// 获取文章信息
      this.loading = true;
      this.$get('/article/getArticle/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '文章信息获取失败！');
        } else {
          this.STATIC_URL = response.info.STATIC_URL;
          this.fashionForm = response.data;
          if (new Date(response.data.start_time) == 'Invalid Date') {
            this.fashionForm.start_time = '';
          }
          if (new Date(response.data.end_time) == 'Invalid Date') {
            this.fashionForm.end_time = '';
          }
          this.tinymceType = true;
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：文章信息获取失败！');
      })
    },
    compileFashionMsg(data) {// 修改文章信息
      this.loading = true;
      this.$post('/article/doEdit/', data).then((response) => {
        if (response.code !== 0) {
          this.$message.error(response.message || '文章信息修改失败！');
        } else {
          this.$message.success('文章信息修改成功！');
          this.$router.replace({
            name: 'fashion',
            params: {
              load: true
            }
          })
        }
        this.loading = false;
      }).catch((error) => {
        this.$message.error('错误：文章信息修改失败！');
      })
    }
  }
};
</script>

<style scoped lang="less">
  .fashion-msg {
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
        .fashion-upload-banner {
          display: inline-block;
          position: relative;
          margin-bottom: 20px;
          .fashion-upload-memo {
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
        .fashion-banner-box {
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
