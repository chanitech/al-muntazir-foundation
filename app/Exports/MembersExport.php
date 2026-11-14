<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class MembersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithProperties, WithCustomStartCell, WithDrawings, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Member::with(['membershipType', 'maritalStatus']);

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('membership_number', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $this->filters['search'] . '%');
            });
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['type'])) {
            $query->where('membership_type_id', $this->filters['type']);
        }
        if (!empty($this->filters['marital_status'])) {
            $query->where('marital_status_id', $this->filters['marital_status']);
        }
        if (!empty($this->filters['employed'])) {
            $query->where('is_employed', $this->filters['employed']);
        }
        if (!empty($this->filters['employment_status'])) {
            $query->where('employment_status', $this->filters['employment_status']);
        }
        if (!empty($this->filters['region'])) {
            $query->where('current_region', 'like', '%' . $this->filters['region'] . '%');
        }
        if (!empty($this->filters['district'])) {
            $query->where('current_district', 'like', '%' . $this->filters['district'] . '%');
        }
        if (!empty($this->filters['village'])) {
            $query->where('current_village', 'like', '%' . $this->filters['village'] . '%');
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            '#',
            'Mem No',
            'Name',
            'Phone',
            'Email',
            'Type',
            'Marital',
            'Employment',
            'Region',
            'District',
            'Village',
            'Status',
            'Join Date',
        ];
    }

    public function map($member): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $employment = $member->is_employed
            ? ucfirst($member->employment_status)
            : 'Not employed';
        if ($member->is_employed && $member->employer_name) {
            $employment .= ' at ' . $member->employer_name;
        }

        return [
            $rowNumber,
            $member->membership_number,
            $member->full_name,
            $member->phone,
            $member->email ?? '-',
            $member->membershipType->name ?? '-',
            $member->maritalStatus->name ?? '-',
            $employment,
            $member->current_region ?? '-',
            $member->current_district ?? '-',
            $member->current_village ?? '-',
            $member->status,
            $member->join_date ? $member->join_date->format('Y-m-d') : '-',
        ];
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Organization Logo');
        $drawing->setPath(public_path('images/logo.jpg')); // Ensure file exists
        $drawing->setHeight(30);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(0);
        $drawing->setOffsetY(0);

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set white background behind logo
                $sheet->getStyle('A1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFFFFF');

                // Page setup: Portrait, A4, fit to width
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Zero margins
                $sheet->getPageMargins()->setLeft(0);
                $sheet->getPageMargins()->setRight(0);
                $sheet->getPageMargins()->setTop(0);
                $sheet->getPageMargins()->setBottom(0);
                $sheet->getPageMargins()->setHeader(0);
                $sheet->getPageMargins()->setFooter(0);

                // Ultra compact column widths
                $sheet->getColumnDimension('A')->setWidth(2.5);
                $sheet->getColumnDimension('B')->setWidth(6);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(7);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(6);
                $sheet->getColumnDimension('G')->setWidth(6);
                $sheet->getColumnDimension('H')->setWidth(16);
                $sheet->getColumnDimension('I')->setWidth(6);
                $sheet->getColumnDimension('J')->setWidth(6);
                $sheet->getColumnDimension('K')->setWidth(6);
                $sheet->getColumnDimension('L')->setWidth(4);
                $sheet->getColumnDimension('M')->setWidth(7);

                // Wrap text for all cells
                $sheet->getStyle('A:M')->getAlignment()->setWrapText(true);

                // Smaller font for data rows
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A7:M' . $highestRow)->getFont()->setSize(7);

                // Title at row 2
                $sheet->setCellValue('A2', 'AL MUNTADHIR MEMBERS REPORT');
                $sheet->mergeCells('A2:M2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F4E79']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Filter summary at row 3
                $filterText = $this->getFilterText();
                $sheet->setCellValue('A3', 'Filters: ' . $filterText);
                $sheet->mergeCells('A3:M3');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 7],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9E1F2']],
                    'alignment' => ['wrapText' => true],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(18);

                // Export date at row 4
                $sheet->setCellValue('A4', 'Exported on: ' . now()->format('F j, Y H:i:s'));
                $sheet->mergeCells('A4:M4');
                $sheet->getStyle('A4')->getFont()->setSize(6);

                // Empty row for spacing
                $sheet->getRowDimension(5)->setRowHeight(3);

                // Table header at row 6
                $sheet->getStyle('A6:M6')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF'], 'size' => 7],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F4E79']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(20);

                // Alternating row colors
                for ($row = 7; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':M' . $row)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF2F2F2');
                    }
                }

                // Borders
                $sheet->getStyle('A6:M' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'],
                        ],
                    ],
                ]);

                // Auto-filter
                $sheet->setAutoFilter('A6:M' . $highestRow);

                // Freeze header row
                $sheet->freezePane('A7');

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:M' . $highestRow);

                // No header/footer (save space)
                $sheet->getHeaderFooter()->setOddHeader('');
                $sheet->getHeaderFooter()->setOddFooter('');

                // Optional watermark – use a transparent PNG
                if (file_exists(public_path('images/watermark.png'))) {
                    $watermark = new Drawing();
                    $watermark->setName('Watermark');
                    $watermark->setPath(public_path('images/watermark.png')); // Fixed line
                    $watermark->setWidth(150);
                    $watermark->setCoordinates('D15');
                    $watermark->setWorksheet($sheet);
                }
            },
        ];
    }

    private function getFilterText(): string
    {
        if (empty($this->filters)) {
            return 'No filters applied (all members)';
        }
        $parts = [];
        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $parts[] = $label . ': ' . $value;
            }
        }
        return implode('; ', $parts);
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Al Muntadhir System',
            'lastModifiedBy' => 'Al Muntadhir System',
            'title'          => 'Members Report',
            'description'    => 'Filtered members list',
            'subject'        => 'Members Export',
            'keywords'       => 'members,export,report',
            'category'       => 'Reports',
            'company'        => 'Al Muntadhir',
        ];
    }
}