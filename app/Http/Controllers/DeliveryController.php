<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Contact;
use App\Delivery;
use App\DeliveryPerson;
use App\Notifications\DeliveryAssignedNotification;
use App\Notifications\DeliveryNotification;
use App\Transaction;
use App\User;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeliveryController extends Controller
{
    protected $moduleUtil;
    protected $transactionUtil;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, Delivery $delivery)
    {

        $this->transactionUtil = $transactionUtil;
        $this->delivery = $delivery;
        $this->moduleUtil = $moduleUtil;
        $this->assign_status_colors = [
            'assigned' => 'bg-green',
            'not assigned' => 'bg-red'
        ];

        $this->status_colors = [
            'received' => 'bg-yellow',
            'packed' => 'bg-blue',
            'shipped' => 'bg-purple',
            'delivered' => 'bg-green',
            'cancelled' => 'bg-red',
        ];

        $this->current_work_status_colors = [
            'received' => 'bg-yellow',
            'packed' => 'bg-blue',
            'shipped' => 'bg-purple',
            'on process' => 'bg-orange',
        ];


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('delivery.view') && !auth()->user()->can('view_own_delivery')) {
            abort(403, 'Unauthorized action.');
        }


        $business_id = request()->session()->get('user.business_id');
        $deliveryStatuses = $this->transactionUtil->deliveryStatuses();

        if ($request->ajax()) {

            $deliveries = $this->transactionUtil->getDeliveries($business_id);

            if (request()->has('assigned_by')) {
                $assigned_by = request()->get('assigned_by');
                if (!empty($created_by)) {
                    $deliveries->where('deliveries.assigned_by', $assigned_by);
                }
            }

            if (!empty(request()->delivery_person_id)) {
                $delivery_person_id = request()->delivery_person_id;
                $deliveries->where('delivery_person_id', $delivery_person_id);
            }

            if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (!empty($location_id)) {
                    $deliveries->where('transactions.location_id', $location_id);
                }
            }

            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $deliveries->whereDate('deliveries.created_at', '>=', $start)
                    ->whereDate('deliveries.created_at', '<=', $end);
            }


            if (!empty(request()->input('delivery_status'))) {
                $deliveries->where('deliveries.delivery_status', request()->input('delivery_status'));
            }

            if (auth()->user()->can('delivery.view')) {
                $delivery_person = DeliveryPerson::where('user_id', Auth::user()->id)->first();
                if (isset($delivery_person)) {
                    $deliveries->where('deliveries.delivery_person_id', $delivery_person->id);
                }
            }

            if (!auth()->user()->can('delivery.view') && auth()->user()->can('view_own_delivery')) {
                $deliveries->where('deliveries.assigned_by', Auth::user()->id);
            }


            $deliveries->groupBy('deliveries.id');

            return Datatables::of($deliveries)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle btn-xs"
                        data-toggle="dropdown" aria-expanded="false">' .
						__("messages.actions") .
						'<span class="caret"></span><span class="sr-only">Toggle Dropdown
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';
					if (auth()->user()->can("delivery.view")) {
						$html .= '<li><a href="' . action("DeliveryController@show", [$row->id]) . '"><i class="fas fa-eye" aria-hidden="true"></i>' . __("messages.view") . '</a></li>';
					}
					if (auth()->user()->can('delivery.update')) {
						$html .= '<li><a href="' . action("DeliveryController@edit", [$row->id]) . '"><i class="fas fa-edit"></i> ' . __("messages.edit") . '</a></li>';
					}
					if (auth()->user()->can('delivery.delete')) {
						$html .= '<li><a href="' . action("DeliveryController@destroy", [$row->id]) . '" class="delete-delivery"><i class="fas fa-trash"></i> ' . __("messages.delete") . '</a></li>';
					}
					$html .= '</ul></div>';
					return $html;
				})
				->removeColumn('id')
                ->editColumn('type', function($row) {
                    if($row->type=='sell_transfer') {
                         return 'Stock Transfer';
                     }
                     else{
                         return $row->type;
                     }
 
                 })
				->editColumn('delivery_status', function ($row) use ($deliveryStatuses) {
					$status = $deliveryStatuses[$row->delivery_status];
					$status_color = !empty($this->status_colors[$row->delivery_status]) ? $this->status_colors[$row->delivery_status] : 'bg-gray';
					$status = '<a href="#" class="update_status" data-status="' . $row->delivery_status . '" data-href="' . action("DeliveryController@statusupdate", [$row->id]) . '"><span class="label ' . $status_color . '">' . $deliveryStatuses[$row->delivery_status] . '</span>';
					return $status;
				})
				->setRowAttr([
					'data-href' => function ($row) {
						if (auth()->user()->can("delivery.view") || auth()->user()->can("view_own_delivery")) {
							return action('DeliveryController@show',[$row->id]);
						} else {
							return '';
						}
					}])
				->rawColumns(['action', 'delivery_status','type'])
				->make(true);
