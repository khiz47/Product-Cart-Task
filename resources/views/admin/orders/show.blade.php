@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Order #{{ $order->id }}</h1>

    <div class="mb-3">
        <strong>User ID:</strong> {{ $order->user_id }} <br>
        <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }} <br>
        <strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>₹ {{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹ {{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        <h4>Total Amount: ₹ {{ number_format($order->total_amount, 2) }}</h4>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">
        Back to Orders
    </a>
</div>
@endsection