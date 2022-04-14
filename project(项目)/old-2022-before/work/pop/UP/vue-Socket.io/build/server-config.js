let socketIo = require('socket.io');
let express = require('express'); 
let cxt = require('../src/services-server');

let httpPort = 9001;
let userList = [];
let msgList = [];
let app = express();

app.get('/',function(req,res){
  res.send('启动成功：' + httpPort);
});
 
let server = require('http').createServer(app);
let io = socketIo(server);
let count = 0;

io.on('connection', (socket) => {
  console.log('有客户端连接');
  const roomName = 'room' + count;
  socket.join(roomName, () => {
    console.log(Object.keys(socket.rooms)[1]);
    io.to(roomName).emit('sendName', roomName);
  });
  socket.on('initChat', () => {
    io.to(roomName).emit('initChat', {
      roomName,
      list: userList,
      msg: msgList
    });
  });
  socket.on('chatMsg', (msg) => {
    msgList.push(msg);
    if (msg.txt.indexOf('@小智') != -1) {
      if (msg.txt.indexOf('你好') != -1) {
        msgList.push(Object.assign({...msg}, {
          id: '',
          name: '小智',
          txt: '你好啊，' + msg.name
        }));
      }
    }
    io.to(roomName).emit('chatMsg', msgList);
  });
  socket.on('onLine', name => {
    const id = socket.id;
    userList.push({id, name});
    io.to(roomName).emit('onLine', {id, list: userList, msg: msgList});
  });
  socket.on('loagOut', id => {
    userList.splice(userList.findIndex(item => item.id === id), 1);
    io.to(roomName).emit('loagOut', {
      id,
      list: userList
    });
  });
  socket.on('disconnect', () => {
    console.log('有客户端断开连接');
  });
});

server.listen(httpPort); //用server连接
console.log('io listen success !! ' + httpPort);
