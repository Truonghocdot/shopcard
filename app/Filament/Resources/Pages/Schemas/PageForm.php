<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        $locales = config('locales.supported', ['en' => 'English']);

        return $schema->components([
            Tabs::make('translations')
                ->columnSpanFull()
                ->tabs(
                    collect($locales)->map(function (string $label, string $locale) {
                        return Tabs\Tab::make(strtoupper($locale))
                            ->label($label)
                            ->schema([
                                TextInput::make("title.{$locale}")
                                    ->label(__('filament.page_title'))
                                    ->required($locale === config('locales.default', 'en'))
                                    ->maxLength(255)
                                    ->live($locale === config('locales.default', 'en'))
                                    ->afterStateUpdated(function (?string $state, callable $set, callable $get) use ($locale) {
                                        if ($locale !== config('locales.default', 'en')) {
                                            return;
                                        }

                                        if (blank($state) || filled($get('slug'))) {
                                            return;
                                        }

                                        $slug = str($state)->slug();
                                        $base = $slug;
                                        $i = 1;

                                        while (Page::where('slug', $slug)->exists()) {
                                            $slug = $base . '-' . $i++;
                                        }

                                        $set('slug', $slug);
                                    }),

                                RichEditor::make("content.{$locale}")
                                    ->label(__('filament.field_content'))
                                    ->extraInputAttributes(['style' => 'min-height: 30vh;'])
                                    ->columnSpanFull(),

                                TextInput::make("meta_title.{$locale}")
                                    ->label(__('filament.page_meta_title'))
                                    ->maxLength(255),

                                TextInput::make("meta_description.{$locale}")
                                    ->label(__('filament.page_meta_description'))
                                    ->maxLength(500),
                            ]);
                    })->values()->all()
                ),

            TextInput::make('slug')
                ->label(__('filament.field_slug'))
                ->required()
                ->maxLength(255)
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
}
