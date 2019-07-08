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
                    <div class="card-header">Log</div>

                    <div class="card-body">
                        <h5>Log History</h5>
                        <br>
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 20%">Date</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 70%">Message</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($log as $ini)
                            <tr>
                                <th>{{ $ini->created_at }}</th>
                                <td><span class="badge badge-{{ $ini->status ==1?'success':'danger' }}">{{ $ini->status ==1?'success':'error' }}</span></td>
                                <td>{{ $ini->message }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
