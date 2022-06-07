@extends('layouts.app')

@section('content')
    
<style>
  .chat {
    background-color: #fff;
    height: 500px;
    display: flex;
    justify-content: space-between;
    flex-direction: column;
  }
  .sidebar a {
    text-decoration: none;
    color: #000;
  }
  .sidebar {
    height: 100vh;
    overflow-y: scroll;
  }
  .received {
    background: #b9cad9;
    float: right;
    padding: 0 5%;
    border-radius: 10%;
    align-items: center;
  }
  .sent {
    color: #b9cad9;
    background: #555555;
    padding: 0 5%;
    border-radius: 10%;
    align-items: center;
    display: inline-block;
  }
</style>

  <div class="container">

    <div class="row">

      <div class="sidebar col-md-4" style="background-color: #eee">
        @foreach ($users as $user)
          <a href="{{ route('chat', $user->id) }}"><li>{{ $user->first_name }}(<span class="user-status-{{ $user->id }}">offline</span>)</li></a>
            <span class="user-status-{{ $user->id }}"></span>
          <hr>
        @endforeach
      </div>
      
      <div class="chat col-md-8" >
        <div class="header">

          <button class="seen-message">seen</button>
          
          <p>{{ $receiver->first_name }} <br> <span class="user-status-{{ $receiver->id }}">offline</span></p>

          <span class="user-typing-{{ Auth::user()->id }}"></span>
          
          <hr>
        </div>
        <div class="messages">
          
        </div>
        <div class="input col-md-12">
          <input type="text" class="form-control text-message">
          <button class="btn btn-dark mt-4 send-message">Send</button>
        </div>
        
      </div>
    </div>
  
  </div>

  @push('script')
      
  <script>
    
    
    $(function() {
      
      user_id = "{{ Auth::user()->id }}";
      receiver_id = "{{ $receiver->id }}";
      let ip_addrerss = '127.0.0.1';
      let socket_port = '8005';
      let socket = io(ip_addrerss + ':' + socket_port);
      
      getMessages();
      
      socket.on('connect', function() {
        socket.emit('user_connected', user_id);
      });

      socket.on('private-channel:App\\Events\\ReceiveMessage', function(message) {
        console.log('received', message);
        if(message.receiver_id == user_id) {
          let data = `<div class="row mt-4"><div class='sent'> <p>${message.message}</p></div></div>`;
          $('.messages').append(data);
          seenMessage(message.id);
        }

      });

      socket.on('private-channel:App\\Events\\SeenMessage', function(message) {
        console.log('seen', message);
      });

      socket.on('updateUserTyping', function(data) {
        
          if(data.is_typing && data.receiver_id == user_id)
            $(`.user-typing-${data.receiver_id}`).html('typing');
          else
            $(`.user-typing-${data.receiver_id}`).html('');
      });

      socket.on('updateUserStatus', function(data) {
        
        $.each(data, function(index, value) {
          if(value != null && value != 0)
            $(`.user-status-${index}`).html('online');
          else
            $(`.user-status-${index}`).html('offline');
        })
      })

      // typing

    var typing = false;
    var timeout = undefined;

    function timeoutFunction(){
      typing = false;
      socket.emit('userNoLongerTyping', {user_id, receiver_id});
    }

    function onKeyDownNotEnter(){
      
      if(typing == false) {
        typing = true
        socket.emit('userTyping', {user_id, receiver_id});
        timeout = setTimeout(timeoutFunction, 1000);
      } else {
        clearTimeout(timeout);
        timeout = setTimeout(timeoutFunction, 1000);
      }

    }
      
    $('.text-message').on('input', onKeyDownNotEnter);

    
    });

    $('.send-message').click(async function() {
      message = $('.text-message').val();
      receiver_id = "{{ $receiver->id }}";
      token = await login()

      $.ajax({
        url: "{{ route('send-message') }}",
        method: "POST",
        data: {message, receiver_id, token},
        success: function(data) {
          console.log('sent', data);
          data = `<div class="row d-flex justify-content-end mt-4"><div class='received'> <p>${data.data.message}</p></div></div>`;
          $('.messages').append(data);
          $('.text-message').val('');
        }
      })
    });

    async function seenMessage(id) {

      token = await login()

      $.ajax({
        url: "{{ route('seen-message') }}",
        method: "POST",
        data: {id, token},
        success: function(data) {
          console.log('seen api sent', data);
        }
      })
    };
    
    async function login() {
      let token = null;
      await $.ajax({
        url: "{{ url('api/login') }}",
        method: "POST",
        data: {
          "email": "{{ Auth::user()->email }}",
          "password": "123456789"
        },
        success: function(data) {
          token = data.token;
        }
      })
      return token;
    }

    async function getMessages() {
      token = await login()

      $.ajax({
        url: "{{ url('api/messageList') }}",
        method: "POST",
        data: {
          id: user_id,
          user_id: user_id,
          read_status: 1,
          token
        },
        success: function(data) {
          
          $.each(data.data, (index, value) => {

            console.log(value.receiver_id == user_id);
            
            if(value.receiver_id == user_id)
              $('.messages').append(`<div class="row d-flex justify-content-end mt-4"><div class='received'> <p>${value.message}</p></div></div>`);
            else
              $('.messages').append(`<div class="row mt-4"><div class='sent'> <p>${value.message}</p></div></div>`);

          })
          
        }
      })
      return token;
    }

    


    console.clear()
    
    </script>

@endpush

@endsection