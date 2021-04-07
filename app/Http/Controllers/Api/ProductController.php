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
					->where('c.status','active')
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
					->orderBy('set_featured','DESC')->orderBy('id','DESC')
					->paginate(14);
			$items=[];
			$items=$products;
			$products=collect([$items]);			
		return response()->json([
			'product' => $products,
		]);
	}

	public function categories(){
		
		$special_categories = Category::with(['sub_categories','sub_categories.sub_category_products.variations.media','products.variations.media'])->where('name', 'like', '%special%')->where('parent_id', 0)->first();
        
		if ($special_categories == null) {
            $all_categories = Category::with(['sub_categories','sub_categories.sub_category_products.variations.media','products.variations.media'])->where('parent_id', 0)
										->active()->orderBy('display_order')->get();
        } else {
            $all_categories = Category::with(['sub_categories','sub_categories.sub_category_products.variations.media','products.variations.media'])
										->where('id', '!=', $special_categories->id)->where('parent_id', 0)->active()->orderBy('display_order')->get();
        }
		$items=[];
		$items=$special_categories;
		$special_categories=collect([$items]);	
		return response()->json([
			'categories' => $all_categories,
			'special_category'=>$special_categories
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
		$term = $request->get('query');
		$location = BusinessLocation::where('location_id', 'BL0001')->first();
		$variation_location_product_ids = VariationLocationDetails::with('location')->where('location_id', $location->id)->pluck('variation_id')->toArray();
		$products = Product::leftJoin('variations', 'products.id', '=', 'variations.product_id')
		->leftJoin('media as m','m.model_id','=','variations.id')
		->whereIn('products.id', $variation_location_product_ids)
		->where(function ($query) use ($term) {
			$query->where('products.name', 'like', '%' . $term . '%');
			$query->orWhere('products.sku', 'like', '%' . $term . '%');
		})
		->select(
			'products.name as name',
			'variations.name as variation_name',
			'products.product_description',
			'variations.market_price',
			'variations.id as variation_id',
			'variations.sub_sku as sub_sku',
			'variations.default_sell_price as unit_price',
			'variations.sell_price_inc_tax as unit_price_with_tax',
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

	public function searchByCategory($id){
		$path=asset('/uploads/media/');
		$location = BusinessLocation::where('location_id', 'BL0001')->first();
		$variation_location_variation_ids = VariationLocationDetails::with('location')->where('location_id', $location->id)->pluck('variation_id')->toArray();
		$products = Product::leftJoin('variations as v','products.id','=','v.product_id')
			->leftJoin('categories as c','products.category_id','=','c.id')
			->leftJoin('categories as sc','products.sub_category_id','=','sc.id')
			->leftJoin('media as m','m.model_id','=','v.id')
			->where('products.category_id', $id)
			->orwhere('products.sub_category_id',$id)
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
