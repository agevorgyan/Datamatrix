@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner with KPI Analytics Widgets -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-9 w-auto object-contain">
                    <span class="text-xs uppercase font-bold tracking-wider text-emerald-400">DataMatrix Print Engine</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Դատամատրիքսի գեներացման և տպագրության համակարգ
                </h1>
                <p class="text-slate-300 text-xs md:text-sm mt-1 max-w-2xl">
                    Ներբեռնեք CSV ֆայլը, ստուգեք 5 տողերի preview-ն և տպեք լեյբլները ունիվերսալ ջերմային պրինտերներով։
                </p>
            </div>

            <!-- Widened Responsive KPI Metric Cards -->
            <div class="w-full md:w-auto min-w-[260px] sm:min-w-[340px]">
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="bg-white/10 backdrop-blur border border-white/10 px-5 py-3.5 rounded-xl text-center shadow-inner">
                        <div class="text-[11px] uppercase font-bold text-slate-300 tracking-wider">Տպված Լեյբլներ</div>
                        <div class="text-xl md:text-2xl font-black text-emerald-400 mt-1">{{ number_format($totalPrintedCodes) }}</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur border border-white/10 px-5 py-3.5 rounded-xl text-center shadow-inner">
                        <div class="text-[11px] uppercase font-bold text-slate-300 tracking-wider">Խմբաքանակներ</div>
                        <div class="text-xl md:text-2xl font-black text-sky-400 mt-1">{{ number_format($totalBatchesCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout: CSV Import (Left) and Active Settings & Recent History (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LEFT COLUMN: CSV Import & Preview (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- CSV Upload Form Box -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-md border border-slate-200">
                <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">
                        1
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">CSV Ֆայլի Ներբեռնում & Տվյալների Ստուգում</h2>
                </div>

                <form id="csvImportForm" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="product_name" class="block text-xs font-semibold uppercase text-slate-700 mb-1">
                            Ապրանքի Անվանում (Product Name) *
                        </label>
                        <input type="text" name="product_name" id="product_name" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-slate-800 transition"
                            placeholder="օրինակ՝ Հյութ Տրոպիկ 1Լ">
                        <span class="text-[11px] text-slate-400 mt-1 block">Այս անվանումը տպվելու է ֆայլի բոլոր DataMatrix լեյբլների վրա։</span>
                    </div>

                    <!-- File Dropzone -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-700 mb-1">
                            CSV Ֆայլ (Մեկ սյունյակով կոդեր) *
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-6 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-emerald-500 transition-colors bg-slate-50/50 relative cursor-pointer" id="dropzone">
                            <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" required class="absolute inset-0 opacity-0 w-full h-full cursor-pointer">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20L28 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M28 8v12h12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-sm text-slate-600">
                                    <span class="font-semibold text-emerald-600">Ընտրեք .CSV ֆայլը</span> կամ քաշեք այստեղ
                                </div>
                                <p class="text-xs text-slate-400" id="selectedFileName">Մաքսիմում 10,000 կոդ (CSV / TXT)</p>
                            </div>
                        </div>
                    </div>

                    <div id="previewLoading" class="hidden p-3 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-medium flex items-center space-x-2">
                        <svg class="animate-spin h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Կարդացվում է CSV ֆայլի տվյալների preview-ն...</span>
                    </div>

                    <!-- 5-Row Preview Table Component -->
                    <div id="csvPreviewCard" class="hidden border border-slate-200 rounded-xl p-4 bg-slate-50/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">🔍 Առաջին 5 տողերի նախնական ստուգում</span>
                            <span id="totalCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800"></span>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                            <table class="w-full text-left text-xs text-slate-700">
                                <thead class="bg-slate-100 text-slate-800 font-semibold uppercase text-[10px]">
                                    <tr>
                                        <th class="px-3 py-2 border-b">#</th>
                                        <th class="px-3 py-2 border-b">Բուն Կոդ (Full Code)</th>
                                        <th class="px-3 py-2 border-b text-emerald-700">Վերջին 5 նիշեր</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="divide-y divide-slate-100 font-mono">
                                    <!-- Dynamic Rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Process & Print Button -->
                    <button type="submit" id="submitBatchBtn" disabled
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 hover:from-emerald-500 hover:to-indigo-500 disabled:opacity-50 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition duration-200 flex items-center justify-center space-x-2">
                        <span>🖨️ Գեներացնել DataMatrix & Տպել</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: Active Settings Summary & Quick Links (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Active Label Settings Summary Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                        <span>📐 Ընթացիկ Լեյբլի Չափսեր</span>
                    </span>
                    <a href="{{ route('settings.edit') }}" class="text-xs text-emerald-600 font-bold hover:underline">
                        Փոխել &rarr;
                    </a>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Չափսեր (Լ x Բ)․</span>
                        <span class="font-bold text-slate-900">{{ $setting->width_mm }}մմ x {{ $setting->height_mm }}մմ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">DataMatrix Չափս․</span>
                        <span class="font-bold text-slate-900">{{ $setting->datamatrix_size }}մմ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ապրանքի Տառաչափ․</span>
                        <span class="font-bold text-slate-900">{{ $setting->product_font_size }}pt</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Վերջին 5 նիշերի Տառաչափ․</span>
                        <span class="font-bold text-slate-900">{{ $setting->last5_font_size }}pt</span>
                    </div>
                </div>

                <a href="{{ route('settings.edit') }}" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition border border-slate-300 block text-center">
                    ⚙️ Բացել Drag & Drop Դիզայները
                </a>
            </div>

            <!-- Recent Jobs Snippet -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200">
                <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center justify-between">
                    <span>⏱️ Վերջին Տպագրությունները</span>
                    <a href="{{ route('history.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Տեսնել բոլորը &rarr;</a>
                </h3>

                @if($recentJobs->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($recentJobs as $job)
                            <div class="py-3 flex items-center justify-between gap-3 text-xs overflow-hidden">
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-slate-900 truncate" title="{{ $job->product_name }}">
                                        {{ $job->product_name }}
                                    </div>
                                    <div class="text-slate-400 text-[11px] flex items-center space-x-1 min-w-0" title="{{ $job->file_name }}">
                                        <span class="font-mono max-w-[120px] sm:max-w-[160px] truncate block">{{ $job->file_name }}</span>
                                        <span class="shrink-0">• {{ number_format($job->total_codes) }} կոդ</span>
                                    </div>
                                </div>
                                <a href="{{ route('dashboard.print', $job->id) }}" target="_blank" class="shrink-0 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold rounded-lg transition border border-emerald-200 inline-flex items-center space-x-1 whitespace-nowrap">
                                    <span>🖨️</span>
                                    <span>Տպել</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Դեռ տպագրության պատմություն չկա:</p>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csvFileInput = document.getElementById('csv_file');
    const productNameInput = document.getElementById('product_name');
    const previewLoading = document.getElementById('previewLoading');
    const csvPreviewCard = document.getElementById('csvPreviewCard');
    const previewTableBody = document.getElementById('previewTableBody');
    const totalCountBadge = document.getElementById('totalCountBadge');
    const submitBatchBtn = document.getElementById('submitBatchBtn');
    const selectedFileName = document.getElementById('selectedFileName');
    const csvImportForm = document.getElementById('csvImportForm');

    // CSV File Upload Change Event -> Preview first 5 rows
    csvFileInput.addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;

        const file = this.files[0];
        selectedFileName.textContent = `Ընտրված է՝ ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        
        const formData = new FormData();
        formData.append('csv_file', file);
        formData.append('_token', '{{ csrf_token() }}');

        previewLoading.classList.remove('hidden');
        csvPreviewCard.classList.add('hidden');

        fetch('{{ route("dashboard.preview-csv") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            previewLoading.classList.add('hidden');
            if (data.success) {
                previewTableBody.innerHTML = '';
                data.preview_rows.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 transition';
                    tr.innerHTML = `
                        <td class="px-3 py-2 border-b font-mono font-bold text-slate-500">${row.row_num}</td>
                        <td class="px-3 py-2 border-b font-mono text-slate-900 break-all">${row.code}</td>
                        <td class="px-3 py-2 border-b font-mono font-bold text-emerald-700 bg-emerald-50/50">${row.last_5}</td>
                    `;
                    previewTableBody.appendChild(tr);
                });

                totalCountBadge.textContent = `Ընդհանուր՝ ${data.total_count} կոդ`;
                csvPreviewCard.classList.remove('hidden');
                validateSubmitState();
            } else {
                alert(data.message || 'CSV ֆայլի ստուգման սխալ:');
            }
        })
        .catch(err => {
            previewLoading.classList.add('hidden');
            console.error(err);
            alert('Ֆայլը կարդալու սխալ: Խնդրում ենք ստուգել .CSV ձևաչափը:');
        });
    });

    productNameInput.addEventListener('input', validateSubmitState);

    function validateSubmitState() {
        const hasFile = csvFileInput.files && csvFileInput.files.length > 0;
        const hasName = productNameInput.value.trim().length > 0;
        submitBatchBtn.disabled = !(hasFile && hasName);
    }

    // Process Batch Submission
    csvImportForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        submitBatchBtn.disabled = true;
        submitBatchBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Մշակվում է, խնդրում ենք սպասել...</span>
        `;

        const formData = new FormData(csvImportForm);

        fetch('{{ route("dashboard.store-batch") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.open(data.redirect_url, '_blank');
                window.location.reload();
            } else {
                alert(data.message || 'Սխալ՝ խմբաքանակի պահպանման ընթացքում:');
                submitBatchBtn.disabled = false;
                submitBatchBtn.innerHTML = `<span>🖨️ Գեներացնել DataMatrix & Տպել</span>`;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Տեղի ունեցավ սխալ backend հարցման ընթացքում:');
            submitBatchBtn.disabled = false;
            submitBatchBtn.innerHTML = `<span>🖨️ Գեներացնել DataMatrix & Տպել</span>`;
        });
    });
});
</script>
@endsection
