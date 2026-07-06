@extends('layouts.app')

@section('title', 'Add Job')

@section('content')
<div class="max-w-3xl mx-auto flex flex-col gap-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('jobs.index') }}" class="text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">Post a New Job</h1>
    </div>

    <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#19140035] dark:border-[#3E3E3A] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">
        <form action="{{ route('jobs.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf

            {{-- Job Title + Position --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-2">
                    <label for="name" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Name <span class="text-[#f53003]">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Enter Developer Name"
                        class="w-full px-3 py-2 rounded-md border @error('name') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors" required>
                    @error('name')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="position_id" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Position <span class="text-[#f53003]">*</span></label>
                    <select id="position_id" name="position_id" required
                        class="w-full px-3 py-2 rounded-md border @error('position_id') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                        <option value="">Select a Position</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                        @endforeach
                    </select>
                    @error('position_id')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- State + City + Experience --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col gap-2">
                    <label for="state" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">State <span class="text-[#f53003]">*</span></label>
                    <select id="state" name="state" required
                        class="w-full px-3 py-2 rounded-md border @error('state') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                        <option value="">Select State</option>
                    </select>
                    @error('state')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="city" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-between">
                        <span>City <span class="text-[#f53003]">*</span></span>
                        <span id="city-loader" class="hidden text-xs text-[#f53003] animate-pulse">Loading…</span>
                    </label>
                    <select id="city" name="city" required disabled
                        class="w-full px-3 py-2 rounded-md border @error('city') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">Select a state first</option>
                    </select>
                    @error('city')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>
                
                <div class="flex flex-col gap-2">
                    <label for="experience" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Experience</label>
                    <input type="text" id="experience" name="experience" value="{{ old('experience') }}" placeholder="e.g. 2-4 Years"
                        class="w-full px-3 py-2 rounded-md border @error('experience') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                    @error('experience')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Plan Duration + Start Date + End Date (readonly, auto) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col gap-2">
                    <label for="plan_duration" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Plan Duration <span class="text-[#f53003]">*</span></label>
                    <select id="plan_duration" name="plan_duration" required
                        class="w-full px-3 py-2 rounded-md border @error('plan_duration') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                        <option value="">Select Duration</option>
                        @foreach (['1 Month','2 Months','3 Months','6 Months','1 Year'] as $dur)
                            <option value="{{ $dur }}" {{ old('plan_duration') === $dur ? 'selected' : '' }}>{{ $dur }}</option>
                        @endforeach
                    </select>
                    @error('plan_duration')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="start_date" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Start Date <span class="text-[#f53003]">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                        onclick="try{this.showPicker();}catch(e){}"
                        class="w-full px-3 py-2 rounded-md border @error('start_date') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors cursor-pointer" required>
                    @error('start_date')<span class="text-xs font-medium text-[#f53003]">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="end_date" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-between">
                        <span>End Date</span><span class="text-xs text-[#706f6c] dark:text-[#A1A09A] font-normal italic">Auto-calculated</span>
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" readonly
                        class="w-full px-3 py-2 rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-gray-50 dark:bg-[#0a0a0a]/60 text-[#706f6c] dark:text-[#A1A09A] focus:outline-none cursor-not-allowed opacity-75">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-[#19140035] dark:border-[#3E3E3A]">
                <a href="{{ route('jobs.index') }}" class="px-4 py-2 text-sm font-medium rounded-md border border-[#19140035] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-black dark:hover:bg-white dark:hover:text-[#1C1C1A] transition-colors shadow-sm">Post Job</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stateSelect = document.getElementById('state');
    const citySelect  = document.getElementById('city');
    const cityLoader  = document.getElementById('city-loader');

    const oldState = "{{ old('state') }}";
    const oldCity  = "{{ old('city') }}";

    // Load all Indian states into the state dropdown
    fetch("{{ route('api.indian-states') }}")
        .then(r => r.json())
        .then(states => {
            states.forEach(state => {
                const opt    = document.createElement('option');
                opt.value    = state;
                opt.textContent = state;
                if (state === oldState) opt.selected = true;
                stateSelect.appendChild(opt);
            });
            // If old state present (validation failure), load its cities
            if (oldState) loadCities(oldState, oldCity);
        })
        .catch(e => console.error('State load error:', e));

    stateSelect.addEventListener('change', function () {
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled  = true;
        if (this.value) loadCities(this.value);
    });

    function loadCities(state, selectedCity) {
        cityLoader.classList.remove('hidden');
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Loading cities…</option>';

        const url = "{{ route('api.indian-cities-by-state', ':state') }}".replace(':state', encodeURIComponent(state));

        fetch(url)
            .then(r => r.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                cities.forEach(city => {
                    const opt    = document.createElement('option');
                    opt.value    = city;
                    opt.textContent = city;
                    if (city === selectedCity) opt.selected = true;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            })
            .catch(() => {
                citySelect.innerHTML = '<option value="">Failed to load cities</option>';
            })
            .finally(() => cityLoader.classList.add('hidden'));
    }

    // ── Auto End-Date Calculation ─────────────────────────────────────────────
    const startInput = document.getElementById('start_date');
    const planSelect = document.getElementById('plan_duration');
    const endInput   = document.getElementById('end_date');

    function calcEndDate() {
        if (!startInput.value || !planSelect.value) { endInput.value = ''; return; }
        const s = new Date(startInput.value);
        if (isNaN(s)) return;
        const e = new Date(s);
        const months = {'1 Month':1,'2 Months':2,'3 Months':3,'6 Months':6};
        if (planSelect.value === '1 Year') { e.setFullYear(e.getFullYear() + 1); }
        else if (months[planSelect.value]) { e.setMonth(e.getMonth() + months[planSelect.value]); }
        e.setDate(e.getDate() - 1);
        endInput.value = e.toISOString().split('T')[0];
    }

    startInput.addEventListener('change', calcEndDate);
    planSelect.addEventListener('change', calcEndDate);
    calcEndDate();
});
</script>
@endsection
