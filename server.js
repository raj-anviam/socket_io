
const app = require('express')();
const http = require('http').Server(app);
const cors = require('cors');
const Redis = require('ioredis');
const io = require('socket.io')(http, {
  cors: {
      origin: "*",
      methods: ["GET", "POST"],
      transports: ['websocket', 'polling'],
      credentials: true
  },
  allowEIO3: true
});

app.use(cors());

redis = new Redis;
redis.subscribe('private-channel', function(channel, message) {
  console.log('subscribed to private channel');
});

redis.on('message', function(channel, message) {
  console.log(channel);
  console.log(message);

  if(channel == 'private-channel') {
    let data = JSON.parse(message).data.data;
    let receiver_id = data.receiver.id;
    let event = JSON.parse(message).event;

    // console.log(channel + ':' + event);
    
    io.to(`${users[receiver_id]}`).emit(channel + ':' + event, data);
  }
});

var users = [];
var typing = [];

http.listen(8005, function() {
  console.log('listening on port 8005');
});

io.on('connection', function(socket) {
  socket.on("user_connected", function(user_id) {
    users[user_id] = socket.id;
    socket.emit('updateUserStatus', users);
  });
  
  socket.on("userTyping", function(user_id) {
    typing[user_id] = socket.id;
    socket.emit('updateUserTyping', typing);
  });

  socket.on("userNoLongerTyping", function(user_id) {
    let i = typing.indexOf(socket.id);
    typing.splice(i, 1, 0);
    socket.emit('updateUserTyping', typing);
  });

  socket.on('disconnect', function() {
    let i = users.indexOf(socket.id);
    users.splice(i, 1, 0);
    socket.emit('updateUserStatus', users);
  })
})