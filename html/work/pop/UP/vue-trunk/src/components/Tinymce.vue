<template>
  <div class='tinymce'>
    <editor id='tinymce' v-model='tinymceHtml' :init='init'></editor>
    <el-dialog
      :visible.sync="uploadVideoType"
      title="视频上传">
      <el-upload
        class="upload-video"
        drag
        accept="png"
        :file-list="[]"
        :action="$productUploadImg + tinymceData.video"
        name="video"
        :on-success="handleAvatarSuccess"
        :on-error="handleAvatarError"
        :before-upload="beforeAvatarUpload">
        <i class="el-icon-upload"></i>
        <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
        <div class="el-upload__tip" slot="tip">只能上传视频文件，且不超过5M</div>
      </el-upload>
    </el-dialog>
  </div>
</template>

<script>
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/modern/theme';
import Editor from '@tinymce/tinymce-vue';
import 'tinymce/plugins/media';
import 'tinymce/plugins/image';
import 'tinymce/plugins/link';
import 'tinymce/plugins/hr';
import 'tinymce/plugins/textcolor';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/colorpicker';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';


export default {
  name: 'tinymce',
  props: ['tinymceData', 'content'],
  components: {Editor},
  data () {
    const _self = this;
    return {
      uploadVideoType: false,
      videoUrl: '',
      field_name: '',
      tinymceHtml: this.content,
      init: {
        language_url: '/static/tinymce/langs/zh_CN.js',
        language: 'zh_CN',
        skin_url: '/static/tinymce/skins/lightgray',
        height: 800,
        selector: "textarea",
        theme:"modern",
        skin:"lightgray",
        menubar:false,
        file_browser_callback_types: 'media',
        file_browser_callback: function(field_name, url, type, win) {
          _self.field_name = field_name;
          _self.uploadVideoType = true;
        },
        plugins: 'media image link hr textcolor paste colorpicker code',
        images_upload_url: _self.$productUploadImg + _self.tinymceData.img,
        images_upload_base_path: '',
        images_upload_credentials: true,
        toolbar: [
          'media images image styleselect formatselect fontsizeselect bold italic blockquote alignleft aligncenter alignright link ',
          'strikethrough hr forecolor paste removeformat undo redo code'
        ],
        style_formats:[
          {title: '1.5倍行距', inline: 'span', styles: {'line-height': "1.5"}},
          {title: '1.8倍行距', inline: 'span', styles: {'line-height': '1.8'}},
          {title: '2倍行距', inline: 'span', styles: {'line-height': '2'}},
          {title: '2.2倍行距', inline: 'span', styles: {'line-height': '2.2'}},
          {title: '2.5倍行距', inline: 'span', styles: {'line-height': '2.5'}},
        ],
        fontsize_formats: "12px 14px 16px 18px 20px 24px 28px 30px 32px 36px 40px",
        setup: function(ed) {
          ed.addButton('images', {
            title: '多图上传',
            image: '/static/upload/img/images.png',
            onclick: function() {
              layer.open({
                title:'多图上传',
                type:1,
                anim:1,
                move: false,
                content:'<div id="zwb_upload"><span class="add_box"><input type="file" class="add" multiple>选择图片</span></div>',
                area:['90%','300px;'],
                success: function(layero, index){
                  $("#zwb_upload").bindUpload({
                    url: _self.$productUploadImg + _self.tinymceData.img,//上传服务器地址
                    num:10,//上传数量的限制 默认为空 无限制
                    type:"jpg|png|gif|svg",//上传文件类型 默认为空 无限制
                    size:3,//上传文件大小的限制,默认为5单位默认为mb
                    editor:ed,
                    layer:layer
                  });
                }
              });
            }
          });
        }
      },
    }
  },
  methods: {
    getContent() {// 获取富文本内容
      return tinymce.activeEditor.getContent();
    },
    beforeAvatarUpload(file) {//上传之前
      const isVideo = file.type === 'video/mp4';
      if (!isVideo) {
        this.$message.error('请上传MP4格式的视频!');
      }
      return isVideo;
    },
    handleAvatarSuccess(res, file) {//上传成功
      console.log('上传成功!');
      this.videoUrl = file.response.data.STATIC_URL + file.response.data.path;
      window.document.getElementById(this.field_name).value = this.videoUrl;
      this.uploadVideoType = false;
    },
    handleAvatarError(err, file) {//上传失败
      console.log('上传失败!');
      this.$message.error('上传失败!');
    },
  }
}
</script>

<style lang="less" scoped>
  .upload-video {
    width: 360px;
    margin: 0 auto;
    .el-upload__tip {
      text-align: center;
    }
  }
</style>
