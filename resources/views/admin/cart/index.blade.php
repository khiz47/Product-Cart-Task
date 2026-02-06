@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Cart Items (User ID: 1)</h1>

    @if(count($items) === 0)
    <div class="alert alert-info">
        Cart is empty.
    </div>
    @else
    <table class="table table-bordered table-striped">
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
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['product_name'] }}</td>
                <td>₹ {{ number_format($item['price'], 2) }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>₹ {{ number_format($item['subtotal'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        <strong>Total Quantity:</strong> {{ $totalQuantity }} <br>
        <strong>Grand Total:</strong> ₹ {{ number_format($grandTotal, 2) }}
    </div>
    @endif
</div>
@endsection