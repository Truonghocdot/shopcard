<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Filament\Traits\HandlesWebpUploads;
use App\Models\Banner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sort')
                    ->label(__('filament.banner_sort'))
                    ->required()
                    ->numeric()
                    ->validationMessages([
                        'required' => __('filament.banner_sort_required'),
                        'numeric' => __('filament.banner_sort_numeric'),
                    ]),
                HandlesWebpUploads::processImageUpload(
                    FileUpload::make('image')
                        ->required()
                        ->label(__('filament.banner_image'))
                        ->disk('public')
                        ->directory('banners')
                        ->image()
                        ->validationMessages([
                            'required' => __('filament.banner_image_required'),
                        ])
                ),
            ]);
    }
}
