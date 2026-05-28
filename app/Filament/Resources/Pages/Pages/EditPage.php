<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Models\Page;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Page $record */
        $record = $this->record;

        $data['title'] = $record->getRawTranslation('title');
        $data['content'] = $record->getRawTranslation('content');
        $data['meta_title'] = $record->getRawTranslation('meta_title');
        $data['meta_description'] = $record->getRawTranslation('meta_description');

        return $data;
    }
}
