@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">Menu</div>

                <div class="card-body">
                    <p><a href="/home">Dashboard</a></p>
                    <p><a href="/trade">Trade Buy & Sell</a></p>
                    <p><a href="/order-list">Order History</a></p>
                    <p><a href="/log">Log</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <h5>Your Account Information</h5>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <img style="width: 200px; height: auto;" src="{{ $user['avatar'] }}">
                        </div>
                        <div class="col-md-8">
                            <p>Name : {{ $user['name'] }}</p>
                            <p>Username : {{ $user['username'] }}</p>
                            <p>Email : {{ $user['email'] }}</p>
                            <p>Phone : {{ $user['phone'] }}</p>
                            <hr>
                            <p>Api Key : <b>{{ auth()->user()->email }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
