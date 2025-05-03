<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductController extends Controller {

    public function index() {
        try {
            $products = Product::all();

            return ApiResponse::success('Products retrieved successfully', 200, $products);
        } catch (Throwable $e) {
            return ApiResponse::error('Error listing products - ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'quantity' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'category' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku'
            ]);

            $product = Product::create($validated);

            return ApiResponse::success('Product created successfully', 201, $product);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error', 422, $e->errors());
        } catch (Throwable $e) {
            return ApiResponse::error('Error creating product - ' . $e->getMessage(), 500);
        }
    }

    public function show($id) {
        try {
            $product = Product::findOrFail($id);

            return ApiResponse::success('Product retrieved successfully', 200, $product);
        } catch (Throwable $e) {
            return ApiResponse::error('Error fetching product - ' . $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id) {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'quantity' => 'sometimes|integer|min:0',
                'price' => 'sometimes|numeric|min:0',
                'category' => 'sometimes|string|max:255',
                'sku' => 'sometimes|string|unique:products,sku,' . $product->id
            ]);

            $product->update($validated);

            return ApiResponse::success('Product updated successfully', 200, $product);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error', 422, $e->errors());
        } catch (Throwable $e) {
            return ApiResponse::error('Error updating product - ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id) {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return ApiResponse::success('Produto excluído com sucesso', 200);
        } catch (Throwable $e) {
            return ApiResponse::error('Erro ao excluir produto - ' . $e->getMessage(), 500);
        }
    }
}
