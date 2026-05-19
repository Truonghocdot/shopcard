<?php

namespace App\Filament\Resources\News\Schemas;

use App\Filament\Traits\HandlesWebpUploads;
use App\Models\News;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("title")
                    ->label("Tiêu đề")
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        "required" => "Tiêu đề không được để trống",
                        "max_length" => "Tiêu đề không được vượt quá 255 ký tự",
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $slug = str($state)->slug();
                        $slugOriginal = $slug;
                        $flag = 0;
                        while (News::where('slug', $slug)->exists()) {
                            $slug = str($slugOriginal)->append('-' . $flag);
                            $flag++;
                        }
                        $set('slug', $slug);
                    }),
                TextInput::make("slug")
                    ->label("Slug")
                    ->required()
                    ->validationMessages([
                        "required" => "Slug không được để trống",
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $slug = str($state)->slug();
                        $slugOriginal = $slug;
                        $flag = 0;
                        while (News::where('slug', $slug)->exists()) {
                            $slug = str($slugOriginal)->append('-' . $flag);
                            $flag++;
                        }
                        $set('slug', $slug);
                    }),
                RichEditor::make("description")
                    ->label("Mô tả")
                    ->extraInputAttributes(['style' => 'min-height: 20vh;'])
                    ->required()
                    ->validationMessages([
                        "required" => "Mô tả không được để trống",
                    ]),

                RichEditor::make("content")
                    ->label("Nội dung")
                    ->extraInputAttributes(['style' => 'min-height: 20vh;'])
                    ->required()
                    ->validationMessages([
                        "required" => "Nội dung không được để trống",
                    ]),

                HandlesWebpUploads::processImageUpload(
                    FileUpload::make("thumbnail")
                        ->required()
                        ->label("Hình ảnh")
                        ->disk("public")
                        ->directory("news")
                        ->image()
                        ->validationMessages([
                            "required" => "Hình ảnh không được để trống",
                        ])
                ),
                TextInput::make("meta_title")
                    ->label("Tiêu đề SEO")
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        "required" => "Tiêu đề SEO không được để trống",
                        "max_length" => "Tiêu đề SEO không được vượt quá 255 ký tự",
                    ]),
                TextInput::make("meta_description")
                    ->label("Mô tả SEO")
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        "required" => "Mô tả SEO không được để trống",
                        "max_length" => "Mô tả SEO không được vượt quá 255 ký tự",
                    ]),
                Select::make("published")
                    ->label("Ngày đăng")
                    ->options([
                        0 => "Bản nháp",
                        1 => "Đã xuất bản",
                    ])
                    ->required()
                    ->validationMessages([
                        "required" => "Ngày đăng không được để trống",
                    ])
                    ->default(1),
                TextInput::make("view_count")
                    ->label("Lượt xem")
                    ->required()
                    ->validationMessages([
                        "required" => "Lượt xem không được để trống",
                    ])
                    ->default(0),
            ]);
    }
}
