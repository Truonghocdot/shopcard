<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('phone', 20)->nullable();
            $table->text('password');
            $table->text('password2')->nullable();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->tinyInteger('role')->default(0)->comment('0:client | 1:admin');
            $table->tinyInteger('status')->default(1)->comment('0:inactive | 1:active');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
            $table->index('referrer_id');
        });

        // 2. Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 4. Cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration')->index();
        });

        // 5. Cache Locks
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
        });

        // 6. Jobs
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // 7. Job Batches
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        // 8. Failed Jobs
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // 9. Wallets
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        // 10. Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->tinyInteger('type')->nullable()->comment('0:scratch_card | 1:bank');
            $table->tinyInteger('service_type')->nullable()->comment('0:topup | 1:buy_account');
            $table->tinyInteger('status')->default(0)->comment('0:pending | 1:success | 2:failed');
            $table->string('request_id', 100)->unique()->nullable();
            $table->string('provider', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 11. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('parent_id');
        });

        // 12. News
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->longText('content');
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->tinyInteger('published')->default(0)->comment('0:draft | 1:published');
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });

        // 13. Services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->integer('used_count')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0:inactive | 1:active');
            $table->timestamp('created_at')->useCurrent();
        });

        // 14. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->longText('content')->nullable();
            $table->decimal('sell_price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->tinyInteger('type')->nullable()->comment('1:account | 2:extra');
            $table->tinyInteger('status')->default(0)->comment('0:unsold | 1:sold');
            $table->json('images')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('password2')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 15. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_name', 255)->unique();
            $table->text('setting_value')->nullable();
            $table->timestamps();
        });

        // 16. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Mã coupon');
            $table->string('description', 255)->nullable()->comment('Mô tả coupon');
            $table->tinyInteger('discount_type')->comment('1:percentage | 2:fixed_amount');
            $table->decimal('discount_value', 15, 2)->comment('Giá trị giảm (% hoặc số tiền)');
            $table->decimal('max_discount', 15, 2)->nullable()->comment('Giảm tối đa (cho % discount)');
            $table->decimal('min_order_amount', 15, 2)->default(0)->comment('Giá trị đơn hàng tối thiểu');
            $table->integer('usage_limit')->nullable()->comment('Số lần sử dụng tối đa (null = không giới hạn)');
            $table->integer('usage_count')->default(0)->comment('Số lần đã sử dụng');
            $table->integer('usage_per_user')->default(1)->comment('Số lần 1 user được dùng');
            $table->tinyInteger('status')->default(1)->comment('0:inactive | 1:active');
            $table->decimal('excluded_min_price', 15, 2)->nullable()->comment('Giá tối thiểu sản phẩm bị loại trừ');
            $table->decimal('excluded_max_price', 15, 2)->nullable()->comment('Giá tối đa sản phẩm bị loại trừ');
            $table->timestamp('start_date')->nullable()->comment('Ngày bắt đầu');
            $table->timestamp('end_date')->nullable()->comment('Ngày hết hạn');
            $table->timestamps();
        });

        // 17. Coupon Usage
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            $table->decimal('discount_amount', 15, 2)->comment('Số tiền đã giảm');
            $table->timestamp('used_at')->useCurrent();

            $table->index(['coupon_id', 'user_id']);
        });

        // 18. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->decimal('product_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2);
            $table->tinyInteger('status')->default(0)->comment('0:pending | 1:completed | 2:cancelled | 3:refunded');
            $table->decimal('wallet_balance_before', 15, 2)->nullable();
            $table->decimal('wallet_balance_after', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->index('order_number');
            $table->index('status');
        });

        // 19. Banners
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->integer('sort')->default(0);
            $table->timestamps();
        });


        // 21. Affiliate Commissions
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id')->comment('Who receives commission');
            $table->unsignedBigInteger('referred_user_id')->comment('Who made the purchase');
            $table->unsignedBigInteger('order_id')->comment('Related order');
            $table->decimal('order_amount', 15, 2)->comment('Original order amount');
            $table->decimal('commission_amount', 15, 2)->comment('5% commission');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->index(['referrer_id', 'status']);
            $table->index('order_id');
        });

        // 22. Coupon Excluded Categories
        Schema::create('coupon_excluded_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->unique(['coupon_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key constraints to drop tables in any order without errors
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('coupon_excluded_categories');
        Schema::dropIfExists('affiliate_commissions');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('news');
        Schema::dropIfExists('services');

        Schema::enableForeignKeyConstraints();
    }
};
