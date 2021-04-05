<?php

namespace App\Http\Controllers\Api;

use App\Business;
use App\Transaction;
use Illuminate\Http\Request;
use App\Delivery;
use App\DeliveryPerson;
use App\Events\TransactionPaymentAdded;
use App\Http\Controllers\Controller;
use App\ReferenceCount;
use App\TransactionPayment;
use App\Utils\ContactUtil;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{

    protected $contactUtil;
	protected $transactionUtil;
	protected $notificationUtil;

	public function __construct( ContactUtil $contactUtil, TransactionUtil $transactionUtil,NotificationUtil $notificationUtil)
	{
		$this->contactUtil = $contactUtil;
		$this->transactionUtil = $transactionUtil;
		$this->notificationUtil = $notificationUtil;
	}

    public function index(){

        if (!auth()->user()->can('delivery.view') && !auth()->user()->can('view_own_delivery')) {
            abort(403, 'Unauthorized action.');
        }
        if(auth()->user()->user_type=='delivery' || auth()->user()->user_type=='Delivery')
        {
            $delivery_person = DeliveryPerson::where('user_id', auth()->user()->id)->first();
            $delivery = Delivery::with('transaction')->where('delivery_person_id', $delivery_person->id)->get();
        }
        elseif(auth()->user()->user_type=='admin' || auth()->user()->user_type=='Admin'){
            $delivery = Delivery::with('transaction')->get();
        }

        return response()->json([
            'data'=>$delivery
        ]);
    }

    public function view($id){

    }

    public function update(Request $request,$id){

    try {
        $delivery=Delivery::findorfail($id);
        if ($delivery->delivery_status == 'delivered') {
            $delivered_status_set = 1;
        } else if ($delivery->delivery_status == 'shipped') {
            $shipped_status_set = 1;
        }

	    $transaction=Transaction::findorFail($delivery->transaction_id);

        DB::beginTransaction();
        $delivery_data= $request->only(['delivery_status', 'delivered_to']);
        $delivery->update($delivery_data);

        if ($delivery->delivery_status == 'received') {
            $delivery->delivery_started_at = null;
            $delivery->delivery_ended_at = null;
            $delivery->save();
        }
        
	    if($request->delivery_status=='packed'){
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

        if(!isset($shipped_status_set) && $request->delivery_status=='shipped'){
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

        if(!isset($delivered_status_set) && $request->delivery_status=='delivered'){
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

        if(($delivery->delivery_status=='delivered') || $request->delivery_status=='delivered'){
            if ($transaction->payment_status != 'paid') {
                    $inputs['paid_on'] = Carbon::now()->format('Y-m-d H:i:s');
                    $inputs['transaction_id'] = $transaction->id;
                    $inputs['amount'] = $request['amount'];
                    $inputs['created_by'] = auth()->user()->id;
                    $inputs['payment_for'] = $transaction->contact_id;
                    $prefix_type = 'purchase_payment';
                    if (in_array($transaction->type, ['sell', 'sell_return'])) {
                        $prefix_type = 'sell_payment';
                    } elseif (in_array($transaction->type, ['expense', 'expense_refund'])) {
                        $prefix_type = 'expense_payment';
                    }
                    
                    $ref_count = $this->setAndGetReferenceCount($prefix_type,$transaction->business_id);
                    //Generate reference number
                    $inputs['payment_ref_no'] = $this->generateReferenceNumber($prefix_type, $ref_count);
                    $inputs['business_id'] = $transaction->business_id;
                   
                    if (!empty($inputs['amount'])) {
                        $tp = TransactionPayment::create($inputs);
                        $inputs['transaction_type'] = $transaction->type;
                        event(new TransactionPaymentAdded($tp, $inputs));
                    }
                    
                    //update payment status
                    $this->transactionUtil->updatePaymentStatus($id, $transaction->final_total);
                }

                $status=200;
                $msg=__('purchase.payment_added_success');

        }

        $msg=__('delivery.delivery_update_success');
        DB::commit();
        $output = ['success' => true,
        'msg' => $msg
        ];

		$delivery=Delivery::where('id',$id)->get();

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        $status=401;
        $msg=__('messages.something_went_wrong');
        $output = ['success' => false,
                      'msg' => $msg
                  ];
        }

        return response()->json([
                'data'=>$delivery,
                'message'=>$msg
            ]);
    }

    public function setAndGetReferenceCount($type, $business_id)
    {

        $ref = ReferenceCount::where('ref_type', $type)
            ->where('business_id', $business_id)
            ->first();
        if (!empty($ref)) {
            $ref->ref_count += 1;
            $ref->save();
            return $ref->ref_count;
        } else {
            $new_ref = ReferenceCount::create([
                'ref_type' => $type,
                'business_id' => $business_id,
                'ref_count' => 1
            ]);
            return $new_ref->ref_count;
        }
    }

    public function generateReferenceNumber($type, $ref_count, $business_id = null, $default_prefix = null)
    {
        $ref_no_prefixes=["purchase_payment"=>"PP","sell_payment"=>"SP"];
        $prefix = $ref_no_prefixes[$type];

        if (!empty($business_id)) {
            $business = Business::find($business_id);
            $prefixes = $business->ref_no_prefixes;
            $prefix = !empty($prefixes[$type]) ? $prefixes[$type] : '';
        }

        if (!empty($default_prefix)) {
            $prefix = $default_prefix;
        }

        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        if (!in_array($type, ['contacts', 'business_location', 'username'])) {
            $ref_year = \Carbon::now()->year;
            $ref_number = $prefix . $ref_year . '/' . $ref_digits;
        } else {
            $ref_number = $prefix . $ref_digits;
        }

        return $ref_number;
    }
    
}




