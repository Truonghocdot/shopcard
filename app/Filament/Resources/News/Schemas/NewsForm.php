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
                    ->label(__('filament.news_title'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        "required" => __('filament.news_title_required'),
                        "max_length" => __('filament.news_title_max'),
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
                    ->label(__('filament.news_slug'))
                    ->required()
                    ->validationMessages([
                        "required" => __('filament.news_slug_required'),
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
                    ->label(__('filament.news_description'))
                    ->extraInputAttributes(['style' => 'min-height: 20vh;'])
                    ->required()
                    ->validationMessages([
                        "required" => __('filament.news_description_required'),
                    ]),

                RichEditor::make("content")
                    ->label(__('filament.news_content'))
                    ->extraInputAttributes(['style' => 'min-height: 20vh;'])
                    ->required()
                    ->validationMessages([
                        "required" => __('filament.news_content_required'),
                    ]),

                HandlesWebpUploads::processImageUpload(
                    FileUpload::make("thumbnail")
                        ->required()
                        ->label(__('filament.news_thumbnail'))
                        ->disk("public")
                        ->directory("news")
                        ->image()
                        ->validationMessages([
                            "required" => __('filament.news_thumbnail_required'),
                        ])
                ),
                TextInput::make("meta_title")
                    ->label(__('filament.news_meta_title'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        "required" => __('filament.news_meta_title_required'),
                        "max_length" => __('filament.news_meta_title_max'),
                    ]),
                TextInput::make("meta_description")
                    ->label(__('filament.news_meta_description'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        "required" => __('filament.news_meta_description_required'),
                        "max_length" => __('filament.news_meta_description_max'),
                    ]),
                Select::make("published")
                    ->label(__('filament.news_published'))
                    ->options([
                        0 => __('filament.news_published_draft'),
                        1 => __('filament.news_published_published'),
                    ])
                    ->required()
                    ->validationMessages([
                        "required" => __('filament.news_published_required'),
                    ])
                    ->default(1),
            ]);
    }
}
