@extends('layouts.app') @section('content')

<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Messages Received</div>
                <div id="messages" class="MessagesWindow">
                    @foreach ($messages as $key => $message)
                        <p>{{$message->name}}: {{$message->message}}</p>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Send message</div>
                <form action="sendmessage" method="POST">
                    {{ csrf_field() }}
                    <input type="text" name="message" class="message">
                    <input type="submit" value="send" class="send">
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
