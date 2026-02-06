@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Orders</h1>

    @if($orders->count() === 0)
    <div class="alert alert-info">No orders found.</div>
    @else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Amount</th>
                <th>Payment Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user_id }}</td>
                <td>₹ {{ number_format($order->total_amount, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $order->payment_status === 'pending' ? 'warning' : 'success' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection