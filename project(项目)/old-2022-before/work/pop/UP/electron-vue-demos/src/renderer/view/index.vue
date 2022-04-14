<template>
  <div class="index-page">
    <el-tabs tab-position="left" @tab-click="tabClick">
      <el-tab-pane label="进程通信">进程通信</el-tab-pane>
      <el-tab-pane label="窗口">
        <p class="title">打开一个普通窗口</p>
        <el-button @click="openNewWindow(1)">click</el-button>
        <p class="title">打开一个无框窗口</p>
        <el-button @click="openNewWindow(2)">click</el-button>
        <p class="title">打开一个带有状态栏（macOS）的无框窗口</p>
        <el-button @click="openNewWindow(3)">click</el-button>
        <p class="title">打开一个带有状态栏（macOS）可拖动的无框窗口</p>
        <el-button @click="openNewWindow(4)">click</el-button>
        <p class="title">打开一个透明窗口</p>
        <el-button @click="openNewWindow(5)">click</el-button>
        <p class="title">打开一个穿透窗口</p>
        <el-button @click="openNewWindow(6)">click</el-button>
      </el-tab-pane>
      <el-tab-pane label="弹框">
        <p class="title">打开一个错误弹框</p>
        <el-button @click="openDialog(1)">click</el-button>
        <p class="title">打开一个对话弹框</p>
        <el-button @click="openDialog(2)">click</el-button>
        <p class="title">打开一个信息提示框（macOS）</p>
        <el-button @click="openDialog(3)">click</el-button>
        <p class="title">打开一个文件选择框</p>
        <el-button @click="openDialog(4)">click</el-button>
      </el-tab-pane>
      <el-tab-pane label="系统">
        <p class="title">点击获取当前应用程序路径</p>
        <el-button @click="getSystem(1)">click</el-button>
        <p class="title">点击获取系统主目录</p>
        <el-button @click="getSystem(2)">click</el-button>
        <p class="title">点击获取版本信息</p>
        <el-button @click="getSystem(3)">click</el-button>
        <el-input v-model="input" placeholder="请输入内容" style="margin-top:20px;"></el-input>
        <p class="title">点击复制并粘贴上面的内容：{{readText}}</p>
        <el-button @click="getSystem(4)">click</el-button>
        <p class="title">点击获取屏幕截图：{{imgMsg}}</p>
        <el-button @click="getSystem(5)">click</el-button>
      </el-tab-pane>
      <el-tab-pane label="菜单">
        <div id="menuDemoContainer">
          <p class="title">点击改变应用程序菜单</p>
          <el-button @click="changeMenu(1)">click</el-button>
          <p class="title">点击改变上下文菜单（在灰线框中右键）</p>
          <el-button @click="changeMenu(2)">click</el-button>
          <p class="title">试试【command or Ctrl + M】进行最小化，由菜单创建的局部快捷键</p>
          <p class="title">点击后，试试【command or Ctrl + N】，手动创建的全局快捷键</p>
          <el-button @click="changeMenu(3)">click</el-button>
        </div>
      </el-tab-pane>
      <el-tab-pane label="打印">
        <p class="title">选择打印机</p>
        <el-select v-model="print" placeholder="请选择打印机">
          <el-option
            v-for="(v, i) in printers"
            :key="i"
            :label="v.name"
            :value="v.name">
          </el-option>
        </el-select>
        <p class="title">点击打印</p>
        <el-button @click="printFn(1)">click</el-button>
        <p class="title">点击打印到PDF: {{webviewPdfPath}}</p>
        <el-button @click="printFn(2)">click</el-button>
      </el-tab-pane>
      <el-tab-pane label="保护措施">保护措施</el-tab-pane>
      <el-tab-pane label="Shell">Shell</el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import { remote, clipboard, desktopCapturer, shell } from 'electron'
import os from 'os'
import path from 'path'
import fs from 'fs'

