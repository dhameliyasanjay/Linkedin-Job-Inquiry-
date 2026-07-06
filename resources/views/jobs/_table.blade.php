{{-- Partial: jobs/_table.blade.php
     Rendered for both initial page load and AJAX requests.
     Variables expected: $jobs (LengthAwarePaginator with 'position' eager loaded)
--}}
@forelse ($jobs as $job)
    @php 
        $expired = $job->isExpired(); 
        
        $totalDays = 0;
        $daysLeft = 0;
        if ($job->start_date && $job->end_date) {
            $totalDays = $job->start_date->diffInDays($job->end_date);
            $now = \Carbon\Carbon::now()->startOfDay();
            $endDate = $job->end_date->copy()->startOfDay();
            $startDate = $job->start_date->copy()->startOfDay();
            
            if ($now->greaterThan($endDate)) {
                $daysLeft = 0;
            } elseif ($now->lessThan($startDate)) {
                $daysLeft = $totalDays;
            } else {
                $daysLeft = $now->diffInDays($endDate);
            }
        }
    @endphp
    <tr class="{{ $expired ? 'bg-red-50 dark:bg-red-950/20' : 'hover:bg-gray-50/50 dark:hover:bg-[#1b1b18]/30' }} transition-colors">
        <td class="px-5 py-4 font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $job->name }}</td>
        <td class="px-5 py-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#fff2f2] dark:bg-[#1D0002] text-[#f53003] dark:text-[#FF4433]">
                {{ $job->position->name }}
            </span>
        </td>

        <td class="px-5 py-4 text-[#706f6c] dark:text-[#A1A09A]">{{ $job->start_date->format('d M Y') }}</td>
        <td class="px-5 py-4 text-[#706f6c] dark:text-[#A1A09A]">
            {{ $job->end_date ? $job->end_date->format('d M Y') : '—' }}
        </td>
        <td class="px-5 py-4 text-[#706f6c] dark:text-[#A1A09A]">{{ $job->plan_duration ?? '—' }}</td>
        <td class="px-5 py-4 text-[#706f6c] dark:text-[#A1A09A]">
            @if($job->start_date && $job->end_date)
                {{ $totalDays }}day/{{ $daysLeft }}day
            @else
                —
            @endif
        </td>
        <td class="px-5 py-4">
            @if ($expired)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">Inactive</span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400">Active</span>
            @endif
        </td>
        <td class="px-5 py-4 text-right">
            <div class="flex justify-end gap-3">
                <a href="{{ route('jobs.show', $job) }}" title="View" class="inline-flex items-center p-1.5 rounded-md text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 dark:text-emerald-400 transition-colors">
                    <svg class="w-5 h-5" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </a>
                <a href="{{ route('jobs.edit', $job) }}" title="Edit" class="inline-flex items-center p-1.5 rounded-md text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:text-blue-400 transition-colors">
                    <svg class="w-5 h-5" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </a>
                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline delete-job-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" title="Delete" class="inline-flex items-center p-1.5 rounded-md text-[#f53003] hover:bg-red-50 dark:hover:bg-red-900/30 dark:text-[#FF4433] transition-colors delete-btn">
                        <svg class="w-5 h-5 pointer-events-none" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
            <svg class="w-10 h-10 mx-auto mb-3 text-[#706f6c]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            No job listings found. Try adjusting your filters.
        </td>
    </tr>
@endforelse
