const { app, BrowserWindow } = require('electron')
const path = require('path')
const mac = require('getmac');//获取当前使用的mac地址
const os = require('os');//获取系统信息,包括所有mac地址，cpu,内存等

//创建窗口函数
function createWindow () {
  const win = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: {
      preload: path.join(__dirname, 'preload.js'),
	  nodeIntegration: true,
	  contextIsolation: false
    }
  })
  win.loadFile('index.html')
}

//加载窗口
app.whenReady().then(() => {
  createWindow()
  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
      createWindow()
    }
  })
})

//关闭窗口
app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit()
  }
})