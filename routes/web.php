<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\Rest\ContactController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FoodCalculationController;
use App\Http\Controllers\WakeUpController;
use App\Http\Controllers\RoomFacilityController;
use App\Http\Controllers\RoomSizeController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\RoomDetailController;
use App\Http\Controllers\BookingTypeController;
use App\Http\Controllers\ComplementaryController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\FloorPlaneController;
use App\Http\Controllers\BookingSourceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomAssignController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BussinessController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CancellationController;
use App\Http\Controllers\RoomStatusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfitReportController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\BOTController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StationDisplayController;
use App\Http\Controllers\StationTicketController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Rest\ProductCategoryController;
use App\Http\Controllers\Rest\TypeController;
use App\Http\Controllers\Rest\CousineController;
use App\Http\Controllers\Rest\MenuController;
use App\Http\Controllers\Rest\DrintTypeController;
use App\Http\Controllers\Rest\UnitController;
use App\Http\Controllers\Rest\DepartmentController;
use App\Http\Controllers\Rest\OrderTypeController;
use App\Http\Controllers\Rest\TableLocationController;
use App\Http\Controllers\Rest\TableController;
use App\Http\Controllers\Rest\PaymentMethodController;
use App\Http\Controllers\Rest\ContactTypeController;
use App\Http\Controllers\Rest\CustomerTypeController;
use App\Http\Controllers\Rest\CustomerGroupController;
use App\Http\Controllers\Rest\EventTypeController;
use App\Http\Controllers\Rest\ContactEventController;
use App\Http\Controllers\Rest\BrandController;
use App\Http\Controllers\Rest\TableBookingController;
use App\Http\Controllers\Rest\EMDestinationController;
use App\Http\Controllers\Rest\ContactDocumentController;
use App\Http\Controllers\Rest\ProductController;
use App\Http\Controllers\Rest\ProductVaritationController;
use App\Http\Controllers\Rest\EmployeeTypeController;
use App\Http\Controllers\Rest\AttendanceTypeController;
use App\Http\Controllers\Rest\PositionController;
use App\Http\Controllers\Rest\EmployeeController;
use App\Http\Controllers\Rest\TaxController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\PurchaseWastageController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\OpenStockController;
use App\Http\Controllers\TransactionPaymentController;
use App\Http\Controllers\ChangePasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Authentication
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('post-login', [LoginController::class, 'postLogin'])->name('login.post');
Route::get('registration', [LoginController::class, 'registration'])->name('register');
Route::post('post-registration', [LoginController::class, 'postRegistration'])->name('register.post');

// Public routes
Route::get('/customer', [QRController::class, 'index']);
Route::get('/customer/menu', [QRController::class, 'menu']);
Route::post('/customer/menu', [QRController::class, 'store']);
Route::get('/get_customers', [QRController::class, 'getCustomer']);
Route::post('contact/quick-add', [ContactController::class, 'quickAdd'])->name('contact.create');


