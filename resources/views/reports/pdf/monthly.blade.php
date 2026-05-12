<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Report — {{ $report['month_year'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; padding: 30px; }
        .header { background: #1e40af; color: white; padding: 20px 25px; border-radius: 8px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.8; margin-top: 4px; }
        .summary { display: flex; gap: 15px; margin-bottom: 20px; }
        .summary-box { background: #f3f4f6; border-radius: 6px; padding: 12px; flex: 1; text-align: center; }
        .summary-box .val { font-size: 18px; font-weight: bold; color: #1e40af; }
        .summary-box .lbl { font-size: 9px; color: #6b7280; text-transform: uppercase; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1e40af; color: white; padding: 8px 10px; text-align: left; font-size: 10px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f9fafb; }
        tfoot td { background: #e5e7eb !important; font-weight: bold; }
        .positive { color: #059669; }
        .negative { color: #dc2626; }
        .finalized { color: #059669; font-weight: bold; }
        .draft { color: #d97706; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly Meal Report — {{ $report['month_year'] }}</h1>
        <p>Generated on {{ now()->format('d M Y, h:i A') }} · Status: <strong>{{ $report['mealCost']->is_finalized ? 'FINALIZED' : 'DRAFT' }}</strong></p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="val">{{ number_format($report['mealCost']->total_meals, 1) }}</div>
            <div class="lbl">Total Meals</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ config('meal.currency_symbol') }}{{ number_format($report['mealCost']->total_bazar_cost, 2) }}</div>
            <div class="lbl">Bazar Cost</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ config('meal.currency_symbol') }}{{ number_format($report['mealCost']->cost_per_meal, 2) }}</div>
            <div class="lbl">Cost Per Meal</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ $report['summaries']->count() }}</div>
            <div class="lbl">Members</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
                <th>Total</th>
                <th>Meal Cost</th>
                <th>Bazar</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['summaries'] as $i => $summary)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $summary->user->name }}</td>
                <td>{{ $summary->breakfast_meals }}</td>
                <td>{{ $summary->lunch_meals }}</td>
                <td>{{ $summary->dinner_meals }}</td>
                <td><strong>{{ number_format($summary->total_meals, 1) }}</strong></td>
                <td>{{ config('meal.currency_symbol') }}{{ number_format($summary->total_cost, 2) }}</td>
                <td>{{ config('meal.currency_symbol') }}{{ number_format($summary->bazar_contribution, 2) }}</td>
                <td class="{{ ($summary->balance ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    {{ ($summary->balance ?? 0) >= 0 ? '+' : '' }}{{ config('meal.currency_symbol') }}{{ number_format(abs($summary->balance ?? 0), 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td>{{ $report['summaries']->sum('breakfast_meals') }}</td>
                <td>{{ $report['summaries']->sum('lunch_meals') }}</td>
                <td>{{ $report['summaries']->sum('dinner_meals') }}</td>
                <td>{{ number_format($report['summaries']->sum('total_meals'), 1) }}</td>
                <td>{{ config('meal.currency_symbol') }}{{ number_format($report['summaries']->sum('total_cost'), 2) }}</td>
                <td>{{ config('meal.currency_symbol') }}{{ number_format($report['summaries']->sum('bazar_contribution'), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        {{ config('meal.company_name') }} · Meal Management System · Confidential
    </div>
</body>
</html>
