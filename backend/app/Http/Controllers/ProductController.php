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

            return ApiResponse::success(
                'Lista de produtos',
                200,
                $products
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Erro ao listar produtos - ' . $e->getMessage(), 500);
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

            return ApiResponse::success(
                'Produto criado com sucesso',
                201,
                $product
            );
        } catch (ValidationException $e) {
            return ApiResponse::error('Erro de validação', 422, $e->errors());
        } catch (Throwable $e) {
            return ApiResponse::error('Erro ao criar produto - ' . $e->getMessage(), 500);
        }
    }

    public function show($id) {
        try {
            $product = Product::findOrFail($id);

            return ApiResponse::success(
                'Produto encontrado',
                200,
                $product
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Produto não encontrado - ' . $e->getMessage(), 404);
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

            return ApiResponse::success(
                'Produto atualizado com sucesso',
                200,
                $product
            );
        } catch (ValidationException $e) {
            return ApiResponse::error('Erro de validação', 422, $e->errors());
        } catch (Throwable $e) {
            return ApiResponse::error('Erro ao atualizar produto - ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id) {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return ApiResponse::success(
                'Produto excluído com sucesso',
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Erro ao excluir produto - ' . $e->getMessage(), 500);
        }
    }
}
