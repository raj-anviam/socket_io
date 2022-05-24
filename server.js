
const app = require('express')();
const http = require('http').Server(app);
const cors = require('cors');
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


var users = [];

http.listen(8005, function() {
  console.log('listening on port 8005');
});

io.on('connection', function(socket) {
  socket.on("user_connected", function(user_id) {
    console.log('user connected - ' + user_id);

    users[user_id] = socket.id;

    console.log(users);
    socket.emit('updateUserStatus', users);
  })

  // socket.on('disconnect', function() {
  //   let i = users.indexOf('socket.id');
  //   users.splice(i, 1, 0);
  //   socket.emit('updateUserStatus', users);
  // })
})