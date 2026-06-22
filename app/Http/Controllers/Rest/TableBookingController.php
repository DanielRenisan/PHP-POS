<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Table;
use App\Models\TableBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class TableBookingController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('table-booking.view') && !auth()->user()->can('table-booking.create')) {
            abort(403, 'Unauthorized action.');
        }
        $table = Table::all();

        $customer_name = Contact::select('contacts.*','contacts.first_name')
        ->whereIn('contacts.contact_type_id', [1])->get();

        $tableBooking = TableBooking::select('table_bookings.*','tables.table_name','contacts.id as contact_id','contacts.first_name as customerName')
        ->leftjoin('tables', 'tables.id', 'table_bookings.table_id')
        ->leftjoin('contacts', 'contacts.id', 'table_bookings.contact_id')
        ->get();
        $tableBooking = $tableBooking->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'table_id' => $item->table_id,
                'contact_id' => $item->contact_id,
                'booking_date_time' => $item->booking_date_time,
                'reserved_book_date_time' => $item->reserved_book_date_time,
                'table_name' => $item->table_name,
                // 'name' => $item->name,
                'customerName' => $item->customerName,
                'status' => $item->status,
                'action' => 1
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($tableBooking)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">Cancel</span>';
                if($row['status'] == 'Reserved')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:70px;color:#fff;">Reserved</span>';
                }
                return $span;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }
        return view('rest.table_booking.index',compact('table','customer_name'));
    }


    public function store(Request $request)
    {
        if (!auth()->user()->can('table-booking.create')) {
            abort(403, 'Unauthorized action.');
        }
        $tableBooking = new TableBooking();
        $tableBooking->table_id  = $request->table_id;
        $tableBooking->contact_id = $request->contact_id;
        $tableBooking->booking_date_time = $request->booking_date_time;
        $tableBooking->reserved_book_date_time = $request->reserved_book_date_time;
        $tableBooking->status = $request->has('status') ? 'Reserved' : 'Cancel';
        // dd($tableBooking);
        $tableBooking->save();
        $table = Table::find($request->table_id);
        $table->available_status = 1;
        $table->save();

        return redirect()->route('table_booking.index')
            ->with('success', 'tableBooking successfully Created!!');
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('table-booking.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $tableBooking = TableBooking::find($id);
        $excitTable = Table::find($tableBooking->table_id);
        $excitTable->available_status = 0;
        $excitTable->save();
        $table = Table::find($request->table_id);
        $table->available_status = 1;
        $table->save();
        $tableBooking->table_id  = $request->table_id;
        $tableBooking->contact_id = $request->contact_id;
        $tableBooking->booking_date_time = $request->booking_date_time;
        $tableBooking->reserved_book_date_time = $request->reserved_book_date_time;
        $tableBooking->status = $request->has('status') ? 'Reserved' : 'Cancel';
        $tableBooking->save();
        return redirect()->route('table_booking.index')
            ->with('success', 'tableBooking successfully Updated!!');
    }
    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('table-booking.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $tableBooking =  TableBooking::whereIn('id', $ids)->delete();;

                $output = [
                    'success' => true,
                    'msg' => __("Deleted Success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function show($id)
    {
        $tableBooking = TableBooking::find($id);

        return $tableBooking;
    }
}
