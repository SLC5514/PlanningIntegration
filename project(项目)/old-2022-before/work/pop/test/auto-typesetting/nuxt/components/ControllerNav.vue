<template>
  <div class="controller-nav-com">
    <!-- 未添加子报告 -->
    <div v-if="colNavType === 1" class="content1">
      <p>还没有添加报告内容</p>
      <el-button @click="$bus.$emit('showAddSubReportDialog')">新增子报告</el-button>
    </div>
    <!-- 已添加子报告 -->
    <div v-else-if="colNavType === 2" class="content2">
      <el-card :class="{'on': reportIdx === i}" shadow="hover" v-for="(v, i) in reportData" :key="v.uuid" @click.native="setReportIdx(i)">
        <h5>{{i + 1}}、{{v.title}}</h5>
        <el-image src="https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg" fit="cover"></el-image>
        <el-dropdown trigger="click" size="mini">
          <el-button icon="el-icon-more" circle></el-button>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item @click.native="$bus.$emit('editSubReport', v, i)">编辑</el-dropdown-item>
            <el-dropdown-item @click.native="$bus.$emit('copySubReport', v)">复制</el-dropdown-item>
            <el-dropdown-item @click.native="$bus.$emit('removeSubReport', i)">删除</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </el-card>
      <el-button @click="$bus.$emit('showAddSubReportDialog')">新增子报告</el-button>
    </div>
    <!-- 文字 -->
    <div v-else-if="colNavType === 3" class="content3">
      <h4>文字</h4>
      <el-divider></el-divider>
      <ul class="list-box">
        <li>
          <label>颜色：</label>
          <el-color-picker v-model="textData.color" :predefine="textData.colors" show-alpha size="mini"></el-color-picker>
        </li>
        <li>
          <label>大小：</label>
          <el-input-number v-model="textData.fontSize" :min="12" :max="24" controls-position="right" size="mini"></el-input-number>
        </li>
        <li>
          <!-- <el-button-group>
            <el-tooltip effect="dark" content="加粗" placement="top">
              <el-button size="mini">
                <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M531.968 832c51.84 0 94.912-4.608 129.024-13.824 34.112-9.216 61.248-22.528 81.408-39.936 20.16-17.408 34.304-38.912 42.496-64.512s12.288-54.4 12.288-86.528c0-88.768-41.6-143.68-124.928-164.864V458.24c38.912-10.88 65.92-28.672 80.896-53.248 15.04-24.576 22.528-55.296 22.528-92.16 0-32.064-4.096-60.096-12.288-83.968a128.512 128.512 0 0 0-42.496-59.904c-20.16-16-47.296-27.968-81.408-35.84-34.112-7.872-77.12-11.776-129.024-11.776H270.848V832h261.12zM478.72 414.208h-52.224v-168.96h52.224c25.28 0 47.104 0.832 65.536 2.56 18.432 1.728 33.28 5.632 44.544 11.776 11.264 6.144 19.648 14.72 25.088 25.6 5.44 10.88 8.192 25.6 8.192 44.032 0 17.728-2.752 32.064-8.192 43.008a57.984 57.984 0 0 1-25.6 25.6 132.672 132.672 0 0 1-44.544 12.8 505.664 505.664 0 0 1-65.024 3.584z m19.456 292.864h-71.68V526.848h71.68c27.328 0 50.176 1.536 68.608 4.608 18.432 3.072 32.96 8.192 43.52 15.36a58.688 58.688 0 0 1 22.528 28.16c4.48 11.584 6.656 25.6 6.656 41.984 0 18.432-2.24 33.472-6.656 45.056a52.672 52.672 0 0 1-23.04 27.136 120.96 120.96 0 0 1-43.52 13.824 469.056 469.056 0 0 1-68.096 4.096z"></path></svg>
              </el-button>
              <el-checkbox-button label="上海" size="mini">
                <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M531.968 832c51.84 0 94.912-4.608 129.024-13.824 34.112-9.216 61.248-22.528 81.408-39.936 20.16-17.408 34.304-38.912 42.496-64.512s12.288-54.4 12.288-86.528c0-88.768-41.6-143.68-124.928-164.864V458.24c38.912-10.88 65.92-28.672 80.896-53.248 15.04-24.576 22.528-55.296 22.528-92.16 0-32.064-4.096-60.096-12.288-83.968a128.512 128.512 0 0 0-42.496-59.904c-20.16-16-47.296-27.968-81.408-35.84-34.112-7.872-77.12-11.776-129.024-11.776H270.848V832h261.12zM478.72 414.208h-52.224v-168.96h52.224c25.28 0 47.104 0.832 65.536 2.56 18.432 1.728 33.28 5.632 44.544 11.776 11.264 6.144 19.648 14.72 25.088 25.6 5.44 10.88 8.192 25.6 8.192 44.032 0 17.728-2.752 32.064-8.192 43.008a57.984 57.984 0 0 1-25.6 25.6 132.672 132.672 0 0 1-44.544 12.8 505.664 505.664 0 0 1-65.024 3.584z m19.456 292.864h-71.68V526.848h71.68c27.328 0 50.176 1.536 68.608 4.608 18.432 3.072 32.96 8.192 43.52 15.36a58.688 58.688 0 0 1 22.528 28.16c4.48 11.584 6.656 25.6 6.656 41.984 0 18.432-2.24 33.472-6.656 45.056a52.672 52.672 0 0 1-23.04 27.136 120.96 120.96 0 0 1-43.52 13.824 469.056 469.056 0 0 1-68.096 4.096z"></path></svg>
              </el-checkbox-button>
            </el-tooltip>
            <el-tooltip effect="dark" content="左对齐" placement="top">
              <el-button size="mini">
                <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M1024 1024v0z"></path><path d="M1024 60.224v0z"></path><path d="M1024 120.512v0z"></path><path d="M1024 180.736v0z"></path><path d="M1024 241.024v0z"></path><path d="M1024 301.248v0z"></path><path d="M1024 361.472v0z"></path><path d="M1024 421.76v0z"></path><path d="M1024 481.984v0z"></path><path d="M1024 542.016v0z"></path><path d="M1024 602.24v0z"></path><path d="M1024 662.528v0z"></path><path d="M1024 722.752v0z"></path><path d="M1024 782.976v0z"></path><path d="M1024 843.264v0z"></path><path d="M1024 903.488v0z"></path><path d="M1024 963.776v0z"></path><path d="M1024 0v0z"></path><path d="M1024 1024v0z"></path><path d="M60.224 1024v0z"></path><path d="M120.512 1024v0z"></path><path d="M180.736 1024v0z"></path><path d="M241.024 1024v0z"></path><path d="M301.248 1024v0z"></path><path d="M361.472 1024v0z"></path><path d="M421.76 1024v0z"></path><path d="M481.984 1024v0z"></path><path d="M542.016 1024v0z"></path><path d="M602.24 1024v0z"></path><path d="M662.528 1024v0z"></path><path d="M722.752 1024v0z"></path><path d="M782.976 1024v0z"></path><path d="M843.264 1024v0z"></path><path d="M903.488 1024v0z"></path><path d="M963.776 1024v0z"></path><path d="M0 1024v0z"></path><path d="M960 861.44h-896v-107.52h896v107.52z"></path><path d="M597.33333339 661.312h-537.601v-107.52h537.601v107.52z"></path><path d="M960 467.2h-896v-107.52h896v107.52z"></path><path d="M597.33333339 270.144h-537.601v-107.583h537.601v107.583z"></path></svg>
              </el-button>
            </el-tooltip>
            <el-tooltip effect="dark" content="中对齐" placement="top">
              <el-button size="mini">
                <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M716.8 153.6H359.014v102.093H716.8V153.6zM256.358 357.888v102.093H819.2V357.888H256.358zM204.8 870.4h614.4V768.256H204.8V870.4z m154.214-306.38v102.092H716.8V564.019H359.014z"></path></svg>
              </el-button>
            </el-tooltip>
            <el-tooltip effect="dark" content="右对齐" placement="top">
              <el-button size="mini">
                <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M856.257 170.216H398.951c-18.128 0-32.63 14.667-32.63 32.63v32.63c0 18.127 14.667 32.63 32.63 32.63h457.471c18.127 0 32.63-14.667 32.63-32.63v-32.63c0-17.963-14.667-32.63-32.795-32.63z m0 196.105H235.474c-18.127 0-32.63 14.667-32.63 32.63v32.63c0 18.127 14.667 32.63 32.63 32.63h620.783c18.128 0 32.63-14.667 32.63-32.63v-32.63c0.164-18.127-14.502-32.63-32.63-32.63z m0 195.942H431.58c-17.963 0-32.63 14.667-32.63 32.63v32.63c0 17.963 14.667 32.63 32.63 32.63h424.841c17.963 0 32.63-14.667 32.63-32.63v-32.63c0-17.963-14.667-32.63-32.795-32.63z m0 196.105H170.215c-17.963 0-32.63 14.667-32.63 32.63v32.63c0 17.963 14.667 32.63 32.63 32.63h686.206c17.963 0 32.63-14.667 32.63-32.63v-32.63c0-17.963-14.667-32.63-32.795-32.63z"></path></svg>
              </el-button>
            </el-tooltip>
          </el-button-group> -->
          <el-radio-group v-model="textData.textAlign" size="mini">
            <!-- <el-tooltip effect="dark" content="加粗" placement="top">
              <el-radio-button label="bold">
                <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M531.968 832c51.84 0 94.912-4.608 129.024-13.824 34.112-9.216 61.248-22.528 81.408-39.936 20.16-17.408 34.304-38.912 42.496-64.512s12.288-54.4 12.288-86.528c0-88.768-41.6-143.68-124.928-164.864V458.24c38.912-10.88 65.92-28.672 80.896-53.248 15.04-24.576 22.528-55.296 22.528-92.16 0-32.064-4.096-60.096-12.288-83.968a128.512 128.512 0 0 0-42.496-59.904c-20.16-16-47.296-27.968-81.408-35.84-34.112-7.872-77.12-11.776-129.024-11.776H270.848V832h261.12zM478.72 414.208h-52.224v-168.96h52.224c25.28 0 47.104 0.832 65.536 2.56 18.432 1.728 33.28 5.632 44.544 11.776 11.264 6.144 19.648 14.72 25.088 25.6 5.44 10.88 8.192 25.6 8.192 44.032 0 17.728-2.752 32.064-8.192 43.008a57.984 57.984 0 0 1-25.6 25.6 132.672 132.672 0 0 1-44.544 12.8 505.664 505.664 0 0 1-65.024 3.584z m19.456 292.864h-71.68V526.848h71.68c27.328 0 50.176 1.536 68.608 4.608 18.432 3.072 32.96 8.192 43.52 15.36a58.688 58.688 0 0 1 22.528 28.16c4.48 11.584 6.656 25.6 6.656 41.984 0 18.432-2.24 33.472-6.656 45.056a52.672 52.672 0 0 1-23.04 27.136 120.96 120.96 0 0 1-43.52 13.824 469.056 469.056 0 0 1-68.096 4.096z"></path></svg>
              </el-radio-button>
            </el-tooltip> -->
            <el-tooltip effect="dark" content="加粗" placement="top">
              <el-checkbox-group v-model="textData.fontWeight" size="mini">
                <el-checkbox-button label="bold">
                  <i class="iconfont iconzitijiacu"></i>
                </el-checkbox-button>
              </el-checkbox-group>
            </el-tooltip>
            <el-tooltip effect="dark" content="左对齐" placement="top">
              <el-radio-button label="left">
                <i class="iconfont iconzuoduiqi"></i>
              </el-radio-button>
            </el-tooltip>
            <el-tooltip effect="dark" content="中对齐" placement="top">
              <el-radio-button label="center">
                <i class="iconfont iconjuzhongduiqi"></i>
              </el-radio-button>
            </el-tooltip>
            <el-tooltip effect="dark" content="右对齐" placement="top">
              <el-radio-button label="right">
                <i class="iconfont iconyouduiqi"></i>
              </el-radio-button>
            </el-tooltip>
          </el-radio-group>
        </li>
        <li>
          <ul class="group-list-h">
            <li>
              <div>行间距</div>
              <el-input-number v-model="textData.lineHeight" :min="0.5" :max="2" :precision="1" :step="0.1" size="mini"></el-input-number>
            </li>
            <li>
              <div>字间距</div>
              <el-input-number v-model="textData.letterSpacing" :min="0" :max="2" size="mini"></el-input-number>
            </li>
          </ul>
        </li>
        <li>
          <label>透明度：</label>
          <el-slider v-model="imageData.opacity" :min="0" :max="1" :precision="1" :step="0.1" input-size="mini"></el-slider>
          <div class="opacity-preview">{{imageData.opacity}}</div>
        </li>
      </ul>
      <CommonNav :common-data="textData" />
    </div>
    <!-- 图片 -->
    <div v-else-if="colNavType === 4" class="content4">
      <h4>图片</h4>
      <el-divider></el-divider>
      <ul class="list-box">
        <li>
          <el-button>替换图片</el-button>
        </li>
        <li>
          <el-button>智能抠图</el-button>
          <el-button @click="$bus.$emit('clipImage')">裁剪图片</el-button>
        </li>
        <li>
          <label>透明度：</label>
          <el-slider v-model="imageData.opacity" :min="0" :max="1" :precision="1" :step="0.1" input-size="mini"></el-slider>
          <div class="opacity-preview">{{imageData.opacity}}</div>
        </li>
      </ul>
      <CommonNav :common-data="imageData" />
    </div>
    <!-- 背景 -->
    <div v-else-if="colNavType === 5" class="content5">
      <h4>背景</h4>
      <el-divider></el-divider>
      <ul class="list-box">
        <li v-if="bgImageData.url">
          <el-image src="https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg" fit="cover"></el-image>
        </li>
        <li v-else>
          <label>链接：</label>
          <el-input v-model="bgImageData.link" placeholder="请输入背景链接"></el-input>
        </li>
        <li v-if="bgImageData.url">
          <label>透明度：</label>
          <el-slider v-model="bgImageData.opacity" :min="0" :max="10" input-size="mini"></el-slider>
          <div class="opacity-preview">{{bgImageData.opacity}}</div>
        </li>
        <li v-if="bgImageData.url">
          <el-button>恢复原始背景</el-button>
        </li>
        <li>
          <el-button>上传背景图</el-button>
        </li>
        <li v-if="bgImageData.url">
          <el-button>裁剪</el-button>
        </li>
      </ul>
    </div>
    <!-- 组合 -->
    <div v-else-if="colNavType === 6" class="content6">
      <h4>组合</h4>
      <el-divider></el-divider>
      <ul class="list-box">
        <li>
          <ul class="group-list-v">
            <li>
              <el-button-group>
                <el-tooltip effect="dark" content="加粗" placement="top">
                  <el-button>
                    <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M531.968 832c51.84 0 94.912-4.608 129.024-13.824 34.112-9.216 61.248-22.528 81.408-39.936 20.16-17.408 34.304-38.912 42.496-64.512s12.288-54.4 12.288-86.528c0-88.768-41.6-143.68-124.928-164.864V458.24c38.912-10.88 65.92-28.672 80.896-53.248 15.04-24.576 22.528-55.296 22.528-92.16 0-32.064-4.096-60.096-12.288-83.968a128.512 128.512 0 0 0-42.496-59.904c-20.16-16-47.296-27.968-81.408-35.84-34.112-7.872-77.12-11.776-129.024-11.776H270.848V832h261.12zM478.72 414.208h-52.224v-168.96h52.224c25.28 0 47.104 0.832 65.536 2.56 18.432 1.728 33.28 5.632 44.544 11.776 11.264 6.144 19.648 14.72 25.088 25.6 5.44 10.88 8.192 25.6 8.192 44.032 0 17.728-2.752 32.064-8.192 43.008a57.984 57.984 0 0 1-25.6 25.6 132.672 132.672 0 0 1-44.544 12.8 505.664 505.664 0 0 1-65.024 3.584z m19.456 292.864h-71.68V526.848h71.68c27.328 0 50.176 1.536 68.608 4.608 18.432 3.072 32.96 8.192 43.52 15.36a58.688 58.688 0 0 1 22.528 28.16c4.48 11.584 6.656 25.6 6.656 41.984 0 18.432-2.24 33.472-6.656 45.056a52.672 52.672 0 0 1-23.04 27.136 120.96 120.96 0 0 1-43.52 13.824 469.056 469.056 0 0 1-68.096 4.096z"></path></svg>
                  </el-button>
                </el-tooltip>
                <el-tooltip effect="dark" content="左对齐" placement="top">
                  <el-button>
                    <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M1024 1024v0z"></path><path d="M1024 60.224v0z"></path><path d="M1024 120.512v0z"></path><path d="M1024 180.736v0z"></path><path d="M1024 241.024v0z"></path><path d="M1024 301.248v0z"></path><path d="M1024 361.472v0z"></path><path d="M1024 421.76v0z"></path><path d="M1024 481.984v0z"></path><path d="M1024 542.016v0z"></path><path d="M1024 602.24v0z"></path><path d="M1024 662.528v0z"></path><path d="M1024 722.752v0z"></path><path d="M1024 782.976v0z"></path><path d="M1024 843.264v0z"></path><path d="M1024 903.488v0z"></path><path d="M1024 963.776v0z"></path><path d="M1024 0v0z"></path><path d="M1024 1024v0z"></path><path d="M60.224 1024v0z"></path><path d="M120.512 1024v0z"></path><path d="M180.736 1024v0z"></path><path d="M241.024 1024v0z"></path><path d="M301.248 1024v0z"></path><path d="M361.472 1024v0z"></path><path d="M421.76 1024v0z"></path><path d="M481.984 1024v0z"></path><path d="M542.016 1024v0z"></path><path d="M602.24 1024v0z"></path><path d="M662.528 1024v0z"></path><path d="M722.752 1024v0z"></path><path d="M782.976 1024v0z"></path><path d="M843.264 1024v0z"></path><path d="M903.488 1024v0z"></path><path d="M963.776 1024v0z"></path><path d="M0 1024v0z"></path><path d="M960 861.44h-896v-107.52h896v107.52z"></path><path d="M597.33333339 661.312h-537.601v-107.52h537.601v107.52z"></path><path d="M960 467.2h-896v-107.52h896v107.52z"></path><path d="M597.33333339 270.144h-537.601v-107.583h537.601v107.583z"></path></svg>
                  </el-button>
                </el-tooltip>
                <el-tooltip effect="dark" content="中对齐" placement="top">
                  <el-button>
                    <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M716.8 153.6H359.014v102.093H716.8V153.6zM256.358 357.888v102.093H819.2V357.888H256.358zM204.8 870.4h614.4V768.256H204.8V870.4z m154.214-306.38v102.092H716.8V564.019H359.014z"></path></svg>
                  </el-button>
                </el-tooltip>
                <el-tooltip effect="dark" content="右对齐" placement="top">
                  <el-button>
                    <svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M856.257 170.216H398.951c-18.128 0-32.63 14.667-32.63 32.63v32.63c0 18.127 14.667 32.63 32.63 32.63h457.471c18.127 0 32.63-14.667 32.63-32.63v-32.63c0-17.963-14.667-32.63-32.795-32.63z m0 196.105H235.474c-18.127 0-32.63 14.667-32.63 32.63v32.63c0 18.127 14.667 32.63 32.63 32.63h620.783c18.128 0 32.63-14.667 32.63-32.63v-32.63c0.164-18.127-14.502-32.63-32.63-32.63z m0 195.942H431.58c-17.963 0-32.63 14.667-32.63 32.63v32.63c0 17.963 14.667 32.63 32.63 32.63h424.841c17.963 0 32.63-14.667 32.63-32.63v-32.63c0-17.963-14.667-32.63-32.795-32.63z m0 196.105H170.215c-17.963 0-32.63 14.667-32.63 32.63v32.63c0 17.963 14.667 32.63 32.63 32.63h686.206c17.963 0 32.63-14.667 32.63-32.63v-32.63c0-17.963-14.667-32.63-32.795-32.63z"></path></svg>
                  </el-button>
                </el-tooltip>
              </el-button-group>
            </li>
            <li>
              <div class="group-list-v-item">字间距</div>
              <el-divider direction="vertical"></el-divider>
              <div class="group-list-v-item">字间距</div>
            </li>
          </ul>
        </li>
        <li>
          <el-button>组合</el-button>
        </li>
        <li>
          <el-button>拆分组合</el-button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import { eleDefCommonStyle, titleCoverStyle, imgCoverStyle, bgImgCoverStyle } from '@/common/elementModel';