//
        } else {
            $sales_representative = User::forDropdown($business_id, false, false, true);
            $business_locations = BusinessLocation::forDropdown($business_id, false);
            return view('delivery.index')->with(compact('deliveryStatuses', 'business_locations', 'sales_representative'));
        }

    }


    public function listDeliveryTransaction(Request $request)
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('view_own_purchase') && !auth()->user()->can('direct_sell.access') && !auth()->user()->can('sell.view') && !auth()->user()->can('view_own_sell_only')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $assignStatuses = $this->transactionUtil->delivery_assign_statuses();
        if ($request->ajax()) {
            $transactions = $this->transactionUtil->getListTransactions($business_id);
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $transactions->whereIn('transactions.location_id', $permitted_locations);
            }

            if (request()->has('created_by')) {
                $created_by = request()->get('created_by');
                if (!empty($created_by)) {
                    $transactions->where('transactions.created_by', $created_by);
                }
            }


            if (!empty(request()->customer_id)) {
                $customer_id = request()->customer_id;
                $transactions->where('contacts.id', $customer_id);
            }

            if (!auth()->user()->can('direct_sell.access') && auth()->user()->can('view_own_sell_only')) {
                $transactions->where('transactions.created_by', Auth::user()->id)->where('transactions.type', 'sell');
            }

            if (!auth()->user()->can('purchase.view') && auth()->user()->can('view_own_purchase')) {
                $transactions->where('transactions.created_by', Auth::user()->id)->where('transactions.type', 'purchase');
            }

            if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (!empty($location_id)) {
                    $transactions->where('transactions.location_id', $location_id);
                }
            }

            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $transactions->whereDate('transactions.transaction_date', '>=', $start)
                    ->whereDate('transactions.transaction_date', '<=', $end);
            }

            if (!empty(request()->input('created_by'))) {
                $transactions->where('transactions.created_by', request()->input('created_by'));
            }

            if (!empty(request()->input('assign_delivery_status'))) {
                if (request()->input('assign_delivery_status') == 'assigned') {
                    $assign_status = 1;
                } else {
                    $assign_status = 0;
                }
                $transactions->where('transactions.assign_delivery_status', $assign_status);
            }

            $transactions->groupBy('transactions.id');

            $datatable = DataTables::of($transactions)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                        data-toggle="dropdown" aria-expanded="false">' .
						__("messages.actions") .
						'<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';

                    if (auth()->user()->can("assign.delivery") && $row->assign_delivery_status==0) {
                        $html .= '<li><a href="' . action('DeliveryController@create', [$row->id]) . '"><i class="fas fa-edit"></i>' . __("messages.assign_delivery") . '</a></li>';
                    }
                    if ((auth()->user()->can("purchase.view")||auth()->user()->can('view_own_purchase')) && $row->type =='purchase') {
                        $html .= '<li><a href="#" data-href="' . action('PurchaseController@show', [$row->id]) . '" class="btn-modal" data-container=".view_modal"><i class="fas fa-eye" aria-hidden="true"></i>' . __("messages.view") . '</a></li>';
                    }
                    if ((auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access") || auth()->user()->can("view_own_sell_only")) && $row->type =='sell') {
                        $html .= '<li><a href="#" data-href="' . action("SellController@show", [$row->id]) . '" class="btn-modal" data-container=".view_modal"><i class="fas fa-eye" aria-hidden="true"></i> ' . __("messages.view") . '</a></li>';
                    }
                    if (auth()->user()->can("purchase.update") && $row->type =='purchase') {
                        $html .= '<li><a href="' . action('PurchaseController@edit', [$row->id]) . '"><i class="fas fa-edit"></i>' . __("messages.edit") . '</a></li>';
                    }
         
                    if (auth()->user()->can("direct_sell.access") && $row->type =='sell' && $row->is_direct_sale == 1) {
                        $html .= '<li><a target="_blank" href="' . action('SellController@edit', [$row->id]) . '"><i class="fas fa-edit"></i> ' . __("messages.edit") . '</a></li>';
                    }

                    if (auth()->user()->can("sell.update") && $row->type =='sell' && $row->is_direct_sale == 0) {
                        $html .= '<li><a target="_blank" href="' . action('SellPosController@edit', [$row->id]) . '"><i class="fas fa-edit"></i> ' . __("messages.edit") . '</a></li>';
                    }
                    
                    if ((auth()->user()->can("direct_sell.delete") || auth()->user()->can("sell.delete")) && $row->type =='sell') {
                        $html .= '<li><a href="' . action('SellPosController@destroy', [$row->id]) . '" class="delete-sale"><i class="fas fa-trash"></i> ' . __("messages.delete") . '</a></li>';
                    }
                    if (auth()->user()->can("purchase.delete") && $row->type =='purchase' ) {
                        $html .= '<li><a href="' . action('PurchaseController@destroy', [$row->id]) . '" class="delete-purchase"><i class="fas fa-trash"></i>' . __("messages.delete") . '</a></li>';
                    }

                    $html .= '</ul></div>';

                    return $html;
                }
                )
                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
                ->editColumn('assign_delivery_status', function ($row) use ($assignStatuses) {
                    if ($row->assign_delivery_status == 1) {
                        $assign_status = 'assigned';
                    } else {
                        $assign_status = 'not assigned';
                    }
                    $status = $assignStatuses[$assign_status];
                    $status_color = !empty($this->assign_status_colors[$assign_status]) ? $this->assign_status_colors[$assign_status] : 'bg-gray';
                    $status = '<span class="label ' . $status_color . '">' . $assignStatuses[$assign_status] . '</span>';
                    return $status;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        if ((auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access") || auth()->user()->can("view_own_sell_only")) && $row->type == 'sell') {
                            return action('SellController@show', [$row->id]);
                        } elseif ((auth()->user()->can("purchase.view") || auth()->user()->can("view_own_purchase_only")) && $row->type == 'purchase') {
                            return action('PurchaseController@show', [$row->id]);
                        } else {
                            return '';
                        }
                    }]);

            $rawColumns = ['action', 'transaction_date', 'assign_delivery_status'];
            return $datatable->rawColumns($rawColumns)
                ->make(true);
        }
        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id, false);
        $sales_representative = User::forDropdown($business_id, false, false, true);
        return view('delivery.assign_index')->with(compact('business_locations', 'customers', 'sales_representative', 'assignStatuses'));
    }


    public function getActiveWork(Request $request)
    {
        if (!auth()->user()->can('task.view') && !auth()->user()->can('view_own_task') && !auth()->user()->can('delivery.view') && !auth()->user()->can('view_own_delivery')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $deliveryStatuses = $this->transactionUtil->deliveryStatuses();
        $taskStatuses = $this->transactionUtil->taskStatuses();
        $statuses=array_merge($deliveryStatuses,$taskStatuses);
        $workTypes = $this->transactionUtil->workTypes();
        if ($request->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $currentWorks = $this->transactionUtil->getCurrentWork($business_id);
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $currentWorks->whereIn('deliveries.location_id', $permitted_locations);
            }
       
            if (!empty(request()->location_id)) {
                $currentWorks->where('location_id', request()->location_id);
            }


            if (!empty(request()->work_status)) {
                $currentWorks->where('deliveries.delivery_status', request()->work_status);
            }
           
            if (!empty(request()->work_type)) {
                $currentWorks->where('transactions.type', request()->work_type);
            }

            if (!empty(request()->delivery_person_id)) {
                $delivery_person_id = request()->delivery_person_id;
                $currentWorks->where('deliveries.delivery_person_id', $delivery_person_id);
            }

            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $currentWorks->where('deliveries.created_at', '>=', $start)
                    ->where('deliveries.created_at', '<=', $end);
            }
            if (auth()->user()->can('task.view') && auth()->user()->can('delivery.view')) {
                $delivery_person=DeliveryPerson::where('user_id',Auth::user()->id)->first();
                if(isset($delivery_person)){
                $currentWorks->where('deliveries.delivery_person_id', $delivery_person->id);
                }
            } 
            if (!auth()->user()->can('task.view') && auth()->user()->can('view_own_task') && !auth()->user()->can('delivery.view') && auth()->user()->can('view_own_delivery')) {
                $currentWorks->where('deliveries.assigned_by', Auth::user()->id);
            }

            return Datatables::of($currentWorks)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                        data-toggle="dropdown" aria-expanded="false">' .
						__("messages.actions") .
						'<span class="caret"></span><span class="sr-only">Toggle Dropdown
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';
                    if (auth()->user()->can("task.view")) {
                        if ($row->type == 'delivery' || $row->type == 'pick up') {
                            $controller = 'TaskController';
                        } else {
                            $controller = 'DeliveryController';
                        }
                        $html .= '<li><a href="' . action($controller . '@show', [$row->id]) . '"><i class="fas fa-eye" aria-hidden="true"></i>' . __("messages.view") . '</a></li>';
                    }
                    if (auth()->user()->can('task.update')) {
                        $html .= '<li><a href="' . action($controller . '@edit', [$row->id]) . '"><i class="fas fa-edit"></i> ' . __("messages.edit") . '</a></li>';
                    }
                    if (auth()->user()->can('task.delete')) {
                        $html .= '<li><a href="' . action($controller.'@destroy', [$row->id]) . '" class="delete-work"><i class="fas fa-trash"></i> ' . __("messages.delete") . '</a></li>';
                    }
                    $html .= '</ul></div>';
                    return $html;
                })
                ->editColumn('type', function($row) {
                   if($row->type =='delivery'||$row->type =='pick up'){
                        return 'task';
                    } else if($row->type=='sell_transfer') {
                        return 'Stock Transfer';
                    }
                    else{
                        return $row->type;
                    }

                })
                ->editColumn('status', function($row) use($statuses) {
                    $status =  $statuses[$row->status];
                    $status_color = !empty($this->current_work_status_colors[$row->status]) ? $this->current_work_status_colors[$row->status] : 'bg-gray';
                    if($row->type =='delivery'||$row->type =='pick up'){
                    $status ='<a href="#" class="update_task_status" data-status="' . $row->status . '" data-href="' . action("TaskController@statusupdate", [$row->id]) . '"><span class="label ' . $status_color .'">' . $statuses[$row->status] . '</span></a>';
                    }
                    else{
                        $status ='<a href="#" class="update_delivery_status" data-status="' . $row->status . '" data-href="' . action("DeliveryController@statusupdate", [$row->id]) . '"><span class="label ' . $status_color .'">' . $statuses[$row->status] . '</span></a>';
                    }
                    return $status;
                })
                ->rawColumns(['action','type','status'])
                ->make(true);

        } else {
            $business_locations = BusinessLocation::forDropdown($business_id, false);
            $customers = Contact::customersDropdown($business_id, false);
            $sales_representative = User::forDropdown($business_id, false, false, true);
            return view('delivery.currentwork')->with(compact('statuses','taskStatuses','business_locations','customers','sales_representative','deliveryStatuses','workTypes','deliveryStatuses','taskStatuses'));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($transactionId)
    {
        if (!auth()->user()->can('assign.delivery')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $deliveryStatuses  = $this->transactionUtil->deliveryStatuses();
        $transaction=Transaction::where('business_id', $business_id)
                                    ->where('id', $transactionId)
                                    ->with(
                                        'contact',
                                        'location'
                                    )
                                    ->first();
                                 
        $default_delivery_person=$transaction->contact->delivery_person;
         return view('delivery.assign')
             ->with(compact('transaction', 'deliveryStatuses','default_delivery_person'));
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        if (!auth()->user()->can('delivery.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->except('_token');

            $business_id = $request->session()->get('user.business_id');
            $transaction=Transaction::findOrFail($input['transaction_id']);
            $user_id = Auth::user()->id;
            DB::beginTransaction();
            $delivery = Delivery::create([
                'transaction_id' => $input['transaction_id'],
                'delivery_person_id' => $input['delivery_person_id'],
                'delivery_status' => $input['delivery_status'],
                'shipping_address' => $input['shipping_address'],
                'shipping_latitude' => $input['shipping_latitude'],
                'shipping_longitude' => $input['shipping_longitude'],
                'pickup_address' => $input['pickup_address'],
                'pickup_latitude' => $input['pickup_latitude'],
                'pickup_longitude' => $input['pickup_longitude'],
                'delivered_to' => $input['delivered_to'],
                'assigned_by' => $user_id,
                'special_delivery_instructions' => $input['special_delivery_instructions'],
            ]);
            if (isset($delivery)) {
                $transaction = Transaction::findOrFail($input['transaction_id']);
                $transaction->assign_delivery_status = 1;
                $transaction->save();
            }

            if ($delivery->delivery_status == 'received') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();
            }
            if ($delivery->delivery_status == 'packed') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();

                if($transaction->type=='sell_transfer'){
                $transaction->status='pending';
                $transaction->save();
                }
                else if($transaction->type=='purchase'){
                    $transaction->status='pending';
                    $transaction->save();
                }
            }
            if ($delivery->delivery_status == 'shipped') {
                $delivery_started_at = now();
                $delivery->delivery_started_at = $delivery_started_at;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='transit';
                    $transaction->save();
                }
                else if($transaction->type=='purchase'){
                        $transaction->status='pending';
                        $transaction->save();
                 }
            }
            if ($delivery->delivery_status == 'delivered') {
                $delivery_ended_at = now();
                $delivery->delivery_ended_at = $delivery_ended_at;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='completed';
                    $transaction->save();
                }
                else if($transaction->type=='purchase'){
                        $transaction->status='received';
                        $transaction->save();
                 }
            }
            if ($delivery->delivery_status == 'cancelled') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();

            }
            DB::commit();
            $delivery_person = DeliveryPerson::find($request->delivery_person_id);
            $user=User::find($delivery_person->user_id);
            $user->notify(new DeliveryAssignedNotification($delivery->record_staff->user_name,$delivery->id));
            $output = ['success' => 1,
                'msg' => __('delivery.delivery_assign_success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return redirect('delivery-transaction')->with('status', $output);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Delivery $delivery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('delivery.view')) {
            abort(403, 'Unauthorized action.');
        }

        $delivery = Delivery::with(['delivery_person', 'transaction', 'record_staff'])->findorfail($id);
        $delivery_person = $this->moduleUtil->getDeliveryUser($delivery->delivery_person_id);
        return view('delivery.show', compact('delivery', 'delivery_person'));
    }

    public function trackDeliveryPeople(Request $request)
    {
        if (!auth()->user()->can('delivery.view')) {
            abort(403, 'Unauthorized action.');
        }
        $delivery_people=DeliveryPerson::getAllDeliveryPeople();
        return view('delivery.track',compact('delivery_people'));
    }

	public function livetrackDeliveryPeople()
	{
		if (!auth()->user()->can('delivery.view')) {
			abort(403, 'Unauthorized action.');
		}
		$delivery_people=DeliveryPerson::getAllDeliveryPeople();
		return response()->json([
			$delivery_people
	]);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delivery $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('delivery.update')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $deliveryStatuses = $this->transactionUtil->deliveryStatuses();

        $delivery = Delivery::with('transaction', 'record_staff')->where('id', $id)->first();

        return view('delivery.edit')->with(compact('delivery', 'deliveryStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Delivery $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('delivery.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {

            $delivery = Delivery::findOrFail($id);

            if ($delivery->delivery_status == 'delivered') {
                $delivered_status_set = 1;
            } else if ($delivery->delivery_status == 'shipped') {
                $shipped_status_set = 1;
            }


            $transaction = Transaction::findOrFail($request['transaction_id']);

            $delivery_data = $request->only(['delivery_person_id', 'delivery_status', 'shipping_address', 'shipping_latitude', 'shipping_longitude', 'pickup_address', 'pickup_latitude', 'pickup_longitude', 'special_delivery_instructions', 'delivered_to']);


            DB::beginTransaction();

            $delivery->update($delivery_data);

            if ($delivery->delivery_status == 'received') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();
            }
            if ($delivery->delivery_status == 'packed') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='pending';
                    $transaction->save();
                    }
                    else if($transaction->type=='purchase'){
                        $transaction->status='pending';
                        $transaction->save();
                }
            }
            if (!isset($shipped_status_set) && $delivery->delivery_status == 'shipped') {
                $delivery_started_at = now();
                $delivery->delivery_started_at = $delivery_started_at;
                $delivery->save();
               
                if($transaction->type=='sell_transfer'){
                    $transaction->status='transit';
                    $transaction->save();
                }
                else if($transaction->type=='purchase'){
                        $transaction->status='pending';
                        $transaction->save();
                 }
            }
            if (!isset($delivered_status_set) && $delivery->delivery_status == 'delivered') {
                $delivery_ended_at = now();
                $delivery->delivery_ended_at = $delivery_ended_at;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='completed';
                    $transaction->save();
                }
                else if($transaction->type=='purchase'){
                        $transaction->status='received';
                        $transaction->save();
                 }
            }
            if ($delivery->delivery_status == 'cancelled') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();

            }
            DB::commit();

            $output = ['success' => 1,
                'msg' => __('contact.record_updated_success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = ['success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];

        }
        return redirect('delivery')->with('status', $output);
    }

    public function statusupdate(Request $request, $id)
    {
        
        if (!auth()->user()->can('delivery.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $delivery = Delivery::with('transaction')->findorfail($id);
            if ($delivery->delivery_status == 'delivered') {
                $delivered_status_set = 1;
            } else if ($delivery->delivery_status == 'shipped') {
                $shipped_status_set = 1;
            }

            $data = $request->validate([
                'delivery_status' => 'required',
            ]);
            $transaction=Transaction::findorFail($delivery->transaction_id);
            DB::beginTransaction();
            $update_data['delivery_status'] = $request->input('delivery_status');
            $delivery->update($update_data);
            
            if ($delivery->delivery_status == 'received') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();
            }
            if ($delivery->delivery_status == 'packed') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='pending';
                    $transaction->save();
                    }
                    else if($transaction->type=='purchase'){
                        $transaction->status='pending';
                        $transaction->save();
                 }
            }
            if (!isset($shipped_status_set) && $delivery->delivery_status == 'shipped') {
                $delivery_started_at = now();
                $delivery->delivery_started_at = $delivery_started_at;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='transit';
                    $transaction->save();
                }
                else if($transaction->type=='purchase'){
                        $transaction->status='pending';
                        $transaction->save();
                }
            }
            if (!isset($delivered_status_set) && $delivery->delivery_status == 'delivered') {
                $delivery_ended_at = now();
                $delivery->delivery_ended_at = $delivery_ended_at;
                $delivery->save();
                if($transaction->type=='sell_transfer'){
                    $transaction->status='completed';
                    $transaction->save();
                }
                else if($transaction->type=='purchase'){
                        $transaction->status='received';
                        $transaction->save();
                }
            }
            if ($delivery->delivery_status == 'cancelled') {
                $delivery->delivery_started_at = null;
                $delivery->delivery_ended_at = null;
                $delivery->save();

            }
          
            DB::commit();
            $output = ['success' => 1,
                'msg' => __('Delivery status updated succesfully')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = ['success' => 0,
                'msg' => $e->getMessage()
            ];
        }
        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Delivery $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delivery.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                DB::beginTransaction();
                $delivery = Delivery::with('transaction')->findorfail($id);
                $transaction = $delivery->transaction;
                $delivery->delete();
                $transaction->assign_delivery_status = 0;
                $transaction->save();

                DB::commit();
                $output = ['success' => true,
                    'msg' => __("delivery deleted sucessfully")
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = ['success' => false,
                    'msg' => __("something went wrong")
                ];
            }
            return $output;
        }
    }

}
