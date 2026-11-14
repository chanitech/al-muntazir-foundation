<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MembersDataSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Member::with(['membershipType', 'maritalStatus']);

        // Apply filters (same as index)
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

    public function title(): string
    {
        return 'Members Data';
    }

    public function headings(): array
    {
        return [
            '#',
            'Membership No',
            'Full Name',
            'Phone',
            'Email',
            'Membership Type',
            'Marital Status',
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
            ? ucfirst($member->employment_status) . ' at ' . $member->employer_name
            : 'Not employed';

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

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 25,
            'D' => 15,
            'E' => 25,
            'F' => 18,
            'G' => 15,
            'H' => 30,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 12,
            'M' => 12,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1F4E79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(20);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Apply borders to all data
                $sheet->getStyle('A1:M' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'],
                        ],
                    ],
                ]);

                // Alternating row colors (starting from row 2)
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':M' . $row)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF2F2F2');
                    }
                }

                // Auto-filter
                $sheet->setAutoFilter('A1:M1');

                // Freeze header row
                $sheet->freezePane('A2');

                // Wrap text for employment column
                $sheet->getStyle('H2:H' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}