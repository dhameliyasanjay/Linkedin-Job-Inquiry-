@extends('layouts.app')

@section('title', 'Monthly Analytics')

@section('content')
<div class="max-w-6xl mx-auto flex flex-col gap-6">
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">Monthly Analytics</h1>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">View job posting trends month over month.</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#19140035] dark:border-[#3E3E3A] flex justify-end shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">
        <form id="filter-form" onsubmit="event.preventDefault(); fetchReports();">
            <div class="flex flex-col gap-2 w-64">
                <label for="month-filter" class="text-xs font-bold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Filter By Month</label>
                <select id="month-filter" name="month" class="w-full px-3 py-2 rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                    <option value="">All Months</option>
                    @foreach ($availableMonths as $month)
                        <option value="{{ $month }}" {{ request('month') === $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#19140035] dark:border-[#3E3E3A] overflow-hidden shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)] min-h-[400px]">
        <div class="overflow-x-auto relative h-full">
            <!-- Loading Overlay -->
            <div id="table-loader" class="absolute inset-0 bg-white/50 dark:bg-[#161615]/50 backdrop-blur-sm z-10 hidden flex-col items-center justify-start pt-16">
                <div class="w-6 h-6 border-2 border-[#1b1b18] dark:border-[#EDEDEC] border-t-transparent rounded-full animate-spin"></div>
            </div>
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 dark:bg-gray-800/20 text-xs uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A] border-b border-[#19140035] dark:border-[#3E3E3A]">
                    <tr>
                        <th scope="col" class="px-5 py-4 font-semibold">Month / Year</th>
                        <th scope="col" class="px-5 py-4 font-semibold">Total Jobs Created</th>
                        <th scope="col" class="px-5 py-4 font-semibold">Total Payment</th>
                    </tr>
                </thead>
                <tbody id="reports-tbody" class="divide-y divide-[#19140035] dark:divide-[#3E3E3A]">
                    @include('reports._table', ['monthlyData' => $monthlyData])
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const filterForm = document.getElementById('filter-form');
    const monthFilter = document.getElementById('month-filter');
    const reportsTbody = document.getElementById('reports-tbody');
    const tableLoader = document.getElementById('table-loader');
    const resetFilterBtn = document.getElementById('reset-filter');

    async function fetchReports() {
        // Show loader
        tableLoader.classList.remove('hidden');
        tableLoader.classList.add('flex');

        try {
            // Build query parameters
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            const url = `{{ route('reports.index') }}?${params}`;

            // Fetch AJAX response
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            
            // Update table body
            reportsTbody.innerHTML = data.html;
            
        } catch (error) {
            console.error('Error fetching reports:', error);
            alert('Failed to load reports data. Please try again.');
        } finally {
            // Hide loader
            tableLoader.classList.add('hidden');
            tableLoader.classList.remove('flex');
        }
    }

    monthFilter.addEventListener('change', fetchReports);
</script>
@endsection
