@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
<div class="max-w-2xl mx-auto flex flex-col gap-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('positions.index') }}" class="text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">Edit Position</h1>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#19140035] dark:border-[#3E3E3A] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">
        <form action="{{ route('positions.update', $position) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="flex flex-col gap-2">
                <label for="name" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    Position Name <span class="text-[#f53003] dark:text-[#FF4433]">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $position->name) }}"
                    placeholder="e.g. Senior Software Engineer"
                    class="w-full px-3 py-2 rounded-md border @error('name') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:outline-none transition-colors"
                    required
                    autofocus
                >
                @error('name')
                    <span class="text-xs font-medium text-[#f53003] dark:text-[#FF4433]">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-[#19140035] dark:border-[#3E3E3A]">
                <a 
                    href="{{ route('positions.index') }}" 
                    class="px-4 py-2 text-sm font-medium rounded-md border border-[#19140035] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-sm font-medium rounded-md text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-black dark:hover:bg-white dark:hover:text-[#1C1C1A] transition-colors shadow-sm"
                >
                    Update Position
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
