<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends BaseApiController
{
    public function index()
    {
        try {
            $payments = Payment::latest()->get();
            return $this->success($payments, 'Payments Retrieved Successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        return $this->error('Not implemented', 501);
    }

    public function show($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            return $this->success($payment, 'Payment Retrieved Successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        return $this->error('Not implemented', 501);
    }

    public function destroy($id)
    {
        return $this->error('Not implemented', 501);
    }
}
