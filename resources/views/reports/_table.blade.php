@forelse ($monthlyData as $data)
    <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1b1b18]/30 transition-colors border-b border-[#19140035] dark:border-[#3E3E3A]">
        <td class="px-5 py-4 font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ \Carbon\Carbon::createFromFormat('Y-m', $data['month_year'])->format('F Y') }}
        </td>
        <td class="px-5 py-4 text-[#706f6c] dark:text-[#A1A09A]">
            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium bg-[#f53003]/10 text-[#f53003] dark:bg-[#FF4433]/20 dark:text-[#FF4433]">
                {{ $data['total_jobs'] }} {{ Str::plural('Job', $data['total_jobs']) }}
            </span>
        </td>
        <td class="px-5 py-4 font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
            ₹{{ number_format($data['total_payment'] ?? 0, 2) }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
            <svg class="w-10 h-10 mx-auto mb-3 text-[#706f6c]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            No job data found for this period.
        </td>
    </tr>
@endforelse
