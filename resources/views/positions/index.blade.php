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
                            <td class="px-6 py-4 text-right flex justify-end gap-3">
                                <a 
                                    href="{{ route('positions.edit', $position) }}" 
                                    title="Edit"
                                    class="inline-flex items-center p-1.5 rounded-md text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:text-blue-400 transition-colors"
                                >
                                    <svg class="w-5 h-5" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
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
                                        title="Delete"
                                        class="inline-flex items-center p-1.5 rounded-md text-[#f53003] hover:bg-red-50 dark:hover:bg-red-900/30 dark:text-[#FF4433] transition-colors"
                                    >
                                        <svg class="w-5 h-5 pointer-events-none" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
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
