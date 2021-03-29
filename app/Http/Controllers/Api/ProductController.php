<?php

namespace App\Http\Controllers\api;

use App\BusinessLocation;
use App\ProductVariation;
use App\VariationLocationDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Variation;
use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\Relation;


class ProductController extends Controller
{



	public function products()
	{
		$path=asset('/uploads/media/');
		$location = BusinessLocation::where('location_id', 'BL0001')->first();
        $variation_location_variation_ids = VariationLocationDetails::with('location')->where('location_id', $location->id)->pluck('variation_id')->toArray();
		$products = Product::leftJoin('variations as v','products.id','=','v.product_id')
					->leftJoin('categories as c','products.category_id','=','c.id')
					->leftJoin('categories as sc','products.sub_category_id','=','sc.id')
					->leftJoin('media as m','m.model_id','=','v.id')
					->whereIn('v.id', $variation_location_variation_ids)
					->select(
						'products.id',
						'products.name',
						'products.type',
						'products.product_description',
						'v.id as variation_id',
						'v.name as variation_name',
						'v.sub_sku',
						'v.market_price',
						'v.default_sell_price as unit_price',
						'v.sell_price_inc_tax as unit_price_with_tax',
						'v.id as variation_id',
						'c.id as category_id',
						'c.name as category_name',
						'sc.id as sub_category_id',
						'sc.name as sub_category_name',
						'sc.parent_id',
						DB::raw("CONCAT('$path','/',m.file_name) as product_image")
					)
					->get();
						
		return response()->json([
			'product' => $products,
		]);
	}

	public function categories(){
		$special_cat = Category::with('sub_categories')->where('name', 'like', '%special%')->where('parent_id', 0)->first();
        if ($special_cat == null) {
            $all_categories = Category::with('sub_categories')->where('parent_id', 0)->get();
        } else {
            $all_categories = Category::with('sub_categories')->where('parent_id', 0)->where('id', '!=', $special_cat->id)->get();
        }
		
		return response()->json([
			'categories' => $all_categories,
			'special_category'=>$special_cat
		]);
	}

	public function product($slug)
	{
		$path=asset('/uploads/media/');
		$product = Product::leftJoin('variations as v','products.id','=','v.product_id')
					->leftJoin('media as m','m.model_id','=','v.id')
					->where('v.sub_sku', $slug)
					->select(
						'products.id',
						'products.name',
						'products.type',
						'products.product_description',
						'v.id as variation_id',
						'v.name as variation_name',
						'v.sub_sku',
						'v.market_price',
						'v.default_sell_price as unit_price',
						'v.sell_price_inc_tax as unit_price_with_tax',
						DB::raw("CONCAT('$path','/',m.file_name) as product_image")
					)
					->get();

		if (!$product) {
			return response()->json(["message" => 'Product Not Found!']);
		}

		return response()->json([
			'product' => $product
		]);

	}


	public function variation($slug)
	{
		$product = Variation::with('product')->where('sub_sku', $slug)->first();
		$product_cat = $product->product->category_id;
		$location = BusinessLocation::where('name', 'freshktm')->first();
		$variation_location_product_ids = VariationLocationDetails::with('location')->where('location_id', $location->id)->pluck('product_id')->toArray();
		$products = Product::with('product_variations.variations.product')->where('category_id', $product_cat)->whereIn('id', $variation_location_product_ids)->get();

		return response()->json([
			'variation' => $products
		]);
	}

	public function search(Request $request){
		$path=asset('/uploads/media/');
		$location = BusinessLocation::where('location_id', 'BL0001')->first();
		$variation_location_variation_ids = VariationLocationDetails::with('location')->where('location_id', $location->id)->pluck('variation_id')->toArray();
		$products = Product::leftJoin('variations as v','products.id','=','v.product_id')
			->leftJoin('categories as c','products.category_id','=','c.id')
			->leftJoin('categories as sc','products.sub_category_id','=','sc.id')
			->leftJoin('media as m','m.model_id','=','v.id')
			->where('products.name', 'like', '%' . $request->get('query') . '%')
			->whereIn('v.id', $variation_location_variation_ids)
			->select(
				'products.id',
				'products.name',
				'products.type',
				'products.product_description',
				'v.id as variation_id',
				'v.name as variation_name',
				'v.sub_sku',
				'v.market_price',
				'v.default_sell_price as unit_price',
				'v.sell_price_inc_tax as unit_price_with_tax',
				'v.id as variation_id',
				'c.id as category_id',
				'c.name as category_name',
				'sc.id as sub_category_id',
				'sc.name as sub_category_name',
				'sc.parent_id',
				DB::raw("CONCAT('$path','/',m.file_name) as product_image")
			)
			->get();
		if(count($products)>0){
		return response()->json([
			'product' => $products,
		]);
		}
		else{
			return response()->json([
				'message' => 'No Product Found',
			]);
		}
	}
}
