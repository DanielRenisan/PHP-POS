<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingSource;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\BookingType;
class BookingSourceController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('booking-source.view') && !auth()->user()->can('booking-source.create')) {
            abort(403, 'Unauthorized action.');
        }

            $sources = BookingSource::join('booking_types', 'booking_types.id', '=', 'booking_sources.booking_type')
            ->select(['booking_sources.booking_type',
               'booking_sources.name',
               'booking_sources.rate',
               'booking_sources.id',
               'booking_types.name as type'
            ])->get();
            $sources = $sources->transform(function($item){
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'name' => $item->name,
                    'rate' => $item->rate,
                    'action' => 1,
                    'type_id' => $item->booking_type
                ];
            })->toArray();
            
        $types = BookingType::forDropdown();
        return view('source.index', compact('types'))
        ->with('sources',json_encode($sources,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('booking-source.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('source.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('booking-source.create')) {
            abort(403, 'Unauthorized action.');
        }
        $com = new BookingSource();
        $msg  = 'Created';
        $com->booking_type = $request->booking_type;
        $com->name = $request->name;
        $com->rate = $request->rate;
        $com->save();

        return redirect("booking-sources")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('booking-source.update')) {
            abort(403, 'Unauthorized action.');
        }
        $source = BookingSource::findOrFail($id);
        return view('source.edit', compact('source'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('booking-source.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $source = BookingSource::findOrFail($id);
            $source->booking_type = $request->booking_type;
            $source->name = $request->name;
            $source->rate = $request->rate;
            $source->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('booking-source.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $source = BookingSource::whereIn('id', $ids)->delete();

                $output = ['success' => true,
                            'msg' => __("Deleted Success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
}
