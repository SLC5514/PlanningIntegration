<template>
  <div id="App">
    <el-container>
      <el-header>
        <span>简易聊天室: {{roomName}}</span>
        <el-button type="text" class="loag-out" @click="loagOut">登出</el-button>
      </el-header>
      <el-container>
        <el-aside width="200px">
          <el-card>
            <div slot="header">
              <span>室内人员</span>
            </div>
            <div v-for="(v, i) in users.list" :key="i">
              {{'室友' + (i + 1) + '：' + v.name }}{{users.id === v.id ? ' *':''}}
            </div>
          </el-card>
        </el-aside>
        <el-container>
          <el-main class="chat-main">
            <!-- <router-view/> -->
            <el-card>
              <div slot="header">
                <span>消息列表</span>
              </div>
              <el-card v-for="(v, i) in users.msg" :key="i" class="item" :style="v.id === users.id ? 'text-align:right;':''">
                <div class="user">
                  <div class="name" :style="v.id === users.id ? 'float:right;':''">{{v.name}}</div>
                  <div class="time">{{v.time}}</div>
                </div>
                <el-card :class="'msg ' + (v.id === users.id ? 'active-msg':'')">{{v.txt}}</el-card>
              </el-card>
            </el-card>
          </el-main>
          <el-footer>
            <el-input
              autofocus
              type="textarea"
              resize="none"
              :autosize="{ minRows: 4, maxRows: 4}"
              placeholder="请输入内容"
              @keyup.enter.native="chatMsg"
              chat-type="1"
              v-model="msg">
            </el-input>
            <el-button type="primary" plain @click="chatMsg">发送</el-button>
          </el-footer>
        </el-container>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'App',
  data () {
    return {
      userName: '',
      msg: '',
      roomName: ''
    }
  },
  sockets: {
    connect () {
      console.log('socket connected!')
      if (!this.users.id) {
        this.login()
      }
    },
    initChat ({ roomName, list, msg }) {
      if (this.users.id) {
        const idx = this.users.list.findIndex(item => item.id === this.users.id)
        this.userName = this.users.list[idx].name
        this.roomName = roomName
        this.saveUsers({
          id: this.users.id,
          list: list,
          msg: msg
        })
      }
    },
    sendName (roomName) {
      this.roomName = roomName
    },
    onLine ({ id, list, msg }) {
      this.saveUsers({
        id: this.users.id ? this.users.id : (
          this.userName ? id : 0
        ),
        list,
        msg
      })
    },
    loagOut ({ id, list }) {
      const type = this.users.id === id
      if (type) {
        this.$socket.close()
        console.log(666)
        this.$socket.open()
      }
      this.saveUsers({
        id: type ? 0 : this.users.id,
        list: type ? [] : list,
        msg: type ? [] : this.users.msg
      })
    },
    chatMsg (msgList) {
      this.saveUsers({
        id: this.users.id,
        list: this.users.list,
        msg: msgList
      })
      this.msg = ''
      let count = 0
      let time = setInterval(() => {
        count += 7
        const height = document.getElementsByClassName('chat-main')[0].children[0].clientHeight
        const t1 = document.getElementsByClassName('chat-main')[0].scrollTop
        document.getElementsByClassName('chat-main')[0].scrollTop += height / count
        const t2 = document.getElementsByClassName('chat-main')[0].scrollTop
        if (t1 === t2) {
          clearInterval(time)
        }
      }, 1)
    }
  },
  computed: {
    ...mapState({
      users: state => state.users
    })
  },
  mounted () {
    this.$socket.emit('initChat')
  },
  methods: {
    ...mapActions(['saveUsers']),
    login () {
      this.$prompt('请输入你的名字', '登陆', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /\w+|[\u2E80-\u9FFF]+/,
        inputErrorMessage: '名字格式不正确'
      }).then(({ value }) => {
        this.userName = value
        this.$socket.emit('onLine', value)
        this.$message({
          type: 'success',
          message: '你好 ' + this.userName + '，欢迎进入简易聊天室: ' + this.roomName
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '取消输入'
        })
      })
    },
    loagOut () {
      this.$socket.emit('loagOut', this.users.id)
    },
    chatMsg () {
      if (this.msg.replace(/(^\s*)|(\s*$)/g, '') !== '') {
        const date = new Date()
        this.$socket.emit('chatMsg', {
          id: this.users.id,
          name: this.userName,
          time: date.toLocaleDateString() + ' ' + date.getHours() + ':' + date.getSeconds(),
          txt: this.msg
        })
      } else {
        this.msg = ''
      }
    }
  }
}
</script>

<style lang="less">
* {
  margin: 0;
  padding: 0;
}
html,
body {
  width: 100%;
  height: 100%;
}
a {
  cursor: pointer;
  text-decoration: none;
}
img {
  border: none;
}
#App {
  // min-width: 1200px;
  height: 100%;
  line-height: 1.5;
  .el-container {
    height: 100%;
    .el-header {
      background-color: #B3C0D1;
      color: #333;
      line-height: 60px;
      .loag-out {
        float: right;
        line-height: 60px;
        margin: 0;
        padding: 0;
        border: 0;
      }
    }
    .el-footer {
      background-color: #B3C0D1;
      color: #333;
      height: 136px!important;
      padding: 0;
      .el-button {
        width: 100%;
      }
    }
    .el-aside {
      background-color: #D3DCE6;
      color: #333;
    }
    .el-main {
      background-color: #E9EEF3;
      color: #333;
      .el-card__body {
        padding: 10px;
      }
      .item {
        min-height: 60px;
        margin-bottom: 10px;
        .user {
          line-height: 20px;
          padding-bottom: 10px;
          .name {
            float: left;
            width: 60px;
          }
          .time {
            font-size: 12px;
            color: #999;
          }
        }
        .msg {
          .el-card__body {
            word-break: break-all;
            text-align: left;
            max-width: 70%;
            float: left;
            background: #dfe6ef;
            border-radius: 5px;
          }
        }
        .active-msg {
          .el-card__body {
            float: right;
          }
        }
      }
      .item:last-child {
        margin-bottom: 0;
      }
    }
  }
}
</style>
