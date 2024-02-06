<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // $getTransaction = Transaction::all();
        // dd($getTransaction);
        $transaction = Transaction::with('transaction_details')->latest()->get();
        return response()->json([
            'transaction' => $transaction
        ], 200);
    }

    public function store(Request $request)
    {
        $invoice_id = $this->generateInvoiceId();
        $user_id = auth()->id(); // Assuming user id is obtained from the authenticated user

        // Create the main transaction
        $transaction = Transaction::create([
            'invoice_id'    => $invoice_id,
            'user_id'       => $user_id, // Include the user id
            'total_items'   => $request->total_items,
            'total_price'   => $request->total_price,
            'discount'      => $request->discount,
            'final_price'   => $request->final_price,
            'cash'          => $request->cash,
            'change'        => $request->change,
            'nama_penerima'     => $request->nama_penerima,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kabupaten'         => $request->kabupaten,
            'nomor_hp'          => $request->nomor_hp,
        ]);

        // Decode the JSON data for transaction_details
        $transaction_details = json_decode($request->transaction_details, true);

        foreach ($transaction_details as $detail) {
            // Assuming $detail is an array with keys 'product_name', 'product_price', and 'qty'
            DB::table('transaction_details')->insert([
                'transaction_id'            => $transaction->id,
                'product_name'              => $detail['product_name'],
                'product_price'             => $detail['product_price'],
                'product_discount'          => $detail['product_discount'],
                'product_discount_price'    => $detail['product_discount_price'],
                'qty'                       => $detail['qty'],
                'created_at'                => now(),
                'updated_at'                => now(),
            ]);

            $product = Product::where('name', $detail['product_name'])->first();
            if ($product) {
                $product->stock -= $detail['qty'];
                $product->save();
            }
        }

        return response()->json([
            'transaction' => $transaction,
            'message' => 'Transaksi berhasil diproses',
        ], 200);
    }

    private function generateInvoiceId()
    {
        $latest_invoice = Transaction::latest('created_at')->first();

        if ($latest_invoice) {
            $latest_invoice_date = $latest_invoice->created_at;
            if ($latest_invoice_date->isToday()) {
                $last_invoice_number = intval(substr($latest_invoice->invoice_id, -3));
            } else {
                $last_invoice_number = 0;
            }
        } else {
            $last_invoice_number = 0;
        }

        $current_date = now();
        $invoice_number = str_pad($last_invoice_number + 1, 3, '0', STR_PAD_LEFT);
        $invoice_id = $current_date->format('ymd') . $invoice_number;

        return $invoice_id;
    }
}
