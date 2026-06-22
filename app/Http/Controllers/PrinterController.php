<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use App\Models\Station;
class PrinterController extends Controller
{
    public function index()
    {
            if (!auth()->user()->can('printer.view') && !auth()->user()->can('printer.create')) {
                abort(403, 'Unauthorized action.');
            }
            $printers = Printer::with('station')->get();
            $printers = $printers->transform(function($item) {

                return [
                    'name' => $item->name,
                    'type' => Printer::connection_type_str($item->connection_type),
                    'profile' => Printer::capability_profile_srt($item->capability_profile),
                    'line' => $item->char_per_line,
                    'ip' => $item->ip_address,
                    'port' => $item->port,
                    'path' => $item->path,
                    'id' => $item->id,
                    'printer_type' => $item->printer_type,
                    'station_id' => $item->station_id,
                    'station_name' => $item->station->name ?? '',
                    'is_active' => (bool) $item->is_active,
                    'is_default' => (bool) $item->is_default,
                ];
            })->toArray();
        $capability_profiles = Printer::capability_profiles();
        $connection_types = Printer::connection_types();
        $printer_types = Printer::printer_types();
        $stations = Station::active()->orderBy('display_order')->orderBy('name')->pluck('name', 'id')->prepend(__('— None —'), '');
        return view('printer.index', compact('capability_profiles', 'connection_types', 'printer_types', 'stations'))
        ->with('printers', json_encode($printers, JSON_NUMERIC_CHECK));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('printer.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['name', 'connection_type', 'capability_profile',
                                        'ip_address', 'port', 'path', 'char_per_line',
                                        'invoice_layout',
                                        'printer_type', 'station_id', 'is_active', 'is_default',
            ]);

            $input['created_by'] = auth()->user()->id;
            $input['printer_type'] = $input['printer_type'] ?? Printer::TYPE_RECEIPT;
            $input['station_id'] = !empty($input['station_id']) ? (int) $input['station_id'] : null;
            $input['is_active'] = !empty($input['is_active']);
            $input['is_default'] = !empty($input['is_default']);
            if ($input['printer_type'] === Printer::TYPE_RECEIPT) {
                $input['station_id'] = null;
            }
            ;

            if ($input['connection_type'] == 'network') {
                $input['path'] = '';
            } elseif (in_array($input['connection_type'], ['windows', 'linux', 'file'])) {
                $input['ip_address'] = '';
                $input['port'] = '';
            }

            $printer = new Printer;
            $printer->fill($input)->save();

            $output = ['success' => 1,
                            'msg' => __('Added Success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("Something Went Wrong")
                        ];
        }

        return redirect('printers')->with('status', $output);
    }

    public function destroy(Request $request)
    {

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $printer = Printer::whereIn('id', $ids)->delete();

                $output = ['success' => true,
                            'msg' => __("printer.deleted_success")
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
