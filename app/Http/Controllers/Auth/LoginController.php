<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transactions;
use App\Models\Customer;
use App\Models\RoomAssign;
use App\Models\RoomType;
use App\Models\TransactionPayment;
use App\Models\Product;
use App\Models\BookingRoom;
use App\Models\BookingType;
use App\Models\BookingSource;
use App\Models\Complementary;
use Hash;
use DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }

    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }
      
    public function username()
    {
        return 'username';
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("home")->withSuccess('Great! You have Successfully loggedin');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            $total_sales = Transactions::whereIn('type', ['order', 'checkout', 'checkin', 'booking'])->where('status', '!=', 'canceled')->sum('final_total');
            $total_purchase = Transactions::where('type', 'purchase')->where('status', 'received')->sum('final_total');
            $total_expense = Transactions::where('type', 'expense')->sum('final_total');
            $total_amount = Transactions::whereIn('type', ['order', 'checkin', 'booking', 'checkout'])->sum('final_total');
            $total_paid = TransactionPayment::join('transactions as t', 't.id', '=', 'transaction_payments.transaction_id')
            ->whereIn('t.type', ['order', 'checkin', 'booking', 'checkout'])->where('t.status', '!=', 'canceled')->sum('amount');
            $due = $total_sales - $total_paid;
            $hotel_sales = Transactions::whereIn('type', ['checkout', 'checkin', 'booking'])->where('status', '!=', 'canceled')->sum('final_total');
            $restaurant_sales = Transactions::where('type', 'order')->where('status', 'final')->where('status', '!=', 'canceled')->sum('final_total');
            $bar_sales = Transactions::join('transaction_sell_lines as TSL', 'transactions.id', '=', 'TSL.transaction_id')
            ->join('products as PRO', 'TSL.product_id', '=', 'PRO.id')
            ->where('transactions.type', 'order')
            ->where('PRO.is_bot', 1)
            ->where('transactions.status', '!=', 'canceled')->sum('TSL.sub_total');

            $today_sale = Transactions::where('type', 'order')->where('status', 'final')->whereDate('updated_at', date('Y-m-d'))->sum('final_total');
            
            $today_booking = Booking::whereDate('check_in_at', date('Y-m-d'))->groupBy('id')->count();
            $total_booking_amount = Transactions::whereIn('type', ['checkin', 'booking', 'checkout'])->where('status','!=', 'canceled')->sum('final_total');
            $total_customer = Customer::count();
            $total_booking = Transactions::where('type', 'checkin')->count();

            $today = now()->toDateString(); // or Carbon::today()

            $today_total_sales = Transactions::whereIn('type', ['order', 'checkout', 'checkin', 'booking'])
                ->where('status', '!=', 'canceled')
                ->whereDate('created_at', $today)
                ->sum('final_total');

            $today_total_purchase = Transactions::where('type', 'purchase')
                ->where('status', 'received')
                ->whereDate('created_at', $today)
                ->sum('final_total');

            $today_total_expense = Transactions::where('type', 'expense')
                ->whereDate('created_at', $today)
                ->sum('final_total');

            $today_total_paid = TransactionPayment::join('transactions as t', 't.id', '=', 'transaction_payments.transaction_id')
                ->whereIn('t.type', ['order', 'checkin', 'booking', 'checkout'])->where('t.status', '!=', 'canceled')
                ->whereDate('transaction_payments.created_at', $today)
                ->sum('amount');

            $today_due = $today_total_sales - $today_total_paid;

            $today_hotel_sales = Transactions::whereIn('type', ['checkout', 'checkin', 'booking'])
                ->where('status', '!=', 'canceled')
                ->whereDate('created_at', $today)
                ->sum('final_total');

            $today_restaurant_sales = Transactions::where('type', 'order')
                ->where('status', 'final')
                ->where('status', '!=', 'canceled')
                ->whereDate('created_at', $today)
                ->sum('final_total');


            $reservation = [];
            $i = 1;
            while($i<=12)
            {
                $count = Transactions::where("type", "booking")->whereMonth('created_at', $i)->count();
                array_push($reservation, $count);
                $i++;
            }
            $today_checkin = Transactions::join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
            ->where("transactions.type", "checkin")->whereDate('bookings.check_in_at', date('Y-m-d'))->count();
            $checkin = Transactions::where("type", "checkin")->count();
            $checkout = Transactions::where("type", "checkout")->count();
            $pending = Transactions::where("type", "booking")->count();
            $data = [$checkin,$checkout,$pending];
            

            $rooms = RoomAssign::all();
            $types = RoomType::all();
            $originalRoomData = [];
            foreach($rooms as $sin_room)
            {
                $bookings = BookingRoom::where('booking_rooms.room_no', $sin_room->room_id)
                ->join('transactions as tr', 'booking_rooms.transaction_id', '=', 'tr.id')
                ->whereIn('tr.type', ['checkout', 'checkin', 'booking'])->select([
                    'tr.type',
                    'tr.id',
                    'booking_rooms.check_in_at',
                    'booking_rooms.check_out_at',
                ])->get();
                $ranges = []; 
                foreach($bookings as $sin_booking)
                {
                    $status = '';
                    if( $sin_booking->type == 'booking')
                    {
                        $status = 'reserved';
                    }
                    if( $sin_booking->type == 'checkin')
                    {
                        $status = 'booked';
                    }
                    $range = [
                        'status' => $status, 
                        'start' => date('Y/m/d', strtotime( $sin_booking->check_in_at)),
                        'end' => date('Y/m/d', strtotime( $sin_booking->check_out_at)),
                    ];
                    array_push($ranges, $range);
                }
                $datas = [
                    'name' => "Room ".  $sin_room->room_id. '('. $sin_room->room_type.')',
                    'ranges' => $ranges
                ];

                array_push($originalRoomData, $datas);
            } 


            $transactions = Transactions::whereIn("transactions.type", ['purchase', 'order', 'booking', 'checkin', 'checkout'])->select([
                'transactions.type',
                'transactions.invoice_no',
                'transactions.final_total',
                'transactions.payment_status',
                'transactions.updated_at',
            ])->latest()->take(10)->get();
            $transactions = $transactions->transform(function($item) {

                return [
                    'type' => $item->type,
                    'room' => $item->invoice_no,
                    'date' => date('Y-m-d H:i A', strtotime($item->updated_at)),
                    'status' =>  $item->payment_status,
                ];
            })->toArray();
            
            $stocks = Product::join('product_stocks as ps', 'products.id', '=', 'ps.product_id')
            ->where('products.enable_stock', 1)
            ->select(['products.name' , 'products.sku_code', 'ps.qty_available', 'products.alert_quantity'])->get();
            $stocks = $stocks->transform(function($item){
                if($item->alert_quantity >= $item->qty_available)
                {
                    return [
                        'name' => $item->name,
                        'sku_code' => $item->sku_code,
                        'qty'  => $item->qty_available   
                    ];
                }
                
            })->toArray();
            $stocks = array_filter($stocks);
            $reminders = Transactions::orderBy('transactions.id', 'DESC')
            ->join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
            ->leftjoin('customers', 'bookings.contact_id', '=', 'customers.id')
            ->where(function($query){
                $query->whereDate('bookings.check_out_at', date('Y-m-d'));
                $query->orWhereDate('bookings.check_in_at', date('Y-m-d'));
            })
            ->whereIn('transactions.type', ['checkin', 'booking'])
                ->select(
                    'transactions.final_total',
                    'bookings.ref_no as ref_no',
                    'customers.first_name as customer',
                    'bookings.check_in_at',
                    'bookings.check_out_at',
                    'transactions.type',
                    'transactions.payment_status',
                )->get();
            $reminders = $reminders->transform(function($item) {
                $status = '';
                if($item->type == 'checkin')
                {
                    $status = 'Checkin';
                }
                if($item->type == 'booking')
                {
                    $status = 'Booking';
                }
                return [
                    'customer' => $item->customer,
                    'ref_no' => $item->ref_no,
                    'date' => date('Y-m-d H:i A', strtotime($item->check_in_at)),
                    'status' => $status
                ];
            })->toArray();
            $sale_array = [];
            $expense_array = [];
            $months = [];
            for ($m=1; $m<=12; $m++) {
                $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                $startDate = date("Y-m-01",strtotime($month)); 
                $endDate = date("Y-m-t",strtotime($month));
                $count = Transactions::where('type', 'order')->where('status', 'final')->whereDate('updated_at', '>=', $startDate)
                ->whereDate('updated_at', '<=', $endDate)->count();
                $expense_count = Transactions::where('type', 'expense')->whereDate('updated_at', '>=', $startDate)
                ->whereDate('updated_at', '<=', $endDate)->count();
                array_push($sale_array, $count);
                array_push($expense_array, $expense_count);
            }
            $customers = Customer::forDropdown();
            $payment_line = $this->dummyPaymentLine;
            $payment_types = $this->payment_types();
            $booking_types = BookingType::pluck('name', 'id');
            $sources = BookingSource::pluck('name', 'id');
            $complementaries = Complementary::pluck('name', 'id');
            $room_types = RoomType::pluck('name')->toArray();  


            // =============== DAILY SALES (THIS WEEK & LAST WEEK) ===================

            // Get start and end of this week (Sun → Sat)
            $this_week_start = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            $this_week_end   = Carbon::now()->endOfWeek(Carbon::SATURDAY);

            $last_week_start = Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY);
            $last_week_end   = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY);

            // Count of orders this week (Sun → Sat)
            $this_week_count = [];
            for ($d = 0; $d < 7; $d++) {
                $date = $this_week_start->copy()->addDays($d)->toDateString();
                $count = Transactions::where('type', 'order')
                    ->where('status', 'final')
                    ->whereDate('updated_at', $date)
                    ->count();
                $this_week_count[] = (int) $count;
            }

            // Count of orders last week
            $last_week_count = [];
            for ($d = 0; $d < 7; $d++) {
                $date = $last_week_start->copy()->addDays($d)->toDateString();
                $count = Transactions::where('type', 'order')
                    ->where('status', 'final')
                    ->whereDate('updated_at', $date)
                    ->count();
                $last_week_count[] = (int) $count;
            }

            return view('home', compact('today_booking','total_booking_amount', 'total_customer','total_sales','total_purchase','total_expense',
            'total_booking','due','today_sale','today_checkin','customers','payment_line','payment_types','booking_types','sources','complementaries','room_types',
            'rooms','types', 'restaurant_sales', 'hotel_sales','bar_sales',
            'today_total_sales','today_total_purchase','today_total_expense','today_total_paid','today_hotel_sales','today_restaurant_sales','today_due'
            ))
            ->with('reservation',json_encode($reservation,JSON_NUMERIC_CHECK))
            ->with('data',json_encode($data,JSON_NUMERIC_CHECK))
            ->with('transactions',json_encode($transactions,JSON_NUMERIC_CHECK))
            ->with('stocks',json_encode($stocks,JSON_NUMERIC_CHECK))
            ->with('reminders',json_encode($reminders,JSON_NUMERIC_CHECK))
            ->with('sale_array',json_encode($sale_array,JSON_NUMERIC_CHECK))
            ->with('expense_array',json_encode($expense_array,JSON_NUMERIC_CHECK))
            ->with('originalRoomData',json_encode($originalRoomData,JSON_NUMERIC_CHECK))
            ->with('this_week_count', json_encode($this_week_count, JSON_NUMERIC_CHECK))
            ->with('last_week_count', json_encode($last_week_count, JSON_NUMERIC_CHECK));
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];


        return $payment_types;
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}
