<?php

namespace App\Http\Controllers\Api;

use App\BusinessLocation;
use App\Front\Cart;
use App\Front\Document;
use App\InvoiceScheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Utils\ContactUtil;
use App\Utils\TransactionUtil;
use App\Utils\NotificationUtil;
use App\Http\Controllers\Controller;
use App\Notifications\OrderCreatedNotification;
use App\User;
use App\Variation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
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
	public function checkout()
	{
		$path=asset('/uploads/media/');
		$user_id = Auth::guard('customerapi')->user()->id;
		$cart_items = Cart::leftJoin('variations as v','carts.product_id','=','v.id')
							->leftJoin('products as p','v.product_id','=','p.id')
							->leftJoin('media as m','m.model_id','=','v.id')
							->where('user_id', $user_id)
							->select(
								'carts.id',
								'carts.quantity',
								'carts.total_price as product_price',
								'v.id as variation_id',
								'p.id as product_id',
								'v.name as variation_name',
								'p.name as product_name',
								DB::raw("CONCAT('$path','/',m.file_name) as product_image"),
							)
							->get();
		$user=Auth::guard('customerapi')->user();
		$total_price = Cart::where('user_id', $user_id)->sum('total_price');
		$total_price=(string)$total_price;
		$data=collect($cart_items->toArray());
		$price=[];
		$price['total_price']=$total_price;
		$total_price=collect([$price]);
		return response()->json([
			'data'=>$data,
			'price'=>$total_price
		]);
	}

	public function documents(){
		$path=asset('/uploads/shop/');
		$banner = Document::where('file_type','banner')
				  ->select(
					  'documents.id',
					  DB::raw("CONCAT('$path','/',documents.file_name) as banner_image"),
				  )
				  ->first();
		$catalogues=Document::where('file_type','catalogue')->limit('2')->latest()->get();
		$document=[];
		$document=$banner;	
		$banner=collect([$document]);	
		return response()->json([
			'banner' => $banner,
			'catalogues' => $catalogues,
		]);
	}

	public function downloadFile($fileId){
        $file = Document::where('id',$fileId)->first();
        $myfile = public_path('uploads/shop').'/'.$file->file_name;
        $myfile=path_fixer($myfile);
        return response()->download($myfile);
    }


	public function store(Request $request)
	{
			try {
			$input = $request->except('_token');
				
			$location = BusinessLocation::where('location_id', 'BL0001')->first();
			$input['status'] = 'draft';
			$input['location_id']=$location->id;
			$assign_delivery=1;
			$user=Auth::guard('customerapi')->user();
			$input['contact_id']=$user->id;
			$business_id=$user->business_id;
			$input['commission_agent'] = !empty($request->input('commission_agent')) ? $request->input('commission_agent') : null;
			$input['discount_amount'] = !empty($request->input('discount_amount')) ? $request->input('discount_amount') : null;
			$input['discount_type'] = !empty($request->input('discount_type')) ? $request->input('discount_amount') : null;
			$cart_items=json_decode($input['cart_items'],true);
			$input['cart_items']=$cart_items;
			$invoice_total=$input['total_price'];
			$input['final_total']=$invoice_total;
			$input['is_direct_sale']=1;
			$input['is_save_and_print']=1;
			$input['transaction_date'] = Carbon::now()->format('Y-m-d H:i:s');
			DB::beginTransaction();
			//Customer group details
			$contact_id = $user->id;

			$cg = $this->contactUtil->getCustomerGroup($business_id, $contact_id);
			$input['customer_group_id'] = (empty($cg) || empty($cg->id)) ? null : $cg->id;
			$invoice=InvoiceScheme::where('name','Default')->first();
			$input['invoice_scheme_id']=$invoice->id;
			$product=[];
			$products=[];
			foreach($cart_items as $item){
				$variation=Variation::with('product')->where('id',$item['product_id'])->first();
				$product['product_type']=$variation->product->type;
				$product['unit_price']=$variation->default_sell_price;
				$product['line_discount_price']='fixed';
				$product['line_discount_amount']=0;
				$product['item_tax']=0;
				$product['tax_id']=null;
				$product['sell_line_note']=null;
				$product['lot_no_line_id']=null;
				$product['product_id']=$variation->product->id;
				$product['variation_id']=$variation->id;
				$product['enable_stock']=$variation->product->enable_stock;
				$product['quantity']=$item['quantity'];
				$product['product_unit_id']=$variation->product->unit_id;
				$product['sub_unit_id']=$variation->product->unit_id;
				$product['base_unit_multiplier']=1;
				$product['unit_price_inc_tax']=$variation->sell_price_inc_tax;
				array_push($products,$product);
			}
			$input['products']=$products;
			if (!empty($input['products'])) {
				$transaction = $this->transactionUtil->createSellTransaction($business_id, $input, $invoice_total,1,$assign_delivery,$uf_data = true);
				
				$this->transactionUtil->createOrUpdateSellLines($transaction, $input['products'], $input['location_id']);
				$is_credit_sale = isset($input['is_credit_sale']) && $input['is_credit_sale'] == 1 ? true : false;
				//$this->notificationUtil->autoSendNotification($business_id, 'new_sale', $transaction, $user);

				$admin=User::where('user_type','admin')->first();

        		$admin->notify(new OrderCreatedNotification($transaction->contact->name,$transaction));

				$cart_items=Cart::where('user_id',$transaction->contact_id)->get();
				if($cart_items){

					foreach ($cart_items as $item){
						$item->delete();
					}
				}

				DB::commit();

				$msg = trans("sale.order_added");
				$status=200;
				$output = ['success' => 1, 'msg' => $msg ];
			}
			else {
				$output = ['success' => 0,
					'msg' => trans("messages.something_went_wrong"),
				];
			}
		}
		catch (\Exception $e) {
			DB::rollBack();
			\Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
			$msg = trans("messages.something_went_wrong");
			$status=401;
			$output = ['success' => 0,
				'msg' => $msg
			];
		}
		return response()->json([
			'message'=>$msg
		],$status);
	}
}
