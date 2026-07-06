@extends('layouts.app')

@section('title', $job->name)

@section('content')
<div class="max-w-3xl mx-auto flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-2">
            <a href="{{ route('jobs.index') }}" class="text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">Job Details</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('jobs.edit', $job) }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md border border-[#19140035] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors shadow-2xs">
                Edit Job
            </a>
            <form action="{{ route('jobs.destroy', $job) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this job listing?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-black dark:hover:bg-white dark:hover:text-[#1C1C1A] transition-colors shadow-sm">
                    Delete Job
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#19140035] dark:border-[#3E3E3A] overflow-hidden shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">

        {{-- Header Banner --}}
        <div class="px-6 py-6 border-b border-[#19140035] dark:border-[#3E3E3A] bg-gray-50/50 dark:bg-gray-800/10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $job->name }}</h2>
                <div class="mt-1 flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#fff2f2] dark:bg-[#1D0002] text-[#f53003] dark:text-[#FF4433]">
                        {{ $job->position->name }}
                    </span>
                    <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Job ID: #{{ $job->id }}</span>
                </div>
            </div>
            <div>
                @if ($job->isExpired())
                    <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-md text-xs font-medium">Inactive</span>
                @else
                    <span class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300 px-3 py-1 rounded-md text-xs font-medium">Active</span>
                @endif
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">State</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $job->state ?: 'Not specified' }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">City / Location</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $job->city ?: 'Not specified' }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Required Experience</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $job->experience ?? 'Not specified' }}</span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Plan Duration</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $job->plan_duration ?? 'Not specified' }}</span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Start Date</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $job->start_date->format('F d, Y') }}</span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">End Date</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $job->end_date ? $job->end_date->format('F d, Y') : 'Ongoing / No Deadline' }}
                </span>
            </div>


        </div>
    </div>
</div>
@endsection
