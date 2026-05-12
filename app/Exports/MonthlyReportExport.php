<?php

namespace App\Exports;

use App\Models\MonthlyMealSummary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        private int $year,
        private int $month
    ) {}

    public function collection()
    {
        return MonthlyMealSummary::with('user')
            ->where('year', $this->year)
            ->where('month', $this->month)
            ->orderByDesc('total_meals')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Employee ID',
            'Name',
            'Email',
            'Department',
            'Breakfast',
            'Lunch',
            'Dinner',
            'Total Meals',
            'Meal Cost (' . config('meal.currency_symbol') . ')',
            'Bazar Contribution (' . config('meal.currency_symbol') . ')',
            'Balance (' . config('meal.currency_symbol') . ')',
        ];
    }

    public function map($summary): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $summary->user->employee_id ?? '',
            $summary->user->name,
            $summary->user->email,
            $summary->user->department ?? '',
            $summary->breakfast_meals,
            $summary->lunch_meals,
            $summary->dinner_meals,
            number_format($summary->total_meals, 1),
            number_format($summary->total_cost, 2),
            number_format($summary->bazar_contribution, 2),
            number_format($summary->balance ?? 0, 2),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E40AF']]],
        ];
    }

    public function title(): string
    {
        return date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year));
    }
}
