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
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Tiêu đề không được để trống',
                        'max_length' => 'Tiêu đề không được vượt quá 255 ký tự',
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
                    ->label('Đường dẫn')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Đường dẫn không được để trống',
                        'max_length' => 'Đường dẫn không được vượt quá 255 ký tự',
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
                    ->label('Danh mục cha')
                    ->options(Category::all()->pluck('title', 'id'))
                    ->nullable(),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->required()
                    ->rows(5)
                    ->validationMessages([
                        'required' => 'Mô tả không được để trống',
                        'max_length' => 'Mô tả không được vượt quá 255 ký tự',
                    ]),
                HandlesWebpUploads::processImageUpload(
                    FileUpload::make('image')
                        ->required()
                        ->label('Hình ảnh')
                        ->disk('public')
                        ->directory('categories')
                        ->image()
                        ->validationMessages([
                            'required' => 'Hình ảnh không được để trống',
                        ])
                ),
                TextInput::make('meta_title')
                    ->label('Tiêu đề SEO')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Tiêu đề SEO không được để trống',
                        'max_length' => 'Tiêu đề SEO không được vượt quá 255 ký tự',
                    ]),
                TextInput::make('meta_description')
                    ->label('Mô tả SEO')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Mô tả SEO không được để trống',
                        'max_length' => 'Mô tả SEO không được vượt quá 255 ký tự',
                    ]),
            ]);
    }
}
