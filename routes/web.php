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

Auth::routes();

Route::group(['prefix' => 'view', 'middleware' => 'auth'], function(){
	//全局视图
	Route::get('overall_trades', "OverallController@overallTrades");
	//系统管理
	Route::get('menus', "MenusController@view");
	Route::get('repositories', "RepositoriesController@view");
	Route::get('uris', "UrisController@view");
	Route::get('districts', function(){return view('system.districts');});
	Route::get('accept_channel', "AcceptChannelsController@view");
	Route::get('payment_channel', "PaymentChannelsController@view");
	Route::get('client', "ClientsController@view");
	Route::get('charge_staffs', "ChargeStaffsController@view");
	Route::get('sys_param', "SysParamsController@view");
	//终端管理
	Route::get('terminal', "TerminalsController@view");
	//交易管理
	Route::get('trades_detail', "OrdersController@view");
	Route::get('trades_operation', "OrdersController@view");
	Route::get('count_by_district', "OrdersController@countByDistrictView");
	Route::get('count_by_accept', "OrdersController@countByAcceptView");
	Route::get('count_by_payment', "OrderSerialsController@countByPaymentView");
	//结算管理
	Route::get('clearing_detail', 'ClearingsController@detailView');
	Route::get('clearing_all', 'ClearRelatesController@allView');
	//统计报表
	Route::get('trade_statement', 'StatementsController@tradeView');
	Route::get('account_statement', 'StatementsController@accountView');
	Route::get('clearing_statement', 'StatementsController@clearingView');
	
});

Route::get('/', function () {
    return redirect()->route('manage');
});

Route::get('logout', 'Auth\LoginController@logout');

Route::get('/home', function () {
    return redirect()->route('manage');
});

Route::get('manage', 'Index\IndexController@index')->name('manage');

Route::group(['prefix' => 'index'], function(){
		
		Route::get('welcome', 'Index\IndexController@welcome');
		Route::get('wait', 'Index\IndexController@wait');
	});

Route::group(['prefix' => 'overall'], function () {
	
	Route::group(['prefix' => 'overview_hitches'], function(){
		Route::get('/', function(){return view('overall.overview_hitches');});
	});
	Route::group(['prefix' => 'overview_events'], function(){
		Route::get('/', function(){return view('overall.overview_events');});
	});
	Route::group(['prefix' => 'overview_events'], function(){
		Route::get('/', function(){return view('overall.overview_events');});
	});
});

Route::group(['prefix' => 'account'], function () {
	
	Route::group(['prefix' => 'account_detail'], function(){
		Route::get('/', function(){return view('account.account_detail');});
	});
	Route::group(['prefix' => 'account_operation'], function(){
		Route::get('/', function(){return view('account.account_operation');});
	});
	Route::group(['prefix' => 'overview_events'], function(){
		Route::get('/', function(){return view('global.overview_events');});
	});
	Route::group(['prefix' => 'overview_events'], function(){
		Route::get('/', function(){return view('global.overview_events');});
	});
});

Route::group(['prefix' => 'clearing', 'middleware' => 'auth'], function () {
	Route::get('detail_index', 'ClearingsController@detailIndex');

	Route::get('clearing_relate_index', 'ClearRelatesController@index');

	Route::get('clearing_all_index', 'ClearRelatesController@allIndex');
});

Route::group(['prefix' => 'terminal', 'middleware' => 'auth'], function () {
	
	Route::resource("index", "TerminalsController");
	
});

Route::group(['prefix' => 'procedure'], function () {
	
	Route::group(['prefix' => 'procedure_search'], function(){
		Route::get('/', function(){return view('procedure.procedure_search');});
	});
	Route::group(['prefix' => 'blanace_operation'], function(){
		Route::get('/', function(){return view('blanace.blanace_operation');});
	});
	Route::group(['prefix' => 'overview_events'], function(){
		Route::get('/', function(){return view('global.overview_events');});
	});
	Route::group(['prefix' => 'overview_events'], function(){
		Route::get('/', function(){return view('global.overview_events');});
	});
});
	
Route::group(['prefix' => 'trades', 'middleware' => 'auth'], function () {
	
	Route::get('detail_index', 'OrdersController@index');

	Route::get('operation_index', 'OrdersController@index');
	
	Route::get('serial_index', 'OrderSerialsController@index');
	
	Route::get('count_orders_by_condition', 'OrdersController@countOrdersByCondition');

	Route::get('count_orderserial_by_condition', 'OrderSerialsController@countOrderserialByCondition');

	Route::get('overall_total', 'OrdersController@overallTotal');

	Route::get('sort_rate_by_accept', 'OrdersController@sortRateByCondition');

	Route::get('sort_rate_by_district', 'OrdersController@sortRateByCondition');

	Route::get('sort_rate_by_payment', 'OrderSerialsController@sortRateByCondition');
});

Route::group(['prefix' => 'statement', 'middleware' => 'auth'], function () {
	Route::get('trade_statement', 'StatementsController@tradeStatement');

	Route::get('account_statement', 'StatementsController@accountStatement');

	Route::get('clearing_statement', 'StatementsController@clearingStatement');
});

Route::group(['prefix' => 'system', 'middleware' => 'auth'], function () {
	
	Route::resource('menus', 'MenusController');

	Route::resource('repositories', 'RepositoriesController');

	Route::resource('uris', 'UrisController');

	Route::group(['prefix' => 'system_account'], function(){
		Route::get('/', function(){return view('system.system_account');});
	});
	
	Route::group(['prefix' => 'system_role'], function(){
		Route::get('/', function(){return view('system.system_role');});
	});
	
	Route::resource('accept_channel', 'AcceptChannelsController');
	
	Route::resource('payment_channel', 'PaymentChannelsController');
	
	Route::resource('districts', 'DistrictsController');
	
	Route::resource('client', 'ClientsController');

	Route::resource('charge_staffs', 'ChargeStaffsController');

	Route::resource('sys_param', 'SysParamsController');
});

