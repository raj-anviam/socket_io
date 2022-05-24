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
</style>

  <div class="container">

    <div class="row">

      <div class="sidebar col-md-4" style="background-color: #eee">
        @foreach ($users as $user)
          <a href="{{ route('chat', $user->id) }}"><li>{{ $user->name }}(<span class="user-status-{{ $user->id }}">offline</span>)</li></a>
          <hr>
        @endforeach
      </div>
      
      <div class="chat col-md-8" >
        <div class="header">
          <p>{{ $receiver->name }} <br> <span class="user-status-{{ $user->id }}">offline</span></p>
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
      let ip_addrerss = '127.0.0.1';
      let socket_port = '8005';
      let socket = io(ip_addrerss + ':' + socket_port);
      
      socket.on('connect', function() {
        socket.emit('user_connected', user_id);
      });

      socket.on('updateUserStatus', function(data) {
        console.log(data);
        
        $.each(data, function(index, value) {
          if(value != null && value != 0)
            $(`.user-status-${index}`).html('online');
        })
      })
    });

    $('.send-message').click(function() {
      message = $('.text-message').val();
      receiver_id = "{{ $receiver->id }}";
      _token = "{{ csrf_token() }}";

      $.ajax({
        url: "{{ route('send-message') }}",
        method: "POST",
        data: {message, receiver_id, _token},
        success: function(data) {
          console.log(data);
        }
      })
    })
    
    </script>

@endpush

@endsection