export default {
  name: "ControllerNavCom",
  components: {
    CommonNav: () => import("@/components/CommonNav")
  },
  data() {
    return {
      colNavType: 1, // 1未添加子报告 2已添加子报告 3文字 4图片 5背景 6组合
      reportData: this.$parent.reportData,
      // 文字数据
      textData: {
        ...JSON.parse(JSON.stringify(eleDefCommonStyle)),
        ...JSON.parse(JSON.stringify(titleCoverStyle)),
        colors: [
          '#ff4500',
          '#ff8c00',
          '#ffd700',
          '#90ee90',
          '#00ced1',
          '#1e90ff',
          '#c71585',
          'rgba(255, 69, 0, 0.68)',
          'rgb(255, 120, 0)',
          'hsv(51, 100, 98)',
          'hsva(120, 40, 94, 0.5)',
          'hsl(181, 100%, 37%)',
          'hsla(209, 100%, 56%, 0.73)',
          '#c7158577', // 无效
        ],
      },
      // 图片数据
      imageData: {
        ...JSON.parse(JSON.stringify(eleDefCommonStyle)),
        ...JSON.parse(JSON.stringify(imgCoverStyle)),
      },
      // 背景数据
      bgImageData: {
        ...JSON.parse(JSON.stringify(eleDefCommonStyle)),
        ...JSON.parse(JSON.stringify(bgImgCoverStyle)),
      },
    };
  },
  computed: {
    ...mapGetters(['reportIdx']),
  },
  watch: {
    reportData() {
      this.setColNavType();
    }
  },
  mounted() {
    this.setColNavType();
    this.$bus.$on('setColNavType', this.setColNavType);
  },
  methods: {
    ...mapMutations(['setReportIdx']),
    // 设置控制器状态
    setColNavType(type) {
      if (!this.reportData.length) {
        this.colNavType = 1;
      } else {
        this.colNavType = 2;
        if (type === 'text') {
          this.colNavType = 3;
        } else if (type === 'grid' || type === 'image') {
          this.colNavType = 4;
        } else if (type === 'page') {
          this.colNavType = 5;
        }
      }
    },
  }
};
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.controller-nav-com {
  position: absolute;
  top: $header-height;
  bottom: 0;
  right: 0;
  width: $aside-width;
  box-shadow: $box-shadow;
  background: #fff;
  z-index: 7;
  & > div {
    height: 100%;
    overflow: auto;
  }
  .content1 {
    text-align: center;
    padding-top: 100px;
    p {
      margin-bottom: 30px;
    }
  }
  .content2 {
    padding: 20px;
    text-align: center;
    .el-card {
      position: relative;
      text-align: left;
      cursor: pointer;
      &.on {
        background: #eee;
      }
      & + .el-card {
        margin-top: 10px;
      }
      h5 {
        margin-bottom: 10px;
      }
      .el-dropdown {
        position: absolute;
        top: 12px;
        right: 20px;
        .el-button.is-circle {
          padding: 5px;
        }
      }
    }
    & > .el-button {
      margin-top: 30px;
    }
  }
  .content3,
  .content4,
  .content5,
  .content6 {
    padding: 20px;
    .el-divider {
      margin: 20px 0;
    }
    .list-box {
      margin-bottom: 20px;
    }
  }
}
</style>

