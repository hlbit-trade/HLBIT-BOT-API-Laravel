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
                        <h5>Order history for market : {{ strtoupper($market) }}</h5>

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
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Trade Type</th>
                                <th scope="col">Price</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Count Match</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $ini)
                            <tr>
                                <th>{{ $ini['id'] }}</th>
                                <td><span class="badge badge-{{ $ini['trade_type']=='buy'?'success':'danger' }}">{{ $ini['trade_type'] }}</span></td>
                                <td>{{ $ini['price'] }}</td>
                                <td>{{ $ini['estimation'] }}</td>
                                <td>{{ $ini['counted_match'] }}</td>
                                <td>
                                    @if($ini['status'] == 'success')
                                        <span class="badge badge-success">success</span>
                                    @elseif($ini['status'] == 'cancelled')
                                        <span class="badge badge-danger">cancelled</span>
                                    @else
                                        <span class="badge badge-warning">pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ini['status'] == 'pending')
                                        <form action="{{ route('cancel-order') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $ini['id'] }}" name="id">
                                            <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                        </form>
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
@endsection
