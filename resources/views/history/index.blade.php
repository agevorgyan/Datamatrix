@extends('layouts.app')

@section('content')
<div class="space-y-6 relative pb-20">

    <!-- Page Title & Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                <span>📜 Տպագրությունների Պատմություն</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Կառավարեք ներբեռնված CSV ֆայլերի և լեյբլների տպագրության պատմությունը
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Export CSV Button -->
            <a href="{{ route('history.export-csv') }}" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Արտահանել CSV (Export)</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('history.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-6">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Որոնել ըստ ապրանքի կամ ֆայլի անվանման..." 
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div class="md:col-span-4">
                <select name="status" class="w-full px-4 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Բոլոր կարգավիճակները</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Ավարտված (Completed)</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Սպասող (Pending)</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🔄 Ընթացքի մեջ (In Progress)</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-emerald-400 font-bold text-xs rounded-lg transition">
                    Ֆիլտրել
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('history.index') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium text-xs rounded-lg transition">
                        Մաքրել
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form id="batchDeleteForm" method="POST" action="{{ route('history.delete-batch') }}">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-100 text-slate-800 font-bold uppercase text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="p-4 w-10 text-center">
                                <input type="checkbox" id="selectAllCheckbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            </th>
                            <th class="p-4">ID</th>
                            <th class="p-4">Ապրանքի Անվանում</th>
                            <th class="p-4">Ֆայլի Անվանում</th>
                            <th class="p-4">Կոդեր</th>
                            <th class="p-4">Կարգավիճակ (Status Badge)</th>
                            <th class="p-4">Ամսաթիվ</th>
                            <th class="p-4 text-right">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jobs as $job)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $job->id }}" class="job-checkbox rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                </td>
                                <td class="p-4 font-mono text-slate-500 font-bold">#{{ $job->id }}</td>
                                <td class="p-4 font-bold text-slate-900">{{ $job->product_name }}</td>
                                <td class="p-4 font-mono text-slate-600">{{ $job->file_name }}</td>
                                <td class="p-4 font-semibold text-slate-800">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200">
                                        {{ number_format($job->total_codes) }}
                                    </span>
                                </td>

                                <!-- Visual Status Badges Component -->
                                <td class="p-4">
                                    @if($job->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                            Ավարտված
                                        </span>
                                    @elseif($job->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5"></span>
                                            Սպասող
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300">
                                            <span class="w-2 h-2 rounded-full bg-sky-500 mr-1.5 animate-spin"></span>
                                            Ընթացքի մեջ
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-slate-500 font-medium">
                                    {{ $job->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td class="p-4 text-right space-x-1">
                                    <a href="{{ route('history.show', $job->id) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition border border-slate-200">
                                        👁️ Դիտել
                                    </a>
                                    <a href="{{ route('dashboard.print', $job->id) }}" target="_blank" class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg font-bold transition border border-emerald-200">
                                        🖨️ Տպել
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 italic">
                                    Տպագրության պատմություն չի գտնվել:
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Pagination Links -->
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $jobs->links() }}
        </div>
    </div>

    <!-- STICKY FLOATING BULK ACTIONS BAR (SLIDES UP WHEN CHECKBOXES ARE SELECTED) -->
    <div id="floatingBulkBar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 backdrop-blur-md text-white px-6 py-3.5 rounded-2xl shadow-2xl border border-slate-700 flex items-center space-x-6 transition-all duration-300 transform translate-y-32 opacity-0 pointer-events-none">
        <div class="flex items-center space-x-3">
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></span>
            <span id="selectedCountBadge" class="text-sm font-bold text-emerald-400">0 ընտրված</span>
        </div>

        <div class="h-5 w-px bg-slate-700"></div>

        <div class="flex items-center space-x-3">
            <button type="button" id="floatingDeleteBtn" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <span>Ջնջել Ընտրվածները</span>
            </button>

            <a href="{{ route('history.export-csv') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
                📥 Export CSV
            </a>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const jobCheckboxes = document.querySelectorAll('.job-checkbox');
    const floatingBulkBar = document.getElementById('floatingBulkBar');
    const selectedCountBadge = document.getElementById('selectedCountBadge');
    const floatingDeleteBtn = document.getElementById('floatingDeleteBtn');
    const batchDeleteForm = document.getElementById('batchDeleteForm');

    selectAllCheckbox.addEventListener('change', function () {
        jobCheckboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkBarState();
    });

    jobCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBarState);
    });

    function updateBulkBarState() {
        const checkedCount = document.querySelectorAll('.job-checkbox:checked').length;
        if (checkedCount > 0) {
            selectedCountBadge.textContent = `${checkedCount} ընտրված գրանցում`;
            floatingBulkBar.classList.remove('translate-y-32', 'opacity-0', 'pointer-events-none');
        } else {
            floatingBulkBar.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
        }
    }

    floatingDeleteBtn.addEventListener('click', function () {
        const checkedCount = document.querySelectorAll('.job-checkbox:checked').length;
        if (checkedCount === 0) return;

        if (confirm(`Վստա՞հ եք, որ ցանկանում եք ջնջել ընտրված ${checkedCount} տպագրության պատմության գրանցումները:`)) {
            batchDeleteForm.submit();
        }
    });
});
</script>
@endsection
