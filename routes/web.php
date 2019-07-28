<?php

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

use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

//PR Routes
// Customer
/*Route::get('/customer/new',function (){
    return view('customer.addCustomer');
});*/

Route::get('',function (){
    return view('customer.index');
})->middleware('auth');

Route::get('/customer/vue/all',function (){
    return view('customer.all');
});

Route::get('/work-order/vue/all', function (){
   return view('work-order.all');
});


Route::get('/work-order/vue/add', function (){
    return view('work-order.add');
});

Route::post('/customer/add','CustomerController@addStore')->name('add_customer');
Route::get('/customer-show/{id}','CustomerController@show')->name('get_customer');
Route::post('/customer-edit/{customer}','CustomerController@editStore')->name('edit_customer');
Route::get('/customer/delete/{customer}','CustomerController@delete')->name('delete_customer');
Route::post('/customer-details','CustomerController@viewAllWithDetails')->name('view_all_customer_details');
Route::get('/customer/all','CustomerController@all')->name('view_all_customer');
Route::get('/customer-overview/{id}','CustomerController@getCustomerOverview')->name('view_customer_overview');


// Location
Route::post('/customer/location/add/{customer}','LocationController@addStore')->name('add_location');
Route::post('/customer/location/edit/{location}','LocationController@editStore')->name('edit_location');
Route::post('/location/all','LocationController@viewAll')->name('view_all_location');
Route::get('/customer-location/{id}','LocationController@viewCustomerLocation')->name('view_customer_location');
Route::get('/location/all','LocationController@viewAllLocation')->name('view_all_locations');
Route::get('/location/{location}','LocationController@viewLocation')->name('view_location');
Route::get('/customer-locations/all/{id}','LocationController@getLocationForCustomer')->name('view_all_customer_locations');

// Contact
Route::get('/contact/{contact}','ContactController@viewContact')->name('view_contact');
Route::post('/customer/contact/add/{customer}','ContactController@addStore')->name('add_contact');
Route::post('/customer/contact/edit/{contact}','ContactController@editStore')->name('edit_contact');
Route::get('/contacts/all','ContactController@viewAll')->name('view_all_contacts');
Route::post('/contact-customer/all','ContactController@allForCustomer')->name('view_all_customer_contacts');
//for customer edit
Route::get('/customer-contact/{id}','ContactController@viewCustomerContact')->name('view_customer_contact');
//for customer details->contact tab
Route::get('/customer-contacts/all/{id}','ContactController@viewContactForCustomer')->name('view_all_customer_contacts');

// Attachment
Route::post('/attachment/add/{parent}','AttachmentController@addStore')->name('add_attachment');
Route::post('/attachment/edit/{attachment}','AttachmentController@editStore')->name('edit_attachment');
Route::post('/attachment/delete/{attachment}','AttachmentController@delete')->name('delete_attachment');

//Calendar
Route::get('/calendar',function(){
    return view('calendar.index');

});
Route::get('/events','AppointmentController@showAppointments')->name('show_appointments');
Route::get('/event/{event}','AppointmentController@showEvent')->name('show_event');
Route::get('/today-events/{date}','AppointmentController@showTodaysEvents')->name('show_todays_events');
Route::get('event-delete/{event}','AppointmentController@deleteEvent')->name('delete_events');


// Comment
Route::post('/comment/add/{parent}','CommentController@addStore')->name('add_comment');
Route::post('/comment/edit/{comment}','CommentController@editStore')->name('edit_comment');
Route::post('/comment/delete/{comment}','CommentController@delete')->name('delete_comment');

//user
Route::get('/user/all','UserController@index')->name('all_users');

//work-order
Route::post('/workOrder-assignment/{workOrder}','WorkOrderController@assignTo')->name('assign_work_order');

Route::get('/test',function(){
    //$details = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=New+York+City&destinations=San+Francisco&mode=driving&sensor=false";

$details="https://maps.googleapis.com/maps/api/directions/json?origin=Dhaka,SA&destination=Dhaka,SA&waypoints=optimize:true|Chittagong,SA|Rajshahi,SA|Khulna,SA";
$jsonfile = file_get_contents($details);
   $jsondata = json_decode($jsonfile);
    for($i=0;$i<3;$i++)
    dd($jsondata->routes[0]->waypoint_order);
    return $jsondata->routes[0]->legs[0]->steps;

// key : AIzaSyClrEV41J0erVBTCKxnUfBV_8KqFaAhgxU

});



