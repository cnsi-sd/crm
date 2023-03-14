<?php

namespace App\Trait;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Enums\CrmDocumentTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Builder\Table\TableColumnBuilder;
use Illuminate\Http\Request;
use Cnsi\Attachments\Model\Document;
use Cnsi\Attachments\Trait\Documentable;

trait CrmDocumentable
{
    use Documentable {
        Documentable::getDocumentsTable as getCommentsDocumentsTable;
    }

    public function getCommentsDocumentsTable(Request $request, string $upload_document_route)
    {
        $columns = [
            (new TableColumnBuilder())
                ->setLabel(trans_choice('attachments::attachments.user', 1))
                ->setType(ColumnTypeEnum::TEXT)
                ->setKey('name')
                ->setAlign(AlignEnum::CENTER)
                ->setCallback(function (Document $document) {
                    return $document->user ? $document->user->name : '';
                }),
            (new TableColumnBuilder())
                ->setLabel(trans_choice('attachments::attachments.document.type', 1))
                ->setType(ColumnTypeEnum::TEXT)
                ->setKey('type')
                ->setAlign(AlignEnum::CENTER)
                ->setCallback(function (Document $document) {
                    return CrmDocumentTypeEnum::getMessage($document->type);
                }),
            (new TableColumnBuilder())
                ->setLabel(__('attachments::attachments.document.created_at'))
                ->setType(ColumnTypeEnum::TEXT)
                ->setKey('created_at')
                ->setAlign(AlignEnum::CENTER)
                ->setCallback(function (Document $document) {
                    return $document->created_at->format('d/m/y H:i');
                }),
            TableColumnBuilder::actions()
                ->setFixedWidth(FixedWidthEnum::LG)
                ->setCallback(function(Document $document) {
                    return view('attachments::documents_inline')
                        ->with('document', $document);
                }),
        ];

        $table = (new TableBuilder('documents', $request))
            ->setColumns($columns)
            ->setExportable(false)
            ->setSortable(false)
            ->setSearchable(false)
            ->setPaginable(false)
            ->setQuery($this->documents()->getQuery());

        return view('attachments::documents_table')
            ->with('table', $table)
            ->with('upload_document_route', $upload_document_route)
            ->with('documentable_type', get_class($this))
            ->with('documentable_id', $this->getKey())
            ->with('documentable', $this)
            ->with('allowed_types', $this->getAllowedDocumentTypes());
    }
}
