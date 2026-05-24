<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SepayWebhookController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/locale/{locale}', function (Request $request, string $locale) {
    $supportedLocales = array_keys(config('locales.supported', ['en' => 'English']));

    abort_unless(in_array($locale, $supportedLocales, true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

// chính sách
Route::get('/chinh-sach', function () {
    return view('policy');
})->name('policy');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Trang sản phẩm
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

// Danh mục
Route::get('/danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Trang tin tức
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news.show');

// Webhook Sepay (Không cần Auth)
Route::post('/webhook/sepay', [SepayWebhookController::class, 'handle'])->name('webhook.sepay');

// Trang người dùng & Mua hàng
Route::middleware('auth')->group(function () {
    Route::get('/nguoi-dung', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/nguoi-dung/update', [UserController::class, 'update'])->name('user.update');

    Route::get('/gio-hang', \App\Livewire\CartPage::class)->name('cart');
    Route::get('/thanh-toan', \App\Livewire\CheckoutPage::class)->name('checkout');
    Route::get('/thanh-toan/thanh-cong/{id}', [PurchaseController::class, 'success'])->name('purchase.success');


});

Route::feeds();

// Static pages
Route::get('/trang/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
