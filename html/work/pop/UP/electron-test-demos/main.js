const { app, BrowserWindow } = require('electron');
const path = require('path');

// 关闭安全警告
process.env.ELECTRON_DISABLE_SECURITY_WARNINGS = true;

let win;

function createWindow() {
  win = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: {
      nodeIntegration: true
    }
  })

  win.loadFile('index.html');

  win.webContents.openDevTools();

  // win.setProgressBar(0.5);

  win.setThumbarButtons([
    {
      tooltip: 'button1',
      icon: path.join(__dirname, '/src/images/banner01.jpg'),
      click () { console.log('button1 clicked') }
    }, {
      tooltip: 'button2',
      icon: path.join(__dirname, '/src/images/banner01.jpg'),
      flags: ['enabled', 'dismissonclick'],
      click () { console.log('button2 clicked.') }
    }
  ])

  win.setOverlayIcon(null, 'Description for overlay');

  // win.flashFrame(true);
  // win.once('focus', () => win.flashFrame(false));

  win.on('closed', () => {
    win = null;
  })
}

app.setUserTasks([
  {
    program: process.execPath,
    arguments: '--new-window',
    iconPath: process.execPath,
    iconIndex: 0,
    title: 'New Window',
    description: 'Create a new window'
  }
])

app.on('ready', createWindow);

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
})

app.on('activate', () => {
  if (win === null) {
    createWindow();
  }
})
