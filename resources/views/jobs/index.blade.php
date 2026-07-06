@extends('layouts.app')

@section('title', 'Jobs')

@section('content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">Jobs</h1>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Browse, create, and manage active job listings.</p>
        </div>
        <a href="{{ route('jobs.create') }}"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-black dark:hover:bg-white dark:hover:text-[#1C1C1A] transition-colors shadow-sm">
            Add Job
        </a>
    </div>

    {{-- Live Filters (no submit button — AJAX driven) --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#19140035] dark:border-[#3E3E3A] p-4 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Name search --}}
            <div class="flex flex-col gap-1 flex-1">
                <label for="filter_name" class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Search by Name</label>
                <input type="text" id="filter_name" placeholder="Search Name..."
                    class="px-3 py-2 rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] text-sm text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
            </div>

            {{-- Position filter --}}
            <div class="flex flex-col gap-1 flex-1">
                <label for="filter_position" class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Filter by Position</label>
                <select id="filter_position"
                    class="px-3 py-2 rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] text-sm text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                    <option value="">All Positions</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status filter --}}
            <div class="flex flex-col gap-1 flex-1">
                <label for="filter_status" class="text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Filter by Status</label>
                <select id="filter_status"
                    class="px-3 py-2 rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] text-sm text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] focus:outline-none transition-colors">
                    <option value="">All</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#19140035] dark:border-[#3E3E3A] overflow-hidden shadow-[0px_1px_2px_0px_rgba(0,0,0,0.06)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/40 text-xs font-semibold uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A] border-b border-[#19140035] dark:border-[#3E3E3A]">
                    <tr>
                        <th class="px-5 py-4">Name</th>
                        <th class="px-5 py-4">Position</th>

                        <th class="px-5 py-4">Start Date</th>
                        <th class="px-5 py-4">End Date</th>
                        <th class="px-5 py-4">Plan</th>
                        <th class="px-5 py-4">Countday</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="jobs-tbody" class="divide-y divide-[#19140035] dark:divide-[#3E3E3A]">
                    @include('jobs._table', ['jobs' => $jobs])
                </tbody>
            </table>
        </div>

        {{-- Pagination wrapper — swapped out by AJAX --}}
        <div id="jobs-pagination">
            @if ($jobs->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/20 border-t border-[#19140035] dark:border-[#3E3E3A]">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity" style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 9999; background-color: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center;">
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-xl max-w-sm w-full mx-4 border border-[#19140035] dark:border-[#3E3E3A] overflow-hidden transform scale-95 transition-transform origin-center" style="max-width: 400px; width: 100%;">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#f53003] dark:text-[#FF4433]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Delete Job</h3>
                </div>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Are you sure you want to delete this record? This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/40 flex justify-end gap-3 border-t border-[#19140035] dark:border-[#3E3E3A]">
                <button type="button" id="cancel-delete-btn" class="px-4 py-2 text-sm font-medium rounded-md border border-[#19140035] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] hover:bg-white dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="button" id="confirm-delete-btn" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-black dark:hover:bg-white dark:hover:text-[#1C1C1A] transition-colors shadow-sm">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    const nameInput     = document.getElementById('filter_name');
    const positionInput = document.getElementById('filter_position');
    const statusInput   = document.getElementById('filter_status');
    const tbody         = document.getElementById('jobs-tbody');
    const paginationDiv = document.getElementById('jobs-pagination');

    let debounceTimer = null;
    let currentRequest = null; // track in-flight request to abort if superseded

    /**
     * Build the query string from current filter values.
     */
    function buildParams(page) {
        const params = new URLSearchParams();
        const name   = nameInput.value.trim();
        const pos    = positionInput.value;
        const status = statusInput.value;

        if (name)   params.set('name', name);
        if (pos)    params.set('position_id', pos);
        if (status) params.set('status', status);
        if (page)   params.set('page', page);

        return params.toString();
    }

    /**
     * Fetch filtered results from the server via AJAX.
     */
    function fetchResults(page) {
        // Abort any previous in-flight request
        if (currentRequest) currentRequest.abort();

        const controller = new AbortController();
        currentRequest   = controller;

        const qs  = buildParams(page);
        const url = "{{ route('jobs.index') }}" + (qs ? '?' + qs : '');

        fetch(url, {
            signal: controller.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            tbody.innerHTML         = data.rows;
            paginationDiv.innerHTML = data.pagination
                ? '<div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/20 border-t border-[#19140035] dark:border-[#3E3E3A]">' + data.pagination + '</div>'
                : '';

            // Re-bind pagination links to AJAX
            bindPaginationLinks();
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Filter request failed:', err);
            }
        })
        .finally(() => {
            if (currentRequest === controller) currentRequest = null;
        });
    }

    /**
     * Debounce wrapper — used for the name text input only.
     */
    function debounce(fn, delay) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    /**
     * Intercept Laravel pagination links and turn them into AJAX calls.
     */
    function bindPaginationLinks() {
        paginationDiv.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url    = new URL(this.href);
                const page   = url.searchParams.get('page') || 1;
                fetchResults(page);
            });
        });
    }

    // ── Event Listeners ─────────────────────────────────────────────────────
    nameInput.addEventListener('input', debounce(() => fetchResults(), 350));
    positionInput.addEventListener('change', () => fetchResults());
    statusInput.addEventListener('change', () => fetchResults());

    // Bind any pagination links that exist on initial page load
    bindPaginationLinks();

    // ── AJAX Delete via Modal ───────────────────────────────────────────────
    const deleteModal = document.getElementById('delete-modal');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    let deleteFormToSubmit = null;
    let deleteRequestController = null;

    function openModal() {
        // Ensure modal is attached directly to body to avoid CSS transform/relative clipping issues
        if (deleteModal.parentNode !== document.body) {
            document.body.appendChild(deleteModal);
        }
        
        deleteModal.classList.remove('hidden');
        deleteModal.style.display = 'flex';
        // small delay to allow display:block before adding scale-100
        setTimeout(() => {
            deleteModal.querySelector('div').classList.remove('scale-95');
            deleteModal.querySelector('div').classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        deleteModal.querySelector('div').classList.remove('scale-100');
        deleteModal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            deleteModal.style.display = 'none';
            deleteModal.classList.add('hidden');
            deleteFormToSubmit = null;
        }, 150); // wait for transition
    }

    // Event delegation for dynamically added delete buttons in the table
    tbody.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-btn');
        if (btn) {
            e.preventDefault();
            deleteFormToSubmit = btn.closest('form.delete-job-form');
            openModal();
        }
    });

    cancelDeleteBtn.addEventListener('click', closeModal);

    // Close on backdrop click
    deleteModal.addEventListener('click', function (e) {
        if (e.target === deleteModal) closeModal();
    });

    confirmDeleteBtn.addEventListener('click', function () {
        if (!deleteFormToSubmit) return;

        // Abort previous delete if one is hanging
        if (deleteRequestController) deleteRequestController.abort();

        deleteRequestController = new AbortController();
        const url = deleteFormToSubmit.action;
        const csrfToken = deleteFormToSubmit.querySelector('input[name="_token"]').value;
        const method = deleteFormToSubmit.querySelector('input[name="_method"]').value || 'POST';

        // Disable button to prevent double-click
        const origText = confirmDeleteBtn.textContent;
        confirmDeleteBtn.textContent = 'Deleting...';
        confirmDeleteBtn.disabled = true;

        fetch(url, {
            method: 'POST',
            body: new URLSearchParams({ _token: csrfToken, _method: method }),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            signal: deleteRequestController.signal
        })
        .then(res => {
            if (!res.ok) throw new Error('Delete failed');
            return res.json();
        })
        .then(data => {
            closeModal();
            // Show a simple success toast/alert
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 z-50 bg-emerald-50 text-emerald-800 border border-emerald-200 px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in-down';
            
            // Add inline styles to guarantee rendering even if Tailwind JIT hasn't compiled these classes
            successDiv.style.cssText = 'position: fixed; top: 1.5rem; right: 1.5rem; z-index: 99999; background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 0.75rem 1.25rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; font-weight: 500; transition: opacity 0.5s ease;';
            
            successDiv.innerHTML = '<svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem; color: #10b981;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' + (data.message || 'Job deleted successfully.');
            document.body.appendChild(successDiv);
            
            setTimeout(() => {
                successDiv.style.opacity = '0';
                setTimeout(() => successDiv.remove(), 500);
            }, 3000);

            // Refresh table
            const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
            fetchResults(currentPage);
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error(err);
                alert('Error deleting record.');
            }
        })
        .finally(() => {
            confirmDeleteBtn.textContent = origText;
            confirmDeleteBtn.disabled = false;
            deleteRequestController = null;
        });
    });

})();
</script>
@endsection