export default {
  name: 'index',
  data () {
    return {
      input: '复制我呀',
      readText: '',
      imgMsg: '',
      print: null,
      printers: [],
      webviewPdfPath: ''
    }
  },
  methods: {
    tabClick (tab) {
      switch (tab.label) {
        case '打印':
          this.printers = this.getPrinters()
          this.print = this.getDefaultPrinter().name
          break
        default:
          break
      }
    },
    /**
     * @description: 打开一个新窗口
     * @param {Number} 窗口类型
     * @return: null
     */
    openNewWindow (type) {
      // ipcRenderer.send('open-new-window', type)
      let win
      let option
      let url = `http://localhost:9080/` + require('path').join(__dirname, '../static/window.html')
      let callback = function () {}
      switch (type) {
        case 1:
          option = {
            width: 800,
            height: 600
          }
          break
        case 2:
          option = {
            width: 800,
            height: 600,
            frame: false
          }
          break
        case 3:
          option = {
            width: 800,
            height: 600,
            frame: false,
            titleBarStyle: 'hidden'
          }
          break
        case 4:
          option = {
            width: 800,
            height: 600,
            frame: false,
            titleBarStyle: 'hidden'
          }
          url = `http://localhost:9080/` + require('path').join(__dirname, '../static/window_drag.html')
          break
        case 5:
          option = {
            width: 800,
            height: 600,
            frame: false,
            titleBarStyle: 'hidden',
            transparent: true
          }
          url = `http://localhost:9080/` + require('path').join(__dirname, '../static/window_trans.html')
          break
        case 6:
          option = {
            width: 800,
            height: 600
          }
          url = `http://localhost:9080/` + require('path').join(__dirname, '../static/window_trans.html')
          callback = function (win) {
            win.setIgnoreMouseEvents(true)
          }
          break
        default:
          break
      }
      win = new remote.BrowserWindow(option)
      win.loadURL(url)
      callback(win)
      win.on('close', function () {
        win = null
      })
    },
    /**
     * @description: 打开弹框
     * @param {Number} 弹框类型
     * @return: null
     */
    openDialog (type) {
      switch (type) {
        case 1:
          remote.dialog.showErrorBox('错误', '这是一个错误弹框！')
          break
        case 2:
          remote.dialog.showMessageBox({
            type: 'info',
            title: '提示信息',
            message: '这是一个对话弹框！',
            buttons: ['确定', '取消']
          }, (index) => {
            console.log(`【你点击了${index ? '取消' : '确定'}！！】`)
          })
          break
        case 3:
          let options = {
            title: '信息框标题',
            body: '我是一条信息～～～'
          }
          let myNotification = new window.Notification(options.title, options)
          myNotification.onclick = () => {
            console.log('【你点击了信息框！！】')
          }
          break
        case 4:
          remote.dialog.showOpenDialog({
            properties: ['openDirectory', 'openFile']
          }, (data) => {
            console.log(data ? `【选择路径：${data[0]}】` : '取消')
          })
          break
        default:
          break
      }
    },
    /**
     * @description: 系统操作
     * @param {Number} 操作类型
     * @return: null
     */
    getSystem (type) {
      switch (type) {
        case 1:
          console.log('\n' + remote.app.getAppPath())
          break
        case 2:
          console.log('\n' + os.homedir())
          break
        case 3:
          console.log('\n' + 'electron:' + remote.process.versions.electron)
          console.log('chrome:' + remote.process.versions.chrome)
          console.log('node:' + remote.process.versions.node)
          console.log('v8:' + remote.process.versions.v8)
          break
        case 4:
          clipboard.writeText(this.input)
          this.readText = clipboard.readText()
          break
        case 5:
          this.imgMsg = '正在截取屏幕...'
          const thumbSize = this.determineScreenShotSize()
          let options = { types: ['screen'], thumbnailSize: thumbSize }
          desktopCapturer.getSources(options, (error, sources) => {
            if (error) return console.log(error)
            sources.forEach((source) => {
              if (source.name === 'Entire screen' || source.name === 'Screen 1') {
                const screenshotPath = path.join(os.homedir(), '/Desktop/screenshot.png')
                fs.writeFile(screenshotPath, source.thumbnail.toPNG(), (error) => {
                  if (error) return console.log(error)
                  shell.openExternal(`file://${screenshotPath}`)
                  this.imgMsg = `截图保存到: ${screenshotPath}`
                })
              }
            })
          })
          break
        default:
          break
      }
    },
    /**
     * @description: 获取设备尺寸
     * @param {null}
     * @return: Object {width: Number, height: Number}
     */
    determineScreenShotSize () {
      // const screenSize = screen.getPrimaryDisplay().workAreaSize
      const maxDimension = Math.max(screen.width, screen.height)
      return {
        width: maxDimension * window.devicePixelRatio,
        height: maxDimension * window.devicePixelRatio
      }
    },
    /**
     * @description: 改变菜单选项
     * @param {Number} 改变的菜单类型
     * @return: null
     */
    changeMenu (type) {
      let template = [
        {
          label: '文件',
          submenu: [
            {
              label: '新建文件',
              click: function () {
                remote.dialog.showMessageBox({
                  type: 'info',
                  message: '嘿!',
                  detail: '你点击了新建文件！'
                })
              }
            }
          ]
        },
        {
          label: '编辑',
          submenu: [{
            label: '剪切',
            role: 'cut'
          }, {
            label: '复制',
            role: 'copy'
          }, {
            label: '粘贴',
            role: 'paste'
          }]
        },
        {
          label: '最小化',
          accelerator: 'CmdOrCtrl+M',
          role: 'minimize'
        }
      ]
      let menu = remote.Menu.buildFromTemplate(template)
      switch (type) {
        case 1:
          remote.Menu.setApplicationMenu(menu)
          break
        case 2:
          document.getElementById('menuDemoContainer').addEventListener('contextmenu', (e) => {
            e.preventDefault()
            menu.popup({ window: remote.getCurrentWindow() })
          })
          break
        case 3:
          remote.globalShortcut.register('CommandOrControl+N', () => {
            remote.dialog.showMessageBox({
              type: 'info',
              message: '嘿!',
              detail: '你触发了手动注册的全局快捷键.'
            })
          })
          break
        default:
          break
      }
    },
    /**
     * @description: 打印
     * @param {Number} 打印类型
     * @return: null
     */
    printFn (type) {
      switch (type) {
        case 1:
          remote.getCurrentWebContents().print({
            silent: false, // 是否静默打印
            printBackground: true, // 是否打印背景
            deviceName: this.print // 打印机设备名称
          }, () => {
            console.log('开始打印')
          })
          break
        case 2:
          const pdfPath = path.join(os.homedir(), '/Desktop/webviewPrint.pdf')
          remote.getCurrentWebContents().printToPDF({}, (err, data) => {
            if (err) throw err
            fs.writeFile(pdfPath, data, (error) => {
              if (error) throw error
              shell.openExternal(`file://${pdfPath}`)
              this.webviewPdfPath = pdfPath
            })
          })
          break
        default:
          break
      }
    },
    /**
    * 获取系统打印机列表
    */
    getPrinters () {
      let printers = []
      try {
        const contents = remote.getCurrentWindow().webContents
        printers = contents.getPrinters()
      } catch (e) {
        console.error('getPrintersError', e)
      }
      return printers
    },
    /**
    * 获取系统默认打印机
    */
    getDefaultPrinter () {
      return this.getPrinters().find(element => element.isDefault)
    },
    /**
    * 检测是否安装了某个打印驱动
    */
    checkDriver (driverMame) {
      return this.getPrinters().find(element => (element.options['printer-make-and-model'] || '').includes(driverMame))
    },
    /**
    * 根据打印机名称获取打印机对象
    */
    getPrinterByName (name) {
      return this.getPrinters().find(element => element.name === name)
    }
  }
}
</script>

<style lang="less" scoped>
.title {
  font-size: 14px;
}
#menuDemoContainer {
  border: 1px solid #e3e3e3;
}
</style>
