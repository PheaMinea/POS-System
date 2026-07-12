@extends("layouts.cashier")

@section('content')
<style>
    .box {
        max-width: 500px;
        margin: 60px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        text-align: center;
    }

    .product-img {
        width: 180px;
        border-radius: 10px;
        margin: 15px 0;
    }

    .price {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2563eb;
        margin: 15px 0;
    }

    .btn-primary-custom {
        background: #2563eb;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-primary-custom:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    }
</style>

<div class="box">
    <h2>{{ $product->name }}</h2>

    <img src="{{ $product->image }}" class="product-img">

    <p>{{ $product->description }}</p>

    <div class="price">
        ${{ number_format($product->price, 2) }}
    </div>

    <form action="{{ route('checkout', $product->id) }}" method="POST">
        @csrf
        <button class="btn-primary-custom">
            Generate KHQR to Pay
        </button>
    </form>
</div>
@endsection