@extends('layouts.cashier')

@section('content')
<style>
    .box {
        max-width: 420px;
        margin: 60px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .price {
        font-size: 1.4rem;
        font-weight: bold;
        color: #2563eb;
    }

    .count {
        font-size: 2rem;
        font-weight: bold;
        color: #dc2626;
    }

    .qr-box {
        margin: 20px 0;
    }

    .md5 {
        font-size: 13px;
        color: #6c757d;
        word-break: break-all;
    }

    .btn-back {
        display: inline-block;
        margin-top: 15px;
        padding: 12px 30px;
        background: #2563eb;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    }

    .alert-danger {
        background: #fee2e2;
        color: #dc2626;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #fecaca;
    }
</style>

<div class="box">
    <h2 class="title">Scan KHQR</h2>

    <p>
        <strong>{{ $product->name }}</strong><br>
        <span class="price">${{ number_format($product->price, 2) }}</span>
    </p>

    @if ($qr)
        <div class="qr-box">
            {!! QrCode::size(220)->generate($qr) !!}
        </div>
        <p class="md5">
            <strong>MD5:</strong> {{ $md5 }}
        </p>
    @else
        <div class="alert-danger">
            Failed to generate QR
        </div>
    @endif

    <div class="mt-3">
        <div id="countdown" class="count">120</div>
        <small>
            Expire in <span id="seconds">120</span>s
        </small>
    </div>

    <a href="{{ route('home') }}" class="btn-back">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
</div>

<div id="paymentSuccessPopup"
     class="fixed inset-0 z-[2000] hidden items-center justify-center bg-slate-900/50 px-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
            <i class="fas fa-check text-3xl text-emerald-600"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800">ទូទាត់ទឹកប្រាក់បានជោគជ័យ</h3>
        <p class="mt-2 text-sm text-slate-500">កំពុងត្រឡប់ទៅទំព័រដើម...</p>
    </div>
</div>

<script>
    let timeLeft = 120;

    const countdownElement = document.getElementById('countdown');
    const secondsText = document.getElementById('seconds');
    const paymentSuccessPopup = document.getElementById('paymentSuccessPopup');

    function showPaymentSuccessPopup() {
        paymentSuccessPopup.classList.remove('hidden');
        paymentSuccessPopup.classList.add('flex');
    }

    const timer = setInterval(() => {
        timeLeft--;

        countdownElement.textContent = timeLeft;
        secondsText.textContent = timeLeft;

        if (timeLeft > 0) {
            fetch("{{ route('verify.transaction') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    md5: "{{ $md5 }}"
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.responseCode === 0) {
                    clearInterval(timer);
                    showPaymentSuccessPopup();
                    setTimeout(() => {
                        window.location.href = "{{ route('home') }}";
                    }, 1200);
                } else if (data.failed) {
                    clearInterval(timer);
                    alert("Transaction failed. Please try again.");
                    window.location.href = "{{ route('home') }}";
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        if (timeLeft <= 0) {
            clearInterval(timer);
            alert("QR expired.");
            window.location.href = "{{ route('home') }}";
        }
    }, 1000);
</script>
@endsection