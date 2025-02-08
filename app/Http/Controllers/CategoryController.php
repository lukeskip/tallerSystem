<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ValidateDataService;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read category', ['only' => ['index', 'show']]);
        $this->middleware('can:create category', ['only' => ['create', 'store']]);
        $this->middleware('can:edit category', ['only' => ['edit', 'update', 'categoriesOrder']]);
        $this->middleware('can:delete category', ['only' => ['destroy']]);

        $this->rules = [
            'categories' => 'required|array',
        ];
    }

    public function categoriesOrder(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData()['data'];
        $categoriesRequest = collect($validatedData['categories']);
        $categoriesIds = $categoriesRequest->pluck('id');
        
        $categories = Category::whereIn('id', $categoriesIds)->get();

        $categories->each(function ($category) use ($categoriesRequest) {
            $newOrder = $categoriesRequest->firstWhere('id', $category->id)['order'] ?? null;
            if (!is_null($newOrder)) {
                $category->update(['order' => $newOrder]);
            }
        });
    
        return response()->json([
            'message' => 'Orden de categorías actualizado correctamente',
            'categories' => $categories->pluck('id', 'order')
        ]);
    }
}
