<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Traits\HandlesWebpUploads;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('filament.cat_title'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => __('filament.required'),
                        'max_length' => __('field_max_length'),
                    ])
                    ->live()
                    ->debounce(1500)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $slug = str($state)->slug();
                        $slugOriginal = $slug;
                        $flag = 0;
                        while (Category::where('slug', $slug)->exists()) {
                            $slug = str($slugOriginal)->append('-' . $flag);
                            $flag++;
                        }
                        $set('slug', $slug);
                        $set('meta_title', $state);
                        $set('meta_description', $state);
                    }),
                TextInput::make('slug')
                    ->label(__('filament.cat_slug'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => __('filament.required'),
                        'max_length' => __('field_max_length'),
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $slug = str($state)->slug();
                        $slugOriginal = $slug;
                        $flag = 0;
                        while (Category::where('slug', $slug)->exists()) {
                            $slug = str($slugOriginal)->append('-' . $flag);
                            $flag++;
                        }
                        $set('slug', $slug);
                    }),
                Select::make('parent_id')
                    ->label(__('filament.cat_parent'))
                    ->options(Category::all()->pluck('title', 'id'))
                    ->nullable(),
                Textarea::make('description')
                    ->label(__('filament.cat_description'))
                    ->rows(5)
                    ->validationMessages([
                        'max_length' => __('field_max_length'),
                    ]),
                HandlesWebpUploads::processImageUpload(
                    FileUpload::make('image')
                        ->required()
                        ->label(__('filament.cat_image'))
                        ->disk('public')
                        ->directory('categories')
                        ->image()
                        ->validationMessages([
                            'required' => __('filament.required'),
                        ])
                ),
                TextInput::make('meta_title')
                    ->label(__('filament.cat_meta_title'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => __('filament.required'),
                        'max_length' => __('field_max_length'),
                    ]),
                TextInput::make('meta_description')
                    ->label(__('filament.cat_meta_desc'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => __('filament.required'),
                        'max_length' => __('field_max_length'),
                    ]),
            ]);
    }
}
