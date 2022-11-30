<?php

namespace App\Helpers\Builder\Table\Features;

use App\Enums\TableBuilder\ExportTypeEnum;
use App\Helpers\Builder\PDF\PDFBuilder;
use App\Helpers\Builder\Table\TableToExcel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait Exportable
{
    private bool $is_exportable         = true;
    private ?string $file_name          = null;
    private ExportTypeEnum $export_type = ExportTypeEnum::XLSX;

    abstract public function getIdentifier(): string;
    abstract public function getRequest(): Request;

    public function setExportable(bool $is_exportable): static
    {
        $this->is_exportable = $is_exportable;
        return $this;
    }

    public function isExportable(): bool
    {
        return $this->is_exportable;
    }

    public function getFileName(): ?string
    {
        return $this->file_name ?: $this->getExportAction();
    }

    public function setFileName(?string $file_name): static
    {
        $this->file_name = $file_name;
        return $this;
    }

    public function needsExport(): bool
    {
        return $this->isExportable() && $this->getRequest()->exists($this->getExportAction());
    }

    public function export(): null|BinaryFileResponse|RedirectResponse
    {
        $this->setPaginable(false);
        $this->setSearchOptions($this->getRequest());
        $this->executeQuery();

        switch ($this->export_type) {
            case ExportTypeEnum::XLSX:
                $tableToExcel = new TableToExcel($this, $this->getFileName() . '.xlsx');
                return $tableToExcel->exportToExcel();
            case ExportTypeEnum::PDF:
                $view = $this->render();
                PDFBuilder::load($view)->download();
                break;
        }

        return null;
    }

    public function getExportAction() : string
    {
        return 'export_' . $this->getIdentifier();
    }
}
