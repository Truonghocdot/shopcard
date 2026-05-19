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
                    ->label('Thứ tự')
                    ->required()
                    ->numeric()
                    ->validationMessages([
                        'required' => 'Thứ tự không được để trống',
                        'numeric' => 'Thứ tự phải là số',
                    ]),
                HandlesWebpUploads::processImageUpload(
                    FileUpload::make('image')
                        ->required()
                        ->label('Hình ảnh')
                        ->disk('public')
                        ->directory('banners')
                        ->image()
                        ->validationMessages([
                            'required' => 'Hình ảnh không được để trống',
                        ])
                ),
            ]);
    }
}
