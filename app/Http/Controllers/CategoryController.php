<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function show($slug)
    {
        $categoryResult = $this->categoryService->getCategoryBySlug($slug);

        if ($categoryResult->isError()) {
            abort(404, $categoryResult->getMessage());
        }

        $category = $categoryResult->getData();

        $productsResult = $this->categoryService->getProductsByCategory($category->id);

        if ($productsResult->isError()) {
            abort(500, $productsResult->getMessage());
        }

        $products = $productsResult->getData();

        return view('categories.show', compact('category', 'products'));
    }
}
