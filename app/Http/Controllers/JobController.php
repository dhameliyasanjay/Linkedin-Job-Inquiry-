<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * Display a listing of the resource with optional live AJAX filters.
     *
     * Supports three query parameters: name, position_id, status.
     * When called via AJAX (X-Requested-With: XMLHttpRequest), returns
     * only the table partial + pagination HTML instead of the full page.
     */
    public function index(Request $request): View|JsonResponse
    {
        $positions = Position::orderBy('name')->get();

        $today = now()->toDateString();

        $jobs = Job::with('position')
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($request->filled('position_id'), function ($q) use ($request) {
                $q->where('position_id', $request->position_id);
            })
            ->when($request->filled('status'), function ($q) use ($request, $today) {
                if ($request->status === 'Active') {
                    // Active: end_date is null OR end_date >= today
                    $q->where(function ($q) use ($today) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $today);
                    });
                } elseif ($request->status === 'Inactive') {
                    // Inactive: end_date is in the past
                    $q->whereNotNull('end_date')
                      ->where('end_date', '<', $today);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // AJAX: return only the tbody rows + pagination, no full layout
        if ($request->ajax()) {
            $rows       = view('jobs._table', compact('jobs'))->render();
            $pagination = $jobs->hasPages()
                ? $jobs->links()->toHtml()
                : '';

            return response()->json([
                'rows'       => $rows,
                'pagination' => $pagination,
            ]);
        }

        return view('jobs.index', compact('jobs', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $positions = Position::orderBy('name')->get();
        return view('jobs.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Compute end_date server-side from start_date + plan_duration
        $data['end_date'] = $this->computeEndDate($data['start_date'], $data['plan_duration']);

        // New jobs are always Active
        $data['status'] = 'Active';

        Job::create($data);

        return redirect()->route('jobs.index')
            ->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): View
    {
        $job->load('position');
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job): View
    {
        $positions = Position::orderBy('name')->get();
        $job->load('position');
        return view('jobs.edit', compact('job', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        $data = $request->validated();

        // Recompute end_date from start_date + plan_duration
        $data['end_date'] = $this->computeEndDate($data['start_date'], $data['plan_duration']);

        $job->update($data);

        return redirect()->route('jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Returns JSON for AJAX requests, redirect for standard requests.
     */
    public function destroy(Job $job): RedirectResponse|JsonResponse
    {
        $job->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Job deleted successfully.']);
        }

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    /**
     * Compute end date from start date and plan duration string.
     * Handles different month lengths and leap years via Carbon.
     */
    private function computeEndDate(string $startDate, string $planDuration): string
    {
        $start = Carbon::parse($startDate);

        $end = match ($planDuration) {
            '1 Month'  => (clone $start)->addMonths(1)->subDay(),
            '2 Months' => (clone $start)->addMonths(2)->subDay(),
            '3 Months' => (clone $start)->addMonths(3)->subDay(),
            '6 Months' => (clone $start)->addMonths(6)->subDay(),
            '1 Year'   => (clone $start)->addYear()->subDay(),
            default    => (clone $start)->addMonth()->subDay(),
        };

        return $end->toDateString();
    }
}
