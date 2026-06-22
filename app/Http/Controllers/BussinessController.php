<?php

namespace App\Http\Controllers;

use App\Models\Business;

use App\Models\Currency;
use Spatie\Permission\Models\Permission;

use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BussinessController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('setting.update')) {
            abort(403, 'Unauthorized action.');
        }
        $currencies =  Currency::select('id', DB::raw("concat(country, ' - ',currency, '(', code, ') ') as info"))
        ->orderBy('country')
        ->pluck('info', 'id');

        $business = Business::first();
        $timezone_list = $this->allTimeZones();
        $accounting_methods = $this->allAccountingMethods();
        $months = $this->months();


        return view('setting.index', compact('currencies', 'business', 'timezone_list', 'accounting_methods', 'months'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('setting.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $business_details = $request->only(['name', 'start_date', 'currency_id', 'default_profit_percent', 'time_zone', 'fy_start_month', 'accounting_method',
                'country', 'reg_doc_no', 'fax_no', 'website', 'address', 'day_duration','address_two',
                'city','mobile','phone','email', 'printer_display']);

            if (!empty($business_details['start_date'])) {
                $business_details['start_date'] = Carbon::createFromFormat('m/d/Y', $business_details['start_date'])->toDateString();
            }
            $checkboxes = ['is_bot',
                'is_kot', 'is_need_food_calculation'];
            foreach ($checkboxes as $value) {
                $business_details[$value] = !empty($request->input($value)) &&  $request->input($value) == 1 ? 1 : 0;
            }
            
            if ($request->hasFile('business_logo') && $request->file('business_logo')->isValid()) {
                $path = $request->business_logo->store('public/business_logos');
                $business_details['logo'] = str_replace('public/business_logos/', '', $path);
            }

            
            
            $business_id = request()->session()->get('user.business_id');
            $business = Business::first();

            //Update business settings
            if (!empty($business_details['logo'])) {
                $business->logo = $business_details['logo'];
            } else {
                unset($business_details['logo']);
            }

           
            $business->fill($business_details);
            $business->save();
            
            //update session data
            $request->session()->put('business', $business);

            //Update Currency details
            $currency = Currency::find($business->currency_id);
            $request->session()->put('currency', [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'symbol' => $currency->symbol,
                        'thousand_separator' => $currency->thousand_separator,
                        'decimal_separator' => $currency->decimal_separator,
                        ]);

            $request->session()->put('financial_year', $financial_year);
            
            $output = ['success' => 1,
                            'msg' => __('Settings Updated Success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }
        return redirect("business-setting")->with('status', $output);
    }

    private function allTimeZones()
    {
        $datetime = new \DateTimeZone("EDT");

        $timezones = $datetime->listIdentifiers();
        $timezone_list = [];
        foreach ($timezones as $timezone) {
            $timezone_list[$timezone] = $timezone;
        }

        return $timezone_list;
    }

    private function allAccountingMethods()
    {
        return [
            'fifo' => __('FIFO (First In First Out)'),
            'lifo' => __('LIFO (Last In First Out)')
        ];
    }

    private function months()
    {
        return  [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
    }

}
