<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ChekoutRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class ChekoutController extends Controller
{
    public function chekout(ChekoutRequest $request)
    {
        $data = $request->except('transaction_details');

        $data['uuid'] = 'TDL' . mt_rand(1000,9999) . mt_rand(100,999);

        $transaction = Transaction::create($data);

        foreach ($request->transaction_details as $product)
        {
            $details[] = new TransactionDetail([
                'transactions_id' => $transaction->id,
                'products_id' => $product
            ]);
//          function untuk pengurangan stok quantity
            Product::find($product)->decrement('quantity');

        }

        $transaction->details()->saveMany($details);

        return ResponseFormatter::success($transaction);
    }
}
