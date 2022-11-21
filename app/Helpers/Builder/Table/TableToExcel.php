<?php

namespace App\Helpers\Builder\Table;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Alert;
use App\ReportLayoutColumn;
use App\Seller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TableToExcel
{
    /** @var TableBuilder  */
    private $tableBuilder;

    /** @var int  */
    private $current_line = 1;

    /** @var string */
    private $fileName;

    /** @var int  */
    const HEADER_FOOTER_ROW_HEIGHT = 30;

    /** @var string  */
    const EUR_FORMAT = '#,##0.00 €';

    public function __construct(TableBuilder $tableBuilder, string $fileName)
    {
        $this->tableBuilder = $tableBuilder;
        $this->fileName = $fileName;
    }

    public function exportToExcel()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $this->addHeaderLine($sheet);

            $cell_number = 2;
            foreach ($this->tableBuilder->getLines() as $line) {
                $this->addLine($sheet, $line, $cell_number);
                $cell_number++;
            }

            $absolute_filepath = tempnam('/tmp', 'seller_perform');
            if (!$absolute_filepath)
                throw new \Exception('Failed to create temporary file named : ' . 'TableToExcel/');

            $writer = new Xlsx($spreadsheet);
            $writer->save($absolute_filepath);
            return response()->download($absolute_filepath, $this->fileName);
        }
        catch (\Exception $e) {
            Alert::modalError($e->getMessage());
            return redirect()->back();
        }

    }

    protected function addHeaderLine(Worksheet $sheet) {
        foreach ($this->tableBuilder->getColumns() as $key => $column) {
            if ($column->getType() == ColumnTypeEnum::ACTIONS)
                continue;

            $cell_letter = self::getLetterFromNumber($key);
            $cell_id = $cell_letter . $this->current_line;

            // Set background color
            $sheet->getStyle($cell_id)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('25446b');

            // Set text color
            $sheet->getStyle($cell_id)
                ->getFont()
                ->getColor()
                ->setRGB('ffffff');

            // Set text alignment
            $sheet->getStyle($cell_id)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Autosize cell width
            $sheet->getColumnDimension($cell_letter)->setAutoSize(true);

            // Set row height
            $sheet->getRowDimension($this->current_line)->setRowHeight(self::HEADER_FOOTER_ROW_HEIGHT);

            // Set cell content
            $cell_text = $column->getLabel();
            $sheet->setCellValue($cell_id, $cell_text);
        }

        $this->current_line++;
    }

    protected function addLine(Worksheet $sheet, $line, $cell_number)
    {
        foreach ($this->tableBuilder->getColumns() as $key => $column) {
            if ($column->getType() == ColumnTypeEnum::ACTIONS)
                continue;

            $cell_letter = self::getLetterFromNumber($key);
            $cell_id = $cell_letter . $cell_number;
            $cell = $sheet->getCell($cell_id);

            switch ($column->getType()) {
                case ColumnTypeEnum::DATE:
                    $cell->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);
                    $cell->getStyle()
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    break;
                case ColumnTypeEnum::PRICE:
                    $cell->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(self::EUR_FORMAT);
                    break;
            }

            $content = strip_tags($column->getValue($line, true));
            $sheet->setCellValue($cell_id, $content);
        }
    }

    /**
     * Get Excel column letter from number
     * @param $num
     * @return string
     */
    public static function getLetterFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return self::getLetterFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

}
