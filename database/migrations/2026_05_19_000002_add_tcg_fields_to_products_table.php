<?php

use App\Constants\CardField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string(CardField::CONDITION->value, 10)->nullable()
                ->after('password2');

            $table->string(CardField::LANGUAGE->value, 50)->nullable()
                ->after(CardField::CONDITION->value);

            $table->string(CardField::SET->value, 255)->nullable()
                ->after(CardField::LANGUAGE->value);

            $table->string(CardField::RARITY->value, 100)->nullable()
                ->after(CardField::SET->value);

            $table->string(CardField::GRADING->value, 20)->nullable()
                ->after(CardField::RARITY->value);

            $table->string(CardField::GRADE->value, 10)->nullable()
                ->after(CardField::GRADING->value);

            $table->string(CardField::CERT->value, 100)->nullable()
                ->after(CardField::GRADE->value);

            $table->tinyInteger(CardField::TYPE->value)->default(1)
                ->after(CardField::CERT->value);
        });

        // Migrate existing data from generic columns → proper TCG columns
        DB::statement(sprintf(
            "UPDATE products SET %s = phone, %s = `password`, %s = email, %s = password2, %s = username
             WHERE phone IS NOT NULL OR `password` IS NOT NULL OR email IS NOT NULL OR password2 IS NOT NULL OR username IS NOT NULL",
            CardField::CONDITION->value,
            CardField::LANGUAGE->value,
            CardField::SET->value,
            CardField::RARITY->value,
            CardField::CERT->value,
        ));
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(array_column(CardField::cases(), 'value'));
        });
    }
};
