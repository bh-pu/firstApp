<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Product as ProductResource;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::all();
            return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required',
                'detail' => 'required'
            ]);
            $input['user_id'] = Auth::id();

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $product = Product::create($input);

            return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::find(decrypt($id));

            if (is_null($product)) {
                return $this->sendError('Product not found.');
            }

            return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail(decrypt($id));
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required',
                'detail' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $product->name = $input['name'];
            $product->detail = $input['detail'];
            $product->save();

            return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail(decrypt($id));
            $product->delete();
            return $this->sendResponse([], 'Product deleted successfully.');
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }
}

