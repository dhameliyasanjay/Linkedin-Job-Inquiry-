<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all jobs' start dates and payment data, ignoring those without a start date
        $jobs = Job::select('id', 'start_date', 'payment')
            ->whereNotNull('start_date')
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Generate all 12 months for the current year to always show in the dropdown
        $availableMonths = collect(range(1, 12))->map(function ($month) {
            return now()->startOfYear()->addMonths($month - 1)->format('Y-m');
        });

        // Filter jobs if a specific month is requested
        if ($request->filled('month')) {
            $jobs = $jobs->filter(function ($job) use ($request) {
                return $job->start_date->format('Y-m') === $request->month;
            });
        }

        // Group the remaining jobs by month and count/sum them
        $monthlyData = $jobs->groupBy(function ($job) {
            return $job->start_date->format('Y-m');
        })->map(function ($group, $month) {
            $totalPayment = $group->sum(function($job) {
                // Extract numbers from strings like "50000 INR"
                return (float) filter_var($job->payment, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            });
            
            return [
                'month_year' => $month,
                'total_jobs' => $group->count(),
                'total_payment' => $totalPayment
            ];
        })->values();

        // If AJAX request, render just the table fragment
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('reports._table', compact('monthlyData'))->render()
            ]);
        }

        return view('reports.index', compact('availableMonths', 'monthlyData'));
    }
}