//JR Routes
Route::post('/equipment/add','EquipmentController@addStore')->name('add_equipment')->middleware('auth');
Route::post('/equipment/edit/{equipment}','EquipmentController@editStore')->name('edit_equipment');
Route::get('/equipment/all','EquipmentController@viewAll')->name('view_all_equipments');
Route::get('/equipment/{equipment}','EquipmentController@viewEquipment')->name('view_equipment');
Route::post('/equipment-customer','EquipmentController@allForCustomer')->name('view_all_customer_equipments');
Route::get('/equipment/delete/{equipment}','EquipmentController@deleteEquipment')->name('delete_equipment');
Route::post('/equipment-type/add','EquipmentController@addType')->name('add_equipment_type')->middleware('auth');
Route::get('/equipment-type/all','EquipmentController@getTypes')->name('view_all_equipment_types');

Route::post('/contract/add','ContractController@addStore')->name('add_contract');
Route::post('/contract/edit/{contract}','ContractController@editStore')->name('edit_contract');
Route::get('/contract/all','ContractController@viewAll')->name('view_all_contracts');
Route::post('/contract-customer','ContractController@allForCustomer')->name('view_all_customer_contracts');
Route::get('/contract/{contract}','ContractController@viewContract')->name('view_contract');
Route::get('/delete-contract/{contract}','ContractController@deleteContract')->name('delete_contract');
Route::post('/contract-type/add','ContractController@addType')->name('add_contract_type')->middleware('auth');
Route::get('/contract-type/all','ContractController@getTypes')->name('view_all_contract_types');
Route::post('/contract/change-status/{contract}','ContractController@changeStatus')->name('change-status-contract');

Route::post('/tax-code/add','TaxCodeController@addStore')->name('add_tax_code');
Route::post('/tax-code/edit/{taxCode}','TaxCodeController@editStore')->name('edit_tax_code');
Route::get('tax-code/all', 'TaxCodeController@all')->name('view_all_tax_code');
Route::post('tax-code', 'TaxCodeController@getTax')->name('view_tax_code');

Route::post('/item/add','ItemController@addStore')->name('add_item');
Route::post('/item/edit/{item}','ItemController@editStore')->name('edit_item');
Route::get('/item/all','ItemController@viewAll')->name('view_all_item');
Route::get('/item/delete/{item}','ItemController@deleteItem')->name('delete_item');

Route::get('item/type/{type}','ItemController@viewTypeAll')->name('view_items_of_a_type');
Route::get('item/{id}','ItemController@viewItem')->name('view_item');
Route::post('/assign-item','ItemController@assignItem')->name('assign_item');

Route::get('/item/{item}','ItemController@viewItem')->name('view_item');


Route::post('/work-order/add','WorkOrderController@addStore')->name('add_work_order');
Route::get('/work-order/{workOrder}','WorkOrdercontroller@viewWorkOrder')->name('view_work_order');
Route::post('/work-order/edit/{workOrder}','WorkOrderController@editStore')->name('edit_work_order');
Route::post('/work-orders/all','WorkOrderController@viewAll')->name('view_all_workorders')->middleware('auth');
Route::get('/work-order/delete/{workOrder}','WorkOrdercontroller@delete')->name('delete_work_order');

Route::post('/appointment/add','AppointmentController@addStore')->name('add_appointment');
Route::post('/appointment/edit/{appointment}','AppointmentController@editStore')->name('edit_appointment');

Route::post('/work-order/item-add/{workOrder}','WorkOrderController@addItemStore')->name('add_item_work_order');
Route::post('/work-order/item-edit/{workOrder}','WorkOrderController@editItemStore')->name('edit_item_work_order');

Route::post('/work-order/change-status/{workOrder}','WorkOrderController@changeStatus')->name('change-status-workorder');

//Recurring WorkOrder
Route::post('/recurring-jobs/add','RecurringWorkOrderController@addStore')->name('add_recurring_jobs');
Route::post('/recurring-job/edit/{recurringWorkOrder}','RecurringWorkOrderController@editStore')->name('edit_recurring_jobs');
Route::get('/recurring-jobs/all','RecurringWorkOrderController@viewAll')->name('view_all_recurring_jobs')->middleware('auth');
Route::post('/recurring-details','RecurringWorkOrderController@viewAllwithDetails')->name('view_recurring_with_details');
Route::get('/recurring/{recurring}','RecurringWorkOrderController@viewRecurring')->name('view_recurring');
Route::get('/recurring-job/delete/{recurringWorkOrder}','RecurringWorkOrderController@delete')->name('delete_recurring_jobs');

Route::post('/recurring-job/change-status/{recurringWorkOrder}','RecurringWorkOrderController@changeStatus')->name('change-status-recurring');

Route::post('/user/add','UserController@create')->name('add_user');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
