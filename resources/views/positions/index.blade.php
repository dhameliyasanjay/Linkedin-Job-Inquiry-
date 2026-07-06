@extends('layouts.app')

@section('title', 'Positions')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">Positions</h1>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage all organizational job roles and positions.</p>
        </div>
        <a 
            href="{{ route('positions.create') }}" 
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-black dark:hover:bg-white dark:hover:text-[#1C1C1A] transition-colors shadow-sm"
        >
            Add Position
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#19140035] dark:border-[#3E3E3A] overflow-hidden shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/40 text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A] border-b border-[#19140035] dark:border-[#3E3E3A]">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Position Name</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#19140035] dark:divide-[#3E3E3A]">
                    @forelse ($positions as $position)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1b1b18]/30 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                #{{ $position->id }}
                            </td>
                            <td class="px-6 py-4 font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $position->name }}
                            </td>
                            <td class="px-6 py-4 text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $position->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-3">
                                <a 
                                    href="{{ route('positions.edit', $position) }}" 
                                    class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                >
                                    Edit
                                </a>
                                <form 
                                    action="{{ route('positions.destroy', $position) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this position? All associated jobs will also be deleted.')"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center text-xs font-medium text-[#f53003] hover:text-[#f53003]/85 dark:text-[#FF4433] dark:hover:text-[#FF4433]/85 transition-colors"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                <svg class="w-10 h-10 mx-auto mb-3 text-[#706f6c]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                No positions found. Click "Add Position" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if ($positions->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/20 border-t border-[#19140035] dark:border-[#3E3E3A]">
                {{ $positions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
