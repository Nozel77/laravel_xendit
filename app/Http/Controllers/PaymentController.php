<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct() {
        Configuration::setXenditKey('xnd_development_I2eMdJI9KVjf82j9RjsunTt5QcjVuAKZvom3H7NZRY8NUpbUhRhD91JuGvejwrJ');
        $this->apiInstance = new InvoiceApi();
    }

    public function store(Request $request){
        $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
            'external_id' => (string) Str::uuid(),
            'description' => $request->description,
            'amount' => $request->amount,
            'payer_email' => $request->payer_email,
          ]);

          $result = $this->apiInstance->createInvoice($create_invoice_request);

          $payment = new Payment();
          $payment->status = 'PENDING';
          $payment->checkout_link = $result['invoice_url'];
          $payment->external_id = $create_invoice_request['external_id'];
          $payment->save();

          return response()->json($payment);
          
    }
}
