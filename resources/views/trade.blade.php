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
                    <div class="card-header">Trade</div>

                    <div class="card-body">
                        <h5>Trade Action</h5>
                        <form id="form-market" action="" method="GET">
                            <label>Select Market</label>
                            <select class="form-control" name="pair" required onchange="submit()">
                                <option value="btcusd" {{ isset($_GET['pair']) && $_GET['pair'] == 'btcusd'? 'selected':'' }}>BTCUSD</option>
                                <option value="ethusd" {{ isset($_GET['pair']) && $_GET['pair'] == 'ethusd'? 'selected':'' }}>ETHUSD</option>
                                <option value="bpinkusd" {{ isset($_GET['pair']) && $_GET['pair'] == 'bpinkusd'? 'selected':'' }}>BPINKUSD</option>
                            </select>
                        </form>
                        <script>
                            function submit() {
                                document.getElementById('form-market').submit();
                            }
                        </script>
                        <br>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Last Price</p>
                                <h5>{{ $data['last'] }}</h5>
                            </div>
                            <div class="col-md-2">
                                <p>Volume 24Hr</p>
                                <h5>{{ $data['volume'] }}</h5>
                            </div>
                            <div class="col-md-3">
                                <p>High</p>
                                <h5>{{ $data['high'] }}</h5>
                            </div>
                            <div class="col-md-3">
                                <p>Low</p>
                                <h5>{{ $data['low'] }}</h5>
                            </div>
                            <div class="col-md-2">
                                <p>24Hr Change (%)</p>
                                <h5>{{ $data['last_24h_change'] }}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <marquee style="background: rgba(0,0,0,.03)">
                                @foreach($balance_fiat as $ini)
                                    {{ $ini['name'] }} : {{ $ini['balance'] }} {{ strtoupper($ini['code']) }} |
                                @endforeach
                                @foreach($balance_crypto as $ini)
                                    {{ $ini['name'] }} : {{ $ini['balance'] }} {{ strtoupper($ini['code']) }} |
                                @endforeach
                            </marquee>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="text-center">Your current condition<br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Condition</button></h5>

                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add Condition</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{ route('setting.save') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h5 class="text-center">Select your condition</h5>

                                                            <input type="hidden" name="pair" value="{{ isset($_GET['pair']) ? $_GET['pair'] :'btcusd' }}">
                                                            <label>Type</label>
                                                            <select name="type" class="form-control">
                                                                <option value="buy">Buy</option>
                                                                <option value="sell">Sell</option>
                                                            </select>

                                                            <label>Time Repeat</label>
                                                            <select name="repeat" class="form-control">
                                                                <option value="0">No Repeat</option>
                                                                <option value="1">1 Minutes</option>
                                                                <option value="5">5 Minutes</option>
                                                                <option value="15">15 Minutes</option>
                                                                <option value="30">30 Minutes</option>
                                                                <option value="60">60 Minutes</option>
                                                            </select>

                                                            <label>Amount</label>
                                                            <select name="amount" class="form-control" required>
                                                                <option value="1">1% Balance</option>
                                                                <option value="5">5% Balance</option>
                                                                <option value="10">10% Balance</option>
                                                                <option value="50">50% Balance</option>
                                                                <option value="100">100% Balance</option>
                                                            </select>

                                                            <label>Where 24Hr</label>
                                                            <div class="row" style="margin-left: 1px; margin-right: 1px">
                                                                <select name="type_24hr" class="form-control col-3" required>
                                                                    <option value="none">None</option>
                                                                    <option value="more">More Than</option>
                                                                    <option value="same">Exactly</option>
                                                                    <option value="less">Less Than</option>
                                                                </select>
                                                                <input type="number" class="form-control col-9" step="any" name="value_24hr" placeholder="Enter 24Hr" required>
                                                            </div>

                                                            <label>On Price</label>
                                                            <div class="row" style="margin-left: 1px; margin-right: 1px">
                                                                <select name="type_price" class="form-control col-3" required>
                                                                    <option value="none">None</option>
                                                                    <option value="more">More Than</option>
                                                                    <option value="same">Exactly</option>
                                                                    <option value="less">Less Than</option>
                                                                </select>
                                                                <input type="number" class="form-control col-9" step="any" name="value_price" placeholder="Enter Price" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Market</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Repeat</th>
                                        <th scope="col">24Hr</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($setting as $ini)
                                        <tr>
                                            <td>{{ strtoupper($ini->pair) }}</td>
                                            <td>{{ $ini->type }}</td>
                                            <td>@if($ini->repeat >0)
                                                Every {{ $ini->repeat }} Minutes
                                                @else
                                                No Repeat
                                                @endif
                                            </td>
                                            <td>{{ $ini->type_24hr }} {{ $ini->value_24hr }} %</td>
                                            <td>{{ $ini->type_price }} {{ $ini->value_price }}</td>
                                            <td>{{ $ini->amount }} % of Balance</td>
                                            <td>
                                                @if($ini->status == 1)
                                                    <span class="badge badge-success">on</span>
                                                @elseif($ini->status == 0)
                                                    <span class="badge badge-danger">off</span>
                                                @else
                                                    <span class="badge badge-info">done</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ini->status == 1)
                                                    <button class="btn btn-sm btn-danger">disable</button>
                                                @elseif($ini->status == 0)
                                                    <button class="btn btn-sm btn-succedd">enable</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
