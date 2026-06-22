<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transactions;
use App\Models\BusinessLocation;
use App\Models\Business;
use App\Models\Printer;

class PrinterController extends Controller
{
    public function index($id)
    {
        $transaction = Transactions::where('id', $id)
        ->with(['sell_lines', 'sell_lines.product'])->first();
        $location_details = BusinessLocation::find($transaction->location_id);

        $output['is_enabled'] = true;
        $business_details = Business::first();
        $output['flag'] = $business_details->business_code;
        $layout = 'receipt';
        $output['data'] = $this->receipt_details($transaction, $location_details, $business_details);
        $output['printer_config'] = $this->printerConfig($layout);
        return response()->json($output);
    }

    public function printerConfig($layout)
    {
        $printer = Printer::where('invoice_layout', $layout)
                    ->first();

        $output = [];

        if (!empty($printer)) {
            $output['connection_type'] = $printer->connection_type;
            $output['capability_profile'] = $printer->capability_profile;
            $output['char_per_line'] = $printer->char_per_line;
            $output['ip_address'] = $printer->ip_address;
            $output['port'] = $printer->port;
            $output['path'] = $printer->path;
        }

        return $output;
    }

    private function receipt_details($transaction, $location_details, $business_details)
    {
        $output['content'] = view('pos.receipt.receipt', compact('transaction', 'location_details', 'business_details'))->render();
        return (object)$output;
    }
}
