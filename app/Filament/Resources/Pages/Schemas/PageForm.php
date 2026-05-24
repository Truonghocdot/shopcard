<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        $locales = config('locales.supported', ['en' => 'English']);
        $defaultLocale = config('locales.default', 'en');
        $siteName = config('app.name', 'Rabby TCG');

        return $schema->components([
            Tabs::make('translations')
                ->columnSpanFull()
                ->tabs(
                    collect($locales)->map(function (string $label, string $locale) use ($defaultLocale, $siteName) {
                        return Tab::make(strtoupper($locale))
                            ->label($label)
                            ->schema([
                                TextInput::make("title.{$locale}")
                                    ->label(__('filament.page_title'))
                                    ->required($locale === $defaultLocale)
                                    ->maxLength(255)
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, callable $set, callable $get) use ($locale, $defaultLocale, $siteName) {
                                        if (blank($state)) {
                                            return;
                                        }

                                        if ($locale === $defaultLocale && blank($get('slug'))) {
                                            $set('slug', self::generateUniqueSlug($state));
                                        }

                                        if (blank(data_get($get('meta_title'), $locale))) {
                                            $set("meta_title.{$locale}", self::generateMetaTitle($state, $siteName));
                                        }

                                        if (blank(data_get($get('meta_description'), $locale))) {
                                            $set("meta_description.{$locale}", self::generateMetaDescription($state, $siteName));
                                        }
                                    }),

                                RichEditor::make("content.{$locale}")
                                    ->label(__('filament.field_content'))
                                    ->extraInputAttributes(['style' => 'min-height: 30vh;'])
                                    ->columnSpanFull(),

                                TextInput::make("meta_title.{$locale}")
                                    ->label(__('filament.page_meta_title'))
                                    ->helperText(__('filament.product_meta_title_help'))
                                    ->maxLength(255),

                                TextInput::make("meta_description.{$locale}")
                                    ->label(__('filament.page_meta_description'))
                                    ->helperText(__('filament.product_meta_description_help'))
                                    ->maxLength(500),
                            ]);
                    })->values()->all()
                ),

            TextInput::make('slug')
                ->label(__('filament.field_slug'))
                ->required()
                ->maxLength(255)
                ->live()
                ->afterStateUpdated(function (?string $state, callable $set) {
                    if (blank($state)) {
                        return;
                    }

                    $set('slug', self::generateUniqueSlug($state));
                })
                ->unique(Page::class, 'slug', ignoreRecord: true),

            Select::make('status')
                ->label(__('filament.field_status'))
                ->options([
                    Page::STATUS_ACTIVE   => __('filament.field_active'),
                    Page::STATUS_INACTIVE => __('filament.field_inactive'),
                ])
                ->default(Page::STATUS_ACTIVE)
                ->required(),

            Checkbox::make('show_in_header')
                ->label(__('filament.page_show_in_header')),

            Checkbox::make('show_in_footer')
                ->label(__('filament.page_show_in_footer')),

            TextInput::make('sort_order')
                ->label(__('filament.page_sort_order'))
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }

    private static function generateUniqueSlug(string $value): string
    {
        $slug = str($value)->slug()->value();
        $baseSlug = $slug;
        $counter = 1;

        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
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
        return str("{$title} at {$siteName}. Read more details, company information, and policy content here.")
            ->limit(500, '')
            ->value();
    }
}
