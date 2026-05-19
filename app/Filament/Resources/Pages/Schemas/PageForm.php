<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('title')
                ->label(__('filament.page_title'))
                ->required()
                ->maxLength(255)
                ->live()
                ->afterStateUpdated(function (string $state, callable $set) {
                    $slug = str($state)->slug();
                    $base = $slug;
                    $i    = 0;
                    while (Page::where('slug', $slug)->exists()) {
                        $slug = $base . '-' . $i++;
                    }
                    $set('slug', $slug);
                }),

            TextInput::make('slug')
                ->label(__('filament.field_slug'))
                ->required()
                ->maxLength(255)
                ->unique(Page::class, 'slug', ignoreRecord: true),

            RichEditor::make('content')
                ->label(__('filament.field_content'))
                ->extraInputAttributes(['style' => 'min-height: 30vh;'])
                ->columnSpanFull(),

            TextInput::make('meta_title')
                ->label(__('filament.page_meta_title'))
                ->maxLength(255),

            TextInput::make('meta_description')
                ->label(__('filament.page_meta_description'))
                ->maxLength(500),

            Select::make('status')
                ->label(__('filament.field_status'))
                ->options([
                    Page::STATUS_ACTIVE   => __('filament.field_active'),
                    Page::STATUS_INACTIVE => __('filament.field_inactive'),
                ])
                ->default(Page::STATUS_ACTIVE)
                ->required(),
        ]);
    }
}