Route::middleware(['auth'])->group(function () {

    // Dashboard & Auth
    Route::get('dashboard', [LoginController::class, 'dashboard'])->name('home');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    // Roles
    Route::resource('roles', RoleController::class);
    Route::get('role-delete', [RoleController::class, 'deleteItem']);

    // Customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers/create', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('customers/show/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('download/{id}', [CustomerController::class, 'download'])->name('customers.download');

    //Food calculation
    Route::get('food-calculation', [FoodCalculationController::class, 'index'])->name('food-calculation.index');
    Route::get('food-calculation/create', [FoodCalculationController::class, 'create'])->name('food-calculation.create');
    Route::post('food-calculation/store', [FoodCalculationController::class, 'store'])->name('food-calculation.store');
    Route::get('food-calculation/edit/{id}', [FoodCalculationController::class, 'edit'])->name('food-calculation.edit');
    Route::get('food-calculation/show/{id}', [FoodCalculationController::class, 'show'])->name('food-calculation.show');
    Route::post('food-calculation/update', [FoodCalculationController::class, 'update'])->name('food-calculation.update');
    Route::get('food-calculation/delete', [FoodCalculationController::class, 'delete'])->name('food-calculation.delete');

    // Wake-Up Call Routes
    Route::get('wakeup-call', [WakeUpController::class, 'index'])->name('wakeup-call.index');
    Route::get('wakeup-call/create', [WakeUpController::class, 'create'])->name('wakeup-call.create');
    Route::post('wakeup-call/create', [WakeUpController::class, 'store'])->name('wakeup-call.store');
    Route::get('wakeup-call/{id}/edit', [WakeUpController::class, 'edit'])->name('wakeup-call.edit');
    Route::put('wakeup-call/{id}/edit', [WakeUpController::class, 'update'])->name('wakeup-call.update');
    Route::get('wakeup-call/delete', [WakeUpController::class, 'destroy'])->name('wakeup-call.delete');

    // Room Facilities Routes
    Route::get('room-facilities', [RoomFacilityController::class, 'index'])->name('room-facilities.index');
    Route::get('room-facilities/create', [RoomFacilityController::class, 'create'])->name('room-facilities.create');
    Route::post('room-facilities/create', [RoomFacilityController::class, 'store'])->name('room-facilities.store');
    Route::get('room-facilities/{id}/edit', [RoomFacilityController::class, 'edit'])->name('room-facilities.edit');
    Route::put('room-facilities/edit', [RoomFacilityController::class, 'update'])->name('room-facilities.update');
    Route::get('room-facilities/delete', [RoomFacilityController::class, 'destroy'])->name('room-facilities.delete');
    Route::get('room-facilities/{id}/show', [RoomFacilityController::class, 'show'])->name('room-facilities.show');

    // Room Sizes Routes
    Route::get('room-sizes', [RoomSizeController::class, 'index'])->name('room-sizes.index');
    Route::get('room-sizes/create', [RoomSizeController::class, 'create'])->name('room-sizes.create');
    Route::post('room-sizes/create', [RoomSizeController::class, 'store'])->name('room-sizes.store');
    Route::get('room-sizes/{id}/edit', [RoomSizeController::class, 'edit'])->name('room-sizes.edit');
    Route::put('room-sizes/{id}/edit', [RoomSizeController::class, 'update'])->name('room-sizes.update');
    Route::get('room-sizes/delete', [RoomSizeController::class, 'destroy'])->name('room-sizes.delete');

    // Beds Routes
    Route::get('beds', [BedController::class, 'index'])->name('beds.index');
    Route::get('beds/create', [BedController::class, 'create'])->name('beds.create');
    Route::post('beds/create', [BedController::class, 'store'])->name('beds.store');
    Route::get('beds/{id}/edit', [BedController::class, 'edit'])->name('beds.edit');
    Route::put('beds/{id}/edit', [BedController::class, 'update'])->name('beds.update');
    Route::get('beds/delete', [BedController::class, 'destroy'])->name('beds.delete');

    // Room Details Routes
    Route::get('room-details', [RoomDetailController::class, 'index'])->name('room-details.index');
    Route::get('room-details/create', [RoomDetailController::class, 'create'])->name('room-details.create');
    Route::post('room-details/create', [RoomDetailController::class, 'store'])->name('room-details.store');
    Route::get('room-details/{id}/edit', [RoomDetailController::class, 'edit'])->name('room-details.edit');
    Route::post('room-details/{id}/edit', [RoomDetailController::class, 'update'])->name('room-details.update');
    Route::get('room-details/delete', [RoomDetailController::class, 'destroy'])->name('room-details.delete');

    // Booking Types Routes
    Route::get('booking-types', [BookingTypeController::class, 'index'])->name('booking-types.index');
    Route::get('booking-types/create', [BookingTypeController::class, 'create'])->name('booking-types.create');
    Route::post('booking-types/create', [BookingTypeController::class, 'store'])->name('booking-types.store');
    Route::get('booking-types/{id}/edit', [BookingTypeController::class, 'edit'])->name('booking-types.edit');
    Route::put('booking-types/{id}/edit', [BookingTypeController::class, 'update'])->name('booking-types.update');
    Route::get('booking-types/delete', [BookingTypeController::class, 'destroy'])->name('booking-types.delete');

    // Complementaries Routes
    Route::get('complementaries', [ComplementaryController::class, 'index'])->name('complementaries.index');
    Route::get('complementaries/create', [ComplementaryController::class, 'create'])->name('complementaries.create');
    Route::post('complementaries/create', [ComplementaryController::class, 'store'])->name('complementaries.store');
    Route::get('complementaries/{id}/edit', [ComplementaryController::class, 'edit'])->name('complementaries.edit');
    Route::put('complementaries/{id}/edit', [ComplementaryController::class, 'update'])->name('complementaries.update');
    Route::get('complementaries/delete', [ComplementaryController::class, 'destroy'])->name('complementaries.delete');
    Route::post('complementaries/get-details', [ComplementaryController::class, 'getDetail'])->name('complementaries.get-details');

    // Floor Routes
    Route::get('floors', [FloorController::class, 'index'])->name('floors.index');
    Route::get('floors/create', [FloorController::class, 'create'])->name('floors.create');
    Route::post('floors/create', [FloorController::class, 'store'])->name('floors.store');
    Route::get('floors/{id}/edit', [FloorController::class, 'edit'])->name('floors.edit');
    Route::put('floors/edit', [FloorController::class, 'update'])->name('floors.update');
    Route::get('floors/delete', [FloorController::class, 'destroy'])->name('floors.delete');
    Route::get('floors/{id}', [FloorController::class, 'show'])->name('floors.show');

    // Floor Plans Routes
    Route::get('floor-plans', [FloorPlaneController::class, 'index'])->name('floor-plans.index');
    Route::get('floor-plans/create', [FloorPlaneController::class, 'create'])->name('floor-plans.create');
    Route::post('floor-plans/create', [FloorPlaneController::class, 'store'])->name('floor-plans.store');
    Route::get('floor-plans/{id}/edit', [FloorPlaneController::class, 'edit'])->name('floor-plans.edit');
    Route::put('floor-plans/{id}/edit', [FloorPlaneController::class, 'update'])->name('floor-plans.update');
    Route::get('floor-plans/delete', [FloorPlaneController::class, 'destroy'])->name('floor-plans.delete');

    // Booking Sources Routes
    Route::get('booking-sources', [BookingSourceController::class, 'index'])->name('booking-sources.index');
    Route::get('booking-sources/create', [BookingSourceController::class, 'create'])->name('booking-sources.create');
    Route::post('booking-sources/create', [BookingSourceController::class, 'store'])->name('booking-sources.store');
    Route::get('booking-sources/{id}/edit', [BookingSourceController::class, 'edit'])->name('booking-sources.edit');
    Route::put('booking-sources/{id}/edit', [BookingSourceController::class, 'update'])->name('booking-sources.update');
    Route::get('booking-sources/delete', [BookingSourceController::class, 'destroy'])->name('booking-sources.delete');

    // Room Routes
    Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('rooms/create', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('rooms/{id}/edit', [RoomController::class, 'update'])->name('rooms.update');
    Route::get('rooms/delete', [RoomController::class, 'destroy'])->name('rooms.delete');
    Route::get('rooms/available', [RoomController::class, 'available'])->name('rooms.available');

    // Room Assign Routes
    Route::get('room-assigns/{id}', [RoomAssignController::class, 'index'])->name('room-assigns.index');
    Route::post('room-assigns', [RoomAssignController::class, 'assign'])->name('room-assigns.assign');

    // Transaction Routes
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions/create', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::get('transactions/delete', [TransactionController::class, 'destroy'])->name('transactions.delete');
    Route::get('transactions/{id}/show', [TransactionController::class, 'show'])->name('transactions.show');

    // Supplier Routes
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers/create', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::delete('suppliers/{id}/delete', [SupplierController::class, 'destroy'])->name('suppliers.delete');  

    // Category Routes
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/create', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/edit', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('categories/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
    Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

    // Booking Routes
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings/create', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/edit/{id}', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('bookings/edit/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::get('bookings/checkin/{id}', [BookingController::class, 'checkin'])->name('bookings.checkin');
    Route::put('bookings/checkin/{id}', [BookingController::class, 'updateCK'])->name('bookings.updateCK');
    Route::get('bookings/show/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('bookings/print/{id}', [BookingController::class, 'printInvoice'])->name('bookings.print');
    Route::post('bookings/get-rooms', [BookingController::class, 'getRooms'])->name('bookings.getRooms');
    Route::post('bookings/get-room-details', [BookingController::class, 'getRoomDetail'])->name('bookings.getRoomDetail');
    Route::post('bookings/get-complementry', [BookingController::class, 'getComplementry'])->name('bookings.getComplementry');
    Route::get('room-change/{id}', [BookingController::class, 'viewExchange'])->name('bookings.viewExchange');
    Route::put('room-change', [BookingController::class, 'exchange'])->name('bookings.exchange');
    Route::get('bookings/delete', [BookingController::class, 'destroy'])->name('bookings.delete');

    // Expense Routes
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses/create', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/edit/{id}', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::post('expenses/get-sub-category', [ExpenseController::class, 'getSubCategories'])->name('expenses.getSubCategories');
    Route::get('expenses/print/{id}', [ExpenseController::class, 'printInvoice'])->name('expenses.print');
    Route::get('/expense/summary-data', [ExpenseController::class, 'getSummaryData'])->name('expense.summary');

    // Check-in Routes
    Route::get('check-in', [CheckinController::class, 'index'])->name('checkin.index');
    Route::get('check-in/create', [CheckinController::class, 'create'])->name('checkin.create');
    Route::post('check-in/create', [CheckinController::class, 'store'])->name('checkin.store');
    Route::get('check-in/edit/{id}', [CheckinController::class, 'edit'])->name('checkin.edit');
    Route::put('check-in/edit/{id}', [CheckinController::class, 'update'])->name('checkin.update');
    Route::get('check-in/show/{id}', [CheckinController::class, 'show'])->name('checkin.show');
    Route::get('check-in/print/{id}', [CheckinController::class, 'printInvoice'])->name('checkin.print');
    Route::get('check-in/expense/{id}', [CheckinController::class, 'expense'])->name('checkin.expense');
    Route::post('check-in/expense/{id}', [CheckinController::class, 'postExpense'])->name('checkin.postExpense');
    Route::get('check-in/delete', [CheckinController::class, 'destroy'])->name('checkin.delete');

    // Guest & User Management
    Route::get('guest', [GuestController::class, 'index'])->name('guest.index');
    Route::get('guest/create', [GuestController::class, 'create'])->name('guest.create');
    Route::post('guest/create', [GuestController::class, 'store'])->name('guest.store');

    Route::post('/register/check-username', [ManageUserController::class, 'postCheckUsername'])->name('register.checkUsername');
    Route::resource('users', ManageUserController::class);

    // Account & Business Location
    Route::get('account/opening-balance', [AccountController::class, 'index'])->name('account.openingBalance');
    Route::post('account/opening-balance', [AccountController::class, 'store'])->name('account.store');
    Route::get('account/cash-flow', [AccountController::class, 'cashFlow'])->name('account.cashFlow');

    Route::get('business-locations', [LocationController::class, 'index'])->name('business-locations.index');
    Route::get('business-locations/create', [LocationController::class, 'create'])->name('business-locations.create');
    Route::post('business-locations/create', [LocationController::class, 'store'])->name('business-locations.store');
    Route::get('business-locations/{id}/edit', [LocationController::class, 'edit'])->name('business-locations.edit');
    Route::put('business-locations/edit', [LocationController::class, 'update'])->name('business-locations.update');
    Route::get('business-locations/delete', [LocationController::class, 'destroy'])->name('business-locations.delete');
    Route::get('business-locations/show/{id}', [LocationController::class, 'show'])->name('business-locations.show');
    Route::get('business-locations/get-department', [LocationController::class, 'getDeparment'])->name('get.department');

    // Home & Calendar
    Route::get('get-calender', [HomeController::class, 'getCalander'])->name('home.calender');
    Route::get('home/expense/{id}', [HomeController::class, 'expense'])->name('home.expense');
    Route::post('home/expense', [HomeController::class, 'postExpense'])->name('home.postExpense');
    Route::get('payment-due', [HomeController::class, 'getPaymentDues'])->name('home.paymentDue');
    Route::get('today-checkouts', [HomeController::class, 'todayCheckouts'])->name('home.todayCheckouts');

    // Checkout Routes
    Route::get('check-out/list', [CheckoutController::class, 'list'])->name('checkout.list');
    Route::get('check-out', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('check-out', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('check-out/show/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::get('check-out/print/{id}', [CheckoutController::class, 'printInvoice'])->name('checkout.print');
    Route::post('get_payment_row', [CheckoutController::class, 'getPaymentRow'])->name('checkout.getPaymentRow');

    // Business Settings
    Route::get('business-setting', [BussinessController::class, 'index'])->name('business.setting');
    Route::post('business-setting', [BussinessController::class, 'store'])->name('business.setting.store');

    // Room Types
    Route::get('types', [RoomTypeController::class, 'index'])->name('type.index');
    Route::get('types/create', [RoomTypeController::class, 'create'])->name('types.create');
    Route::post('types/create', [RoomTypeController::class, 'store'])->name('type.store');
    Route::get('types/{id}/edit', [RoomTypeController::class, 'edit'])->name('types.edit');
    Route::put('types/{id}/edit', [RoomTypeController::class, 'update'])->name('type.update');
    Route::get('types/delete', [RoomTypeController::class, 'destroy'])->name('type.delete');

    // Dashboard Quick Links
    Route::get('booking', [DashboardController::class, 'getChk'])->name('dashboard.booking');
    Route::get('checkin', [DashboardController::class, 'quickChk'])->name('dashboard.checkin');
    Route::get('checkout', [DashboardController::class, 'quickOut'])->name('dashboard.checkout');
    Route::get('booking/edit/{id}', [DashboardController::class, 'editBook'])->name('dashboard.editBook');

    // Cancellations
    Route::get('cancellation/{id}', [CancellationController::class, 'index'])->name('cancellation.index');
    Route::post('cancellation', [CancellationController::class, 'refund'])->name('cancellation.refund');
    Route::post('block', [CancellationController::class, 'block'])->name('cancellation.block');

    // Room Status
    Route::get('room-status', [RoomStatusController::class, 'index'])->name('room-status.index');

    // Reports
    Route::get('reports/booking', [ReportController::class, 'bookingReport'])->name('reports.booking');
    Route::get('reports/stock-report', [StockReportController::class, 'report'])->name('reports.stock');
    Route::get('reports/sale-report', [SaleReportController::class, 'index'])->name('reports.sale');
    Route::get('sale-filter', [SaleReportController::class, 'filter'])->name('reports.sale.filter');
    Route::get('reports/sale-details', [SaleController::class, 'getSalesTotalSell'])->name('reports.sale.details');
    Route::get('get-purchase-summary', [RegisterController::class, 'getTotalPurchase'])->name('reports.purchase.summary');
    Route::get('reports/sale-detail-report', [SaleReportController::class, 'detailReport'])->name('reports.sale.detailReport');
    Route::get('reports/register-report', [RegisterController::class, 'index'])->name('reports.register');
    Route::get('reports/sale-payment-report', [SaleReportController::class, 'paymentDetailReport'])->name('reports.sale.payment');
    Route::get('reports/purchase-report', [RegisterController::class, 'purchaseReport'])->name('reports.purchase');
    Route::get('reports/location-balance-report', [RegisterController::class, 'locationReport'])->name('reports.location.balance');
    Route::get('reports/sale-cancel-report', [SaleReportController::class, 'saleCancelReport'])->name('reports.sale.cancel');
    Route::get('reports/sale-profit-report', [ProfitReportController::class, 'profitReport'])->name('reports.sale.profit');

    // POS Routes
    Route::prefix('pos')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('pos.index');
        Route::post('/draft', [POSController::class, 'draft'])->name('pos.draft');
        Route::post('/order', [POSController::class, 'order'])->name('pos.order');
        Route::post('/add-payments', [POSController::class, 'addPaidorder'])->name('pos.addPayments');
    });

    Route::get('/get-draft-order', [POSController::class, 'getDraft'])->name('pos.getDraft');

    Route::post('pos/sell-item/delete', [OrderController::class, 'delete'])->name('pos.sellItem.delete');
    Route::post('pos/cancel', [OrderController::class, 'cancel'])->name('pos.cancel');
    Route::post('pos/order-cancel', [OrderController::class, 'orderCancel'])->name('pos.orderCancel');
    Route::get('invoice/cancel/{id}', [OrderController::class, 'cancelInvoice'])->name('invoice.cancel');

    // Invoices Routes
    Route::prefix('invoices')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('invoices.index');
        Route::post('cancellation', [SaleController::class, 'cancel'])->name('invoices.cancel');
        Route::get('/show/{id}', [SaleController::class, 'show'])->name('invoices.show');
        Route::get('/print/{id}', [SaleController::class, 'printInvoice'])->name('invoices.print');
        Route::get('invoice-sale-details', [SaleController::class, 'getInvoiceSalesTotal'])->name('invoice.sale.details');
    });

    Route::get('/orders', [SaleController::class, 'order'])->name('orders.index');

    // KOT Routes (Kitchen Orders)
    Route::prefix('kot')->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('kot.index');
        Route::post('/', [KitchenController::class, 'update'])->name('kot.update');
    });

    // BOT Routes (Bar Orders)
    Route::prefix('bot')->group(function () {
        Route::get('/', [BOTController::class, 'index'])->name('bot.index');
    });

    // Stations (Dynamic ticket places: KOT, BOT, DOT, COT, ...)
    Route::prefix('stations')->group(function () {
        Route::get('/', [StationController::class, 'index'])->name('stations.index');
        Route::post('/', [StationController::class, 'store'])->name('stations.store');
        Route::put('/{id}', [StationController::class, 'update'])->name('stations.update');
        Route::delete('/{id}', [StationController::class, 'destroy'])->name('stations.destroy');
    });

    // Station display screens (per-station live order screen)
    Route::prefix('station-display')->group(function () {
        Route::get('/{slug}', [StationDisplayController::class, 'index'])->name('station-display.index');
        Route::post('/lines/{lineId}/status', [StationDisplayController::class, 'updateLineStatus'])
            ->name('station-display.line.status');
    });

    // Station tickets (reprint / details)
    Route::prefix('station-tickets')->group(function () {
        Route::get('/{id}', [StationTicketController::class, 'show'])->name('station-tickets.show');
        Route::post('/{id}/reprint', [StationTicketController::class, 'reprint'])->name('station-tickets.reprint');
    });

    // Printers
    Route::prefix('printers')->group(function () {
        Route::get('/', [PrinterController::class, 'index'])->name('printers.index');
        Route::post('/create', [PrinterController::class, 'store'])->name('printers.store');
        Route::get('/delete', [PrinterController::class, 'destroy'])->name('printers.delete');
    });

    // Purchases
    Route::prefix('purchases')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/create', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/edit/{id}', [PurchaseController::class, 'edit'])->name('purchases.edit');
        Route::put('/edit/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
        Route::get('/delete', [PurchaseController::class, 'destroy'])->name('purchases.delete');
        Route::get('/show/{id}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::get('/get_products', [PurchaseController::class, 'getProducts'])->name('purchases.getProducts');
        Route::post('/get_purchase_entry_row', [PurchaseController::class, 'getPurchaseEntryRow'])->name('purchases.getEntryRow');
        Route::post('/check_ref_number', [PurchaseController::class, 'checkRefNumber'])->name('purchases.checkRefNumber');
        Route::get('/print/{id}', [PurchaseController::class, 'printInvoice'])->name('purchases.print');
    });

    // Restaurant
    Route::group(['prefix' => 'rest'], function () {

        // Category
        Route::get('categeory', [ProductCategoryController::class, 'index'])->name('categeory.index');
        Route::post('categeory/store', [ProductCategoryController::class, 'store'])->name('categeory.store');
        Route::post('categeory/update', [ProductCategoryController::class, 'update'])->name('categeory.update');
        Route::get('categeory/delete', [ProductCategoryController::class, 'delete'])->name('categeory.delete');
        Route::get('categeory/{id}', [ProductCategoryController::class, 'show'])->name('categeory.show');

        // Type
        Route::get('types', [TypeController::class, 'index'])->name('types.index');
        Route::post('types/store', [TypeController::class, 'store'])->name('types.store');
        Route::post('types/update', [TypeController::class, 'update'])->name('types.update');
        Route::get('types/delete', [TypeController::class, 'delete'])->name('types.delete');
        Route::get('types/show/{id}', [TypeController::class, 'show'])->name('types.show');

        // Cousine
        Route::get('cousine', [CousineController::class, 'index'])->name('cousine.index');
        Route::post('cousine/store', [CousineController::class, 'store'])->name('cousine.store');
        Route::post('cousine/update', [CousineController::class, 'update'])->name('cousine.update');
        Route::get('cousine/delete', [CousineController::class, 'delete'])->name('cousine.delete');
        Route::get('cousine/show/{id}', [CousineController::class, 'show'])->name('cousine.show');

        // Menu
        Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
        Route::post('menu/store', [MenuController::class, 'store'])->name('menu.store');
        Route::post('menu/update', [MenuController::class, 'update'])->name('menu.update');
        Route::get('menu/delete', [MenuController::class, 'delete'])->name('menu.delete');
        Route::get('menu/{id}', [MenuController::class, 'show'])->name('menu.show');

        // Drink Type
        Route::get('drinttype', [DrintTypeController::class, 'index'])->name('drinttype.index');
        Route::post('drinttype/store', [DrintTypeController::class, 'store'])->name('drinttype.store');
        Route::post('drinttype/update', [DrintTypeController::class, 'update'])->name('drinttype.update');
        Route::get('drinttype/delete', [DrintTypeController::class, 'delete'])->name('drinttype.delete');
        Route::get('drinttype/{id}', [DrintTypeController::class, 'show'])->name('drinttype.show');

        // Unit
        Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
        Route::post('unit/store', [UnitController::class, 'store'])->name('unit.store');
        Route::post('unit/update', [UnitController::class, 'update'])->name('unit.update');
        Route::get('unit/delete', [UnitController::class, 'delete'])->name('unit.delete');

        // Department
        Route::get('department', [DepartmentController::class, 'index'])->name('department.index');
        Route::post('department/store', [DepartmentController::class, 'store'])->name('department.store');
        Route::post('department/update', [DepartmentController::class, 'update'])->name('department.update');
        Route::get('department/show/{id}', [DepartmentController::class, 'show'])->name('department.show');
        Route::get('department/delete', [DepartmentController::class, 'delete'])->name('department.delete');
        Route::get('department/get-product', [DepartmentController::class, 'getProducts'])->name('department.product');
        Route::get('department/employees', [DepartmentController::class, 'getEmployees'])->name('department.employee');

        // Order Type
        Route::get('order_type', [OrderTypeController::class, 'index'])->name('order_type.index');
        Route::post('order_type/store', [OrderTypeController::class, 'store'])->name('order_type.store');
        Route::post('order_type/update', [OrderTypeController::class, 'update'])->name('order_type.update');
        Route::get('order_type/delete', [OrderTypeController::class, 'delete'])->name('order_type.delete');

        // Table Location
        Route::get('table_location', [TableLocationController::class, 'index'])->name('table_location.index');
        Route::post('table_location/store', [TableLocationController::class, 'store'])->name('table_location.store');
        Route::post('table_location/update', [TableLocationController::class, 'update'])->name('table_location.update');
        Route::get('table_location/delete', [TableLocationController::class, 'delete'])->name('table_location.delete');

        // Table
        Route::get('table', [TableController::class, 'index'])->name('table.index');
        Route::post('table-store', [TableController::class, 'store'])->name('table.store');
        Route::post('table/update', [TableController::class, 'update'])->name('table.update');
        Route::get('table/delete', [TableController::class, 'delete'])->name('table.delete');
        Route::get('table/{id}', [TableController::class, 'show'])->name('table.show');
        Route::get('department/table', [TableController::class, 'getTable'])->name('table.department');

        // Payment Method
        Route::get('payment_method', [PaymentMethodController::class, 'index'])->name('payment_method.index');
        Route::post('payment_method/store', [PaymentMethodController::class, 'store'])->name('payment_method.store');
        Route::post('payment_method/update', [PaymentMethodController::class, 'update'])->name('payment_method.update');
        Route::get('payment_method/delete', [PaymentMethodController::class, 'delete'])->name('payment_method.delete');

        // Contact Type
        Route::get('contact_type', [ContactTypeController::class, 'index'])->name('contact_type.index');
        Route::post('contact_type-store', [ContactTypeController::class, 'store'])->name('contact_type.store');
        Route::post('contact_type-update', [ContactTypeController::class, 'update'])->name('contact_type.update');
        Route::get('contact_type-delete', [ContactTypeController::class, 'delete'])->name('contact_type.delete');

        // Customer Type
        Route::get('customer_type', [CustomerTypeController::class, 'index'])->name('customer_type.index');
        Route::post('customer_type/store', [CustomerTypeController::class, 'store'])->name('customer_type.store');
        Route::post('customer_type/update', [CustomerTypeController::class, 'update'])->name('customer_type.update');
        Route::get('customer_type/delete', [CustomerTypeController::class, 'delete'])->name('customer_type.delete');
        Route::get('customer_type/{id}', [CustomerTypeController::class, 'show'])->name('customer_type.show');

        // Customer Group
        Route::get('customer_group', [CustomerGroupController::class, 'index'])->name('customer_group.index');
        Route::post('customer_group-store', [CustomerGroupController::class, 'store'])->name('customer_group.store');
        Route::post('customer_group-update', [CustomerGroupController::class, 'update'])->name('customer_group.update');
        Route::get('customer_group/delete', [CustomerGroupController::class, 'delete'])->name('customer_group.delete');
        Route::get('customer_group/{id}', [CustomerGroupController::class, 'show'])->name('customer_group.show');

        // Event Type
        Route::get('event_type', [EventTypeController::class, 'index'])->name('event_type.index');
        Route::post('event_type-store', [EventTypeController::class, 'store'])->name('event_type.store');
        Route::post('event_type-update', [EventTypeController::class, 'update'])->name('event_type.update');
        Route::delete('event_type-delete/{id}', [EventTypeController::class, 'delete'])->name('event_type.delete');

        // Contact Event
        Route::get('contact_event', [ContactEventController::class, 'index'])->name('contact_event.index');
        Route::post('contact_event-store', [ContactEventController::class, 'store'])->name('contact_event.store');
        Route::post('contact_event-update', [ContactEventController::class, 'update'])->name('contact_event.update');
        Route::delete('contact_event-delete/{id}', [ContactEventController::class, 'delete'])->name('contact_event.delete');

        // Brand
        Route::get('brand', [BrandController::class, 'index'])->name('brand.index');
        Route::post('brand/store', [BrandController::class, 'store'])->name('brand.store');
        Route::post('brand/update', [BrandController::class, 'update'])->name('brand.update');
        Route::get('brand/delete', [BrandController::class, 'delete'])->name('brand.delete');
        Route::get('brand/{id}', [BrandController::class, 'show'])->name('brand.show');

        // Table Booking
        Route::get('table_booking', [TableBookingController::class, 'index'])->name('table_booking.index');
        Route::post('table_booking/store', [TableBookingController::class, 'store'])->name('table_booking.store');
        Route::post('table_booking/update', [TableBookingController::class, 'update'])->name('table_booking.update');
        Route::get('table_booking/delete', [TableBookingController::class, 'delete'])->name('table_booking.delete');
        Route::post('table_booking/filter', [TableBookingController::class, 'filter'])->name('table_booking.filter');
        Route::get('table_booking/{id}', [TableBookingController::class, 'show'])->name('table_booking.show');

        // EMDestination
        Route::get('destination', [EMDestinationController::class, 'index'])->name('destination.index');
        Route::post('destination-store', [EMDestinationController::class, 'store'])->name('destination.store');
        Route::post('destination-update', [EMDestinationController::class, 'update'])->name('destination.update');
        Route::get('destination/show/{id}', [EMDestinationController::class, 'show'])->name('destination.show');
        Route::get('destination/delete', [EMDestinationController::class, 'delete'])->name('destination.delete');

        // Contact
        Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
        Route::get('contact/index_s', [ContactController::class, 'index_s'])->name('contact.index_s');
        Route::get('contact/customer/create', [ContactController::class, 'create'])->name('contact.customer.create');
        Route::get('contact/customer/edit/{id}', [ContactController::class, 'edit'])->name('contact.customer.edit');
        Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');
        Route::get('contact/supplier/create', [ContactController::class, 'suCreate'])->name('contact.supplier.create');
        Route::get('contact/supplier/edit/{id}', [ContactController::class, 'suEdit'])->name('contact.supplier.edit');
        Route::post('contact/store_s', [ContactController::class, 'store_s'])->name('contact.store_s');
        Route::post('contact/update', [ContactController::class, 'store'])->name('contact.update');
        Route::post('contact/update_s', [ContactController::class, 'store_s'])->name('contact.update_s');
        Route::get('contact/delete', [ContactController::class, 'delete'])->name('contact.delete');
        Route::get('contact/show/{id}', [ContactController::class, 'show'])->name('contact.show');

        // Contact Document
        Route::get('contact_document', [ContactDocumentController::class, 'index'])->name('contact_document.index');
        Route::post('contact_document-store', [ContactDocumentController::class, 'store'])->name('contact_document.store');
        Route::delete('contact_document-delete/{id}', [ContactDocumentController::class, 'delete'])->name('contact_document.delete');


        // product
        Route::group(['prefix' => 'product'], function () {
            Route::get('', 'Rest\ProductController@index')->name('product.index');
            Route::get('/create', 'Rest\ProductController@create')->name('product.create');
            Route::post('/store', 'Rest\ProductController@store')->name('product.store');
            Route::get('/edit/{id}', 'Rest\ProductController@edit')->name('product.edit');
            Route::post('/edit/{id}', 'Rest\ProductController@update')->name('product.update');
            Route::get('/delete', 'Rest\ProductController@delete')->name('product.delete');
            Route::post('/sub-category', 'Rest\ProductController@subCategories');
            Route::get('/show/{id}', 'Rest\ProductController@show');
        });

        // Product
        Route::prefix('product')->group(function () {
            Route::get('', [ProductController::class, 'index'])->name('product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/store', [ProductController::class, 'store'])->name('product.store');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::post('/edit/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/delete', [ProductController::class, 'delete'])->name('product.delete');
            Route::post('/sub-category', [ProductController::class, 'subCategories']);
            Route::get('/show/{id}', [ProductController::class, 'show']);
        });

        // Product Variation
        Route::get('product_variation', [ProductVaritationController::class, 'index'])->name('product_variation.index');
        Route::post('product_variation/store', [ProductVaritationController::class, 'store'])->name('product_variation.store');
        Route::post('product_variation/update', [ProductVaritationController::class, 'update'])->name('product_variation.update');
        Route::get('product_variation/delete', [ProductVaritationController::class, 'delete'])->name('product_variation.delete');
        Route::get('product_variation/show/{id}', [ProductVaritationController::class, 'show'])->name('product_variation.show');

        // Employee Type
        Route::get('employee_type', [EmployeeTypeController::class, 'index'])->name('employee_type.index');
        Route::post('employee_type/store', [EmployeeTypeController::class, 'store'])->name('employee_type.store');
        Route::post('employee_type/update', [EmployeeTypeController::class, 'update'])->name('employee_type.update');
        Route::get('employee_type/show/{id}', [EmployeeTypeController::class, 'show'])->name('employee_type.show');
        Route::get('employee_type/delete', [EmployeeTypeController::class, 'delete'])->name('employee_type.delete');

        // Attendance Type
        Route::get('attendance', [AttendanceTypeController::class, 'index'])->name('attendance.index');
        Route::post('attendance/store', [AttendanceTypeController::class, 'store'])->name('attendance.store');
        Route::post('attendance/update', [AttendanceTypeController::class, 'update'])->name('attendance.update');
        Route::get('attendance/show/{id}', [AttendanceTypeController::class, 'show'])->name('attendance.show');
        Route::get('attendance/delete', [AttendanceTypeController::class, 'delete'])->name('attendance.delete');

        // Position
        Route::get('position', [PositionController::class, 'index'])->name('position.index');
        Route::post('position/store', [PositionController::class, 'store'])->name('position.store');
        Route::post('position/update', [PositionController::class, 'update'])->name('position.update');
        Route::get('position/show/{id}', [PositionController::class, 'show'])->name('position.show');
        Route::get('position/delete', [PositionController::class, 'delete'])->name('position.delete');

        // Employee
        Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');
        Route::get('employee/create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::post('employee/store', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('employee/update/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::post('employee/update', [EmployeeController::class, 'update'])->name('employee.update');
        Route::get('employee/show/{id}', [EmployeeController::class, 'show'])->name('employee.show');
        Route::get('employee/view/{id}', [EmployeeController::class, 'view'])->name('employee.view');
        Route::get('employee/delete', [EmployeeController::class, 'delete'])->name('employee.delete');

        // Tax
        Route::get('tax', [TaxController::class, 'index'])->name('tax.index');
        Route::post('tax/store', [TaxController::class, 'store'])->name('tax.store');
        Route::post('tax/update', [TaxController::class, 'update'])->name('tax.update');
        Route::get('tax/delete', [TaxController::class, 'delete'])->name('tax.delete');
        Route::get('tax/{id}', [TaxController::class, 'show'])->name('tax.show');
    });

    Route::resource('purchase-returns', PurchaseReturnController::class);
    Route::get('returns/get_purchase', [PurchaseReturnController::class, 'getPurchase']);
    Route::post('purchase-return/get_entry_row', [PurchaseReturnController::class, 'getPurchaseEntryRow']);
    Route::post('get-transaction-data', [PurchaseReturnController::class, 'transactionData']);
    Route::post('return-store', [PurchaseReturnController::class, 'store']);
    Route::get('return-cancel/{id}', [PurchaseReturnController::class, 'cancel']);

    Route::resource('purchase-wastage', PurchaseWastageController::class);
    Route::post('get-wastahe/purchase-row', [PurchaseWastageController::class, 'getEntryRow']);

    Route::post('/cash-register', [CashRegisterController::class, 'store']);
    Route::get('/cash-register', [CashRegisterController::class, 'getRegisterDetails']);
    Route::post('/cash-register-closing', [CashRegisterController::class, 'postCloseRegister']);

    Route::get('/open-stock/{id}', [OpenStockController::class, 'create']);
    Route::post('/open-stock', [OpenStockController::class, 'store']);

    Route::get('/add-payment/{id}', [TransactionPaymentController::class, 'create']);
    Route::post('/add-payment', [TransactionPaymentController::class, 'store']);
    Route::get('/view-payment/{id}', [TransactionPaymentController::class, 'show']);

    Route::get('/change-password', [ChangePasswordController::class, 'index']);
    Route::post('/change-password', [ChangePasswordController::class, 'update']);
});
