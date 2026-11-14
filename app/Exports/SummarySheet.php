<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function array(): array
    {
        // Build a query with filters (same as data sheet)
        $query = Member::query();
        // Apply filters (same as in MembersDataSheet) – we could reuse a trait, but for brevity, repeat the logic.
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

        $totalMembers = $query->count();

        // Counts by membership type
        $membershipTypes = \App\Models\MembershipType::all();
        $typeCounts = [];
        foreach ($membershipTypes as $type) {
            $typeCounts[] = [$type->name, $query->clone()->where('membership_type_id', $type->id)->count()];
        }

        // Counts by status
        $statusCounts = [
            ['Active', $query->clone()->where('status', 'Active')->count()],
            ['Inactive', $query->clone()->where('status', 'Inactive')->count()],
        ];

        // Counts by marital status
        $maritalStatuses = \App\Models\MaritalStatus::all();
        $maritalCounts = [];
        foreach ($maritalStatuses as $status) {
            $maritalCounts[] = [$status->name, $query->clone()->where('marital_status_id', $status->id)->count()];
        }

        // Employment stats
        $employed = $query->clone()->where('is_employed', true)->count();
        $notEmployed = $query->clone()->where('is_employed', false)->count();

        return [
            ['SUMMARY STATISTICS'],
            [],
            ['Total Members', $totalMembers],
            [],
            ['By Membership Type'],
            ['Type', 'Count'],
            ...$typeCounts,
            [],
            ['By Status'],
            ['Status', 'Count'],
            ...$statusCounts,
            [],
            ['By Marital Status'],
            ['Marital Status', 'Count'],
            ...$maritalCounts,
            [],
            ['Employment'],
            ['Employed', $employed],
            ['Not Employed', $notEmployed],
        ];
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style main title
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F4E79']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Style total members row
        $sheet->getStyle('A3:B3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);

        // Style section headers
        $sectionHeaders = ['A5', 'A9', 'A14', 'A19'];
        foreach ($sectionHeaders as $cell) {
            $sheet->getStyle($cell)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9E1F2']],
            ]);
        }

        // Style sub-headers (Type, Count, etc.)
        $sheet->getStyle('A6:B6')->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('A10:B10')->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('A15:B15')->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Apply borders to all data cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A6:B' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCCCCCC'],
                ],
            ],
        ]);

        // Align numbers to right
        $sheet->getStyle('B6:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }
}