<style lang="scss">
.controller-nav-com {
  .list-box {
    list-style: none;
    padding: 0px;
    & > li {
      display: flex;
      align-items: center;
      & + li {
        margin-top: 10px;
      }
      & > label {
        // width: 50px;
        margin-right: 10px;
        font-size: 14px;
        text-align: right;
        vertical-align: middle;
      }
      & > .el-input {
        flex: 1;
      }
      & > .el-button {
        flex: 1;
      }
      .el-color-picker {
        flex: 1;
        height: 30px;
        vertical-align: middle;
      }
      .el-input-number {
        width: auto;
        flex: 1;
        & + .el-input-number {
          margin-left: 10px;
        }
      }
      .el-radio-group {
        flex: 1;
        display: flex;
        & > label {
          flex: 1;
        }
      }
      .el-button-group {
        flex: 1;
        display: flex;
        & > button {
          flex: 1;
        }
      }
      .group-list-h {
        list-style: none;
        padding: 0px;
        font-size: 14px;
        flex: 1;
        display: flex;
        text-align: center;
        & > li {
          flex: 1;
          line-height: 27px;
          border: 1px solid #DCDFE6;
          &:not(:last-child) {
            margin-right: -1px;
          }
        }
      }
      .group-list-v {
        list-style: none;
        padding: 0px;
        font-size: 14px;
        flex: 1;
        display: flex;
        flex-direction: column;
        text-align: center;
        & > li {
          height: 40px;
          display: flex;
          align-items: center;
          .group-list-v-item {
            flex: 1;
          }
          &:last-child {
            border: 1px solid #DCDFE6;
            margin-top: -2px;
          }
        }
      }
      .el-slider {
        flex: 1;
      }
      .border-width-preview {
        width: 90px;
        height: 30px;
        margin-left: 10px;
        border: 1px solid #DCDFE6;
        border-radius: 4px;
        padding: 5px;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        align-items: center;
        div {
          width: 100%;
          border: 1px solid #000;
        }
      }
      .opacity-preview {
        width: 40px;
        height: 30px;
        margin-left: 10px;
        border: 1px solid #DCDFE6;
        border-radius: 4px;
        padding: 5px;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        align-items: center;
      }
    }
  }
  /* ui com in */
  .list-box > li {
    .el-color-picker .el-color-picker__trigger {
      width: 100%;
      height: 100%;
      .el-color-picker__icon {
        display: none;
      }
    }
    // .el-input-number.is-controls-right[class*=mini] [class*=decrease],
    // .el-input-number.is-controls-right[class*=mini] [class*=increase] {
    //   line-height: 12px;
    // }
    // .el-input-number.is-controls-right .el-input-number__decrease {
    //   bottom: 3px;
    // }
    .el-radio-group > label .el-radio-button__inner {
      width: 100%;
    }
    .group-list-h > li .el-input-number {
      .el-input-number__decrease {
        left: 0px;
        border-radius: none;
        border-right: none;
        background: transparent;
      }
      .el-input-number__increase {
        right: 0px;
        border-radius: none;
        border-left: none;
        background: transparent;
      }
      .el-input__inner {
        border: none;
      }
    }
    .el-collapse {
      .list-box > li label {
        font-size: 12px;
      }
      .el-collapse-item {
        .el-collapse-item__header {
          font-size: 14px;
          height: 40px;
          line-height: 40px;
        }
        .el-collapse-item__content {
          padding-bottom: 10px;
        }
        &:last-child .el-collapse-item__wrap {
          border-bottom: none;
        }
      }
    }
    .el-slider {
      .el-slider__runway.show-input {
        margin-right: 105px;
      }
      .el-slider__input {
        width: 95px;
      }
    }
  }
}
</style>
