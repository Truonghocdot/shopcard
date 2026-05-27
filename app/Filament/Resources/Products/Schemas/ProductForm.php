<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Constants\CardCondition;
use App\Constants\CardField;
use App\Constants\CardGrading;
use App\Constants\CardLanguage;
use App\Constants\CardType;
use App\Filament\Traits\HandlesWebpUploads;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $siteName = config('app.name', 'Rabby TCG');

        return $schema->components([

            // ── Basic Info ───────────────────────────────────────────────────
            Section::make(__('filament.product_section_basic'))
                ->columns(2)
                ->schema([
                    Select::make('category_id')
                        ->label(__('card_category'))
                        ->relationship('category', 'title')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('title')
                        ->label(__('card_title'))
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->debounce(1500)
                        ->afterStateUpdated(function (?string $state, callable $set, callable $get) use ($siteName) {
                            if (blank($state)) {
                                return;
                            }

                            if (blank($get('meta_title'))) {
                                $set('meta_title', self::generateMetaTitle($state, $siteName));
                            }

                            if (blank($get('meta_description'))) {
                                $set('meta_description', self::generateMetaDescription($state, $siteName));
                            }
                        }),

                    Select::make(CardField::TYPE->value)
                        ->label(__('filament.card_type'))
                        ->options(CardType::options())
                        ->default(CardType::SINGLE->value)
                        ->required(),

                    TextInput::make('quantity')
                        ->label(__('filament.product_quantity'))
                        ->numeric()
                        ->default(1)
                        ->minValue(0)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $quantity = (int) ($state ?? 0);
                            $set('status', $quantity > 0 ? Product::STATUS_UNSOLD : Product::STATUS_SOLD);
                        })
                        ->required(),

                    HandlesWebpUploads::processImageUpload(
                        FileUpload::make('images')
                            ->label(__('card_images'))
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->required()
                            ->panelLayout('grid')
                            ->columnSpanFull()
                    ),
                ]),

            // ── TCG Card Details ─────────────────────────────────────────────
            Section::make(__('filament.product_section_tcg'))
                ->columns(2)
                ->schema([
                    Select::make(CardField::CONDITION->value)
                        ->label(__('card_condition'))
                        ->options(CardCondition::options())
                        ->placeholder('— Select condition —'),

                    Select::make(CardField::LANGUAGE->value)
                        ->label(__('card_language'))
                        ->options(CardLanguage::options())
                        ->placeholder('— Select language —'),

                    TextInput::make(CardField::SET->value)
                        ->label(__('card_set_expansion')),

                    TextInput::make(CardField::RARITY->value)
                        ->label(__('card_rarity')),

                    Select::make(CardField::GRADING->value)
                        ->label(__('filament.card_grading'))
                        ->options(CardGrading::options())
                        ->placeholder('— Select grading company —')
                        ->live(),

                    TextInput::make(CardField::GRADE->value)
                        ->label(__('filament.card_grade'))
                        ->placeholder('e.g. 10 / 9.5 / 9'),

                    TextInput::make(CardField::CERT->value)
                        ->label(__('psa_cert_serial'))
                        ->placeholder('e.g. 12345678')
                        ->columnSpanFull(),
                ]),

            // ── Pricing ──────────────────────────────────────────────────────
            Section::make(__('filament.product_section_pricing'))
                ->columns(2)
                ->schema([
                    TextInput::make('sell_price')
                        ->label(__('regular_price'))
                        ->numeric()
                        ->required()
                        ->minValue(0),

                    TextInput::make('sale_price')
                        ->label(__('discounted_price'))
                        ->numeric()
                        ->minValue(0),

                    Select::make('status')
                        ->label(__('status'))
                        ->options([
                            Product::STATUS_UNSOLD => __('filament.field_active'),
                            Product::STATUS_SOLD => __('filament.field_inactive'),
                        ])
                        ->default(Product::STATUS_UNSOLD)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ((int) $state === Product::STATUS_SOLD && (int) $get('quantity') > 0) {
                                $set('quantity', 0);
                            }

                            if ((int) $state === Product::STATUS_UNSOLD && (int) $get('quantity') <= 0) {
                                $set('quantity', 1);
                            }
                        })
                        ->required(),
                ]),

            // ── Description ──────────────────────────────────────────────────
            Section::make(__('filament.product_section_description'))
                ->schema([
                    RichEditor::make('content')
                        ->label(__('card_details'))
                        ->extraInputAttributes(['style' => 'min-height: 20vh;']),
                ]),

            // ── SEO ──────────────────────────────────────────────────────────
            Section::make(__('filament.product_section_seo'))
                ->columns(2)
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label(__('meta_title_label'))
                        ->helperText(__('filament.product_meta_title_help'))
                        ->maxLength(255),

                    TextInput::make('meta_description')
                        ->label(__('meta_desc_label'))
                        ->helperText(__('filament.product_meta_description_help'))
                        ->maxLength(500),
                ]),
        ]);
    }

    private static function generateMetaTitle(string $title, string $siteName): string
    {
        return str($title)
            ->append(' - ' . $siteName)
            ->limit(255, '')
            ->value();
    }

    private static function generateMetaDescription(string $title, string $siteName): string
    {
        return str("{$title} available at {$siteName}. Explore authentic TCG cards, sealed products, and collector highlights.")
            ->limit(500, '')
            ->value();
    }
}
