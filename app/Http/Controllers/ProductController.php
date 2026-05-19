<?php

namespace App\Http\Controllers;

use App\Constants\CardCondition;
use App\Constants\CardGrading;
use App\Constants\CardLanguage;
use App\Constants\CardType;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService
    ) {}

    public function index(Request $request)
    {
        $filters = [
            'category'  => $request->category,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'sort'      => $request->get('sort', 'newest'),
            'search'    => $request->search,
            'condition' => $request->condition,
            'language'  => $request->language,
            'set'       => $request->set,
            'rarity'    => $request->rarity,
            'grading'   => $request->grading,
            'card_type' => $request->card_type,
            'per_page'  => 12,
        ];

        $productsResult = $this->productService->getProducts($filters);

        if ($productsResult->isError()) {
            abort(500, $productsResult->getMessage());
        }

        $products = $productsResult->getData();

        // Get categories with parent-child relationships
        $categoriesResult = $this->categoryService->getAllCategoriesWithChildren();
        $categories = $categoriesResult->isSuccess() ? $categoriesResult->getData() : collect();

        $conditionOptions = CardCondition::options();
        $languageOptions  = CardLanguage::options();
        $gradingOptions   = CardGrading::options();
        $cardTypeOptions  = CardType::options();

        return view('products.index', compact('products', 'categories',
            'conditionOptions', 'languageOptions', 'gradingOptions', 'cardTypeOptions'));
    }

    public function show($slug)
    {
        $productResult = $this->productService->getProductBySlug($slug, true);

        if ($productResult->isError()) {
            abort(404, $productResult->getMessage());
        }

        $product = $productResult->getData();

        // Get related products from same category
        $relatedProductsResult = $this->productService->getRelatedProducts($product->id, $product->category_id, 4);
        $relatedProducts = $relatedProductsResult->isSuccess() ? $relatedProductsResult->getData() : collect();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
