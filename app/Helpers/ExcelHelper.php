<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Storage;

class ExcelHelper
{
    public static function generateLowStockExcel($lowStockItems)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Low Stock Report');
        
        $sheet->setCellValue('A1', 'Product Name');
        $sheet->setCellValue('B1', 'Variant Name');
        $sheet->setCellValue('C1', 'Color');
        $sheet->setCellValue('D1', 'Remaining Quantity');
        $sheet->setCellValue('E1', 'Status');
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($lowStockItems as $item) {
            $sheet->setCellValue('A' . $row, $item['product_name']);
            $sheet->setCellValue('B' . $row, $item['variant_name']);
            $sheet->setCellValue('C' . $row, $item['color_name']);
            $sheet->setCellValue('D' . $row, $item['remaining_qty']);
            $sheet->setCellValue('E' . $row, 'Low Stock');
            
            if ($item['remaining_qty'] <= 2) {
                $sheet->getStyle('D' . $row)->getFont()->setBold(true)->getColor()->setRGB('DC2626');
            } elseif ($item['remaining_qty'] <= 5) {
                $sheet->getStyle('D' . $row)->getFont()->setBold(true)->getColor()->setRGB('F97316');
            }
            
            if ($item['color_hex'] && $item['color_hex'] != 'N/A') {
                $sheet->getStyle('C' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(ltrim($item['color_hex'], '#'));
            }
            $row++;
        }
        
        foreach(range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $tempPath = storage_path('app/temp/low_stock_' . time() . '.xlsx');
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);
        
        return $tempPath;
    }
}