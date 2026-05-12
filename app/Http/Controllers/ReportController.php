<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReportService;
use App\Exports\MonthlyReportExport;
use App\Exports\UserMealExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function monthly(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $report = $this->reportService->generateMonthlyReport($year, $month);
        $years = range(now()->year, 2020);

        return view('reports.monthly', compact('report', 'years', 'year', 'month'));
    }

    public function userWise(Request $request)
    {
        $userIdParam = $request->get('user_id');
        $userId = $userIdParam ? decryptId($userIdParam) : auth()->id();
        $year = $request->get('year', now()->year);

        $report = $this->reportService->generateUserAnnualReport($userId, $year);
        $users = User::active()->orderBy('name')->get();
        $years = range(now()->year, 2020);

        return view('reports.user-wise', compact('report', 'users', 'years', 'year', 'userId'));
    }

    public function exportMonthlyPdf(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $report = $this->reportService->generateMonthlyReport($year, $month);

        $pdf = Pdf::loadView('reports.pdf.monthly', compact('report'))
            ->setPaper('a4', 'portrait');

        $filename = "monthly-report-{$year}-{$month}.pdf";

        return $pdf->download($filename);
    }

    public function exportMonthlyExcel(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $filename = "monthly-report-{$year}-{$month}.xlsx";

        return Excel::download(new MonthlyReportExport($year, $month), $filename);
    }

    public function exportUserPdf(Request $request)
    {
        $userIdParam = $request->get('user_id');
        $userId = $userIdParam ? decryptId($userIdParam) : auth()->id();
        $year = $request->get('year', now()->year);

        $report = $this->reportService->generateUserAnnualReport($userId, $year);

        $pdf = Pdf::loadView('reports.pdf.user-wise', compact('report'))
            ->setPaper('a4', 'portrait');

        $filename = "user-report-{$report['user']->name}-{$year}.pdf";

        return $pdf->download($filename);
    }
}
