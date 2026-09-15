@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    ⚙️ Լեյբլի Ձևաչափի Կարգավորումներ
                </h1>
                <p class="text-slate-300 text-xs md:text-sm mt-1 max-w-2xl">
                    Կարգավորեք լեյբլի չափսերը (մմ), Orientation (Portrait/Landscape), Margins (Լուսանցքներ), Scale (Մասշտաբ %), Print DPI (203/300/600), Label Gap և տառատեսակների դիրքերը Drag & Drop-ով։
                </p>
            </div>
            <span class="px-3 py-1 bg-emerald-900/80 text-emerald-300 text-xs font-bold rounded-full border border-emerald-500/30">
                Ունիվերսալ (Universal Label Printer)
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- LEFT COLUMN: Settings Controls & Presets (6 Cols) -->
        <div class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 space-y-5">
                
                <!-- QUICK LABEL SIZE PRESETS -->
                <div class="space-y-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">⚡ Արագ Չափսերի Պրեսեթներ (Quick Paper Size Presets)</span>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        <button type="button" class="preset-btn px-2.5 py-2 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 rounded-xl text-xs font-bold transition text-center" data-w="20" data-h="30">
                            20x30մմ
                        </button>
                        <button type="button" class="preset-btn px-2.5 py-2 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 rounded-xl text-xs font-bold transition text-center" data-w="30" data-h="40">
                            30x40մմ
                        </button>
                        <button type="button" class="preset-btn px-2.5 py-2 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 rounded-xl text-xs font-bold transition text-center" data-w="50" data-h="30">
                            50x30մմ
                        </button>
                        <button type="button" class="preset-btn px-2.5 py-2 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 rounded-xl text-xs font-bold transition text-center" data-w="58" data-h="40">
                            58x40մմ
                        </button>
                        <button type="button" class="preset-btn px-2.5 py-2 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 rounded-xl text-xs font-bold transition text-center" data-w="100" data-h="50">
                            100x50մմ
                        </button>
                    </div>
                </div>

                <!-- Form to update saved user settings -->
                <form id="labelSettingsForm" class="space-y-5">
                    @csrf

                    <!-- SECTION 1: Paper Size & Dimensions (mm) -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-xs font-bold uppercase text-slate-700 block">📐 Paper Size & Dimensions (Լեյբլի Չափսեր մմ)</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-medium text-slate-600">Լայնություն (Width mm)</label>
                                <input type="number" step="0.5" name="width_mm" id="set_width_mm" value="{{ $setting->width_mm }}" required class="setting-input w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-slate-600">Բարձրություն (Height mm)</label>
                                <input type="number" step="0.5" name="height_mm" id="set_height_mm" value="{{ $setting->height_mm }}" required class="setting-input w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-semibold">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Orientation / Layout -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-xs font-bold uppercase text-slate-700 block">🔄 Layout Orientation (Ուղղվածություն)</span>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center space-x-2 p-2.5 bg-white rounded-lg border border-slate-200 cursor-pointer hover:border-emerald-500">
                                <input type="radio" name="orientation" value="portrait" class="setting-input text-emerald-600 focus:ring-emerald-500" {{ $setting->orientation === 'portrait' ? 'checked' : '' }}>
                                <span class="text-xs font-semibold text-slate-700">📱 Portrait (Ուղղաձիգ)</span>
                            </label>
                            <label class="flex items-center space-x-2 p-2.5 bg-white rounded-lg border border-slate-200 cursor-pointer hover:border-emerald-500">
                                <input type="radio" name="orientation" value="landscape" class="setting-input text-emerald-600 focus:ring-emerald-500" {{ $setting->orientation === 'landscape' ? 'checked' : '' }}>
                                <span class="text-xs font-semibold text-slate-700">🖥️ Landscape (Հորիզոնական)</span>
                            </label>
                        </div>
                    </div>

                    <!-- SECTION 3: Margins & Spacing (մմ) -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-xs font-bold uppercase text-slate-700 block">📏 Print Margins & Gap (Լուսանցքներ & Բացատ մմ)</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <div>
                                <label class="block text-[10px] text-slate-600">Վերև (Top mm)</label>
                                <input type="number" step="0.5" min="0" max="50" name="margin_top_mm" id="set_margin_top_mm" value="{{ $setting->margin_top_mm ?? 0 }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">Ներքև (Bottom mm)</label>
                                <input type="number" step="0.5" min="0" max="50" name="margin_bottom_mm" id="set_margin_bottom_mm" value="{{ $setting->margin_bottom_mm ?? 0 }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">Ձախ (Left mm)</label>
                                <input type="number" step="0.5" min="0" max="50" name="margin_left_mm" id="set_margin_left_mm" value="{{ $setting->margin_left_mm ?? 0 }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">Աջ (Right mm)</label>
                                <input type="number" step="0.5" min="0" max="50" name="margin_right_mm" id="set_margin_right_mm" value="{{ $setting->margin_right_mm ?? 0 }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div>
                                <label class="block text-[11px] font-medium text-slate-600">Լեյբլների Բացատ (Roll Gap mm)</label>
                                <input type="number" step="0.5" min="0" max="20" name="label_gap_mm" id="set_label_gap_mm" value="{{ $setting->label_gap_mm ?? 2.0 }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-slate-600">Ընդհանուր Լուսանցք (Margin mm)</label>
                                <input type="number" step="0.1" min="0" max="20" name="margin_mm" id="set_margin_mm" value="{{ $setting->margin_mm }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs font-semibold">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Print Scale & Resolution DPI -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-xs font-bold uppercase text-slate-700 block">🖨️ Print Scale & Resolution (Մասշտաբ & Խտություն DPI)</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-medium text-slate-600">Print Scale (Մասշտաբ %)</label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" min="50" max="200" name="print_scale" id="set_print_scale" value="{{ $setting->print_scale ?? 100 }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs font-semibold">
                                    <span class="text-xs font-bold text-slate-500">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-slate-600">Print DPI (Տպագրության DPI)</label>
                                <select name="print_dpi" id="set_print_dpi" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs font-semibold bg-white">
                                    <option value="203" {{ ($setting->print_dpi ?? 203) == 203 ? 'selected' : '' }}>203 DPI (Ստանդարտ Thermal)</option>
                                    <option value="300" {{ ($setting->print_dpi ?? 203) == 300 ? 'selected' : '' }}>300 DPI (Բարձր Խտություն High-Res)</option>
                                    <option value="600" {{ ($setting->print_dpi ?? 203) == 600 ? 'selected' : '' }}>600 DPI (Գերբարձր Ultra-Res)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: Product Name Typography & Placement -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-700">🏷️ Ապրանքի Անվանում (Text 1)</span>
                            <label class="flex items-center space-x-1.5 cursor-pointer">
                                <input type="checkbox" name="product_font_bold" id="set_product_font_bold" value="1" {{ $setting->product_font_bold ? 'checked' : '' }} class="setting-input rounded text-emerald-600">
                                <span class="text-xs font-bold text-slate-700">Bold (Թավ)</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] text-slate-600">Տառաչափ (pt)</label>
                                <input type="number" name="product_font_size" id="set_product_font_size" value="{{ $setting->product_font_size }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">X (մմ)</label>
                                <input type="number" step="0.5" name="product_pos_x" id="set_product_pos_x" value="{{ $setting->product_pos_x }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">Y (մմ)</label>
                                <input type="number" step="0.5" name="product_pos_y" id="set_product_pos_y" value="{{ $setting->product_pos_y }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 6: Last 5 Digits Typography & Placement -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-700">🔢 Վերջին 5 նիշեր (Text 2)</span>
                            <label class="flex items-center space-x-1.5 cursor-pointer">
                                <input type="checkbox" name="last5_font_bold" id="set_last5_font_bold" value="1" {{ $setting->last5_font_bold ? 'checked' : '' }} class="setting-input rounded text-emerald-600">
                                <span class="text-xs font-bold text-slate-700">Bold (Թավ)</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] text-slate-600">Տառաչափ (pt)</label>
                                <input type="number" name="last5_font_size" id="set_last5_font_size" value="{{ $setting->last5_font_size }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">X (մմ)</label>
                                <input type="number" step="0.5" name="last5_pos_x" id="set_last5_pos_x" value="{{ $setting->last5_pos_x }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">Y (մմ)</label>
                                <input type="number" step="0.5" name="last5_pos_y" id="set_last5_pos_y" value="{{ $setting->last5_pos_y }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 7: DataMatrix Size & Placement -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-xs font-bold uppercase text-slate-700 block">🔳 DataMatrix Barcode</span>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] text-slate-600">Չափս (մմ)</label>
                                <input type="number" step="0.5" name="datamatrix_size" id="set_datamatrix_size" value="{{ $setting->datamatrix_size }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">X (մմ)</label>
                                <input type="number" step="0.5" name="datamatrix_pos_x" id="set_datamatrix_pos_x" value="{{ $setting->datamatrix_pos_x }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600">Y (մմ)</label>
                                <input type="number" step="0.5" name="datamatrix_pos_y" id="set_datamatrix_pos_y" value="{{ $setting->datamatrix_pos_y }}" class="setting-input w-full px-2.5 py-1.5 rounded border text-xs">
                            </div>
                        </div>
                    </div>

                    <button type="button" id="saveSettingsBtn" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-emerald-400 font-bold text-sm rounded-xl transition border border-slate-700 shadow-lg">
                        💾 Պահպանել Բոլոր Կարգավորումները
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: Interactive Drag & Drop Live Preview Box (6 Cols) -->
        <div class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 space-y-4 sticky top-20">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                        <span>🖥️ Drag & Drop Real-Time Visual Canvas</span>
                    </span>

                    <!-- Thermal Ribbon Simulation Toggle -->
                    <button type="button" id="toggleThermalThemeBtn" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 font-bold rounded-lg text-xs transition">
                        🟡 Thermal Yellow Mode
                    </button>
                </div>

                <p class="text-xs text-slate-500">💡 Մկնիկով քաշեք (Drag & Drop) տեքստերը կամ DataMatrix-ը canvas-ի վրա դիրքը փոխելու համար։</p>

                <div id="previewContainer" class="bg-slate-200 p-8 rounded-2xl flex items-center justify-center min-h-[350px] transition-colors duration-300 overflow-auto">
                    <!-- Scaled Label Box (Zoomed 5x for UI preview canvas) -->
                    <div id="liveLabelCanvas" class="bg-white border-2 border-slate-400 shadow-2xl relative transition-all overflow-hidden select-none" 
                         style="width: {{ $setting->width_mm * 5 }}px; height: {{ $setting->height_mm * 5 }}px;">
                        
                        <!-- Product Name Layer (Draggable) -->
                        <div id="prev_product_name" class="draggable-layer absolute text-slate-900 overflow-hidden whitespace-nowrap leading-none cursor-grab active:cursor-grabbing border border-transparent hover:border-sky-400 hover:bg-sky-50/50 p-0.5 rounded"
                             style="font-size: {{ $setting->product_font_size * 0.9 }}px; font-weight: {{ $setting->product_font_bold ? '700' : '400' }}; left: {{ $setting->product_pos_x * 5 }}px; top: {{ $setting->product_pos_y * 5 }}px;">
                            Ապրանքի Անվանում
                        </div>

                        <!-- Last 5 Digits Layer (Draggable) -->
                        <div id="prev_last5" class="draggable-layer absolute text-slate-900 leading-none cursor-grab active:cursor-grabbing border border-transparent hover:border-sky-400 hover:bg-sky-50/50 p-0.5 rounded"
                             style="font-size: {{ $setting->last5_font_size * 0.9 }}px; font-weight: {{ $setting->last5_font_bold ? '800' : '400' }}; left: {{ $setting->last5_pos_x * 5 }}px; top: {{ $setting->last5_pos_y * 5 }}px;">
                            A1234
                        </div>

                        <!-- DataMatrix Code Canvas Layer (Draggable) -->
                        <div id="prev_dm_wrap" class="draggable-layer absolute cursor-grab active:cursor-grabbing border border-transparent hover:border-sky-400 hover:bg-sky-50/50 p-0.5 rounded"
                             style="left: {{ $setting->datamatrix_pos_x * 5 }}px; top: {{ $setting->datamatrix_pos_y * 5 }}px;">
                            <canvas id="prev_dm_canvas" style="width: {{ $setting->datamatrix_size * 5 }}px; height: {{ $setting->datamatrix_size * 5 }}px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const liveLabelCanvas = document.getElementById('liveLabelCanvas');

    // Initial DataMatrix preview render
    renderSampleDataMatrix('010486000543210921A1234');

    function renderSampleDataMatrix(code) {
        try {
            bwipjs.toCanvas('prev_dm_canvas', {
                bcid: 'datamatrix',
                text: code || 'DEMO-DATAMATRIX-CODE',
                scale: 3,
                padding: 0
            });
        } catch (e) {
            console.error('DataMatrix Preview Error:', e);
        }
    }

    // Quick presets
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const w = this.dataset.w;
            const h = this.dataset.h;
            document.getElementById('set_width_mm').value = w;
            document.getElementById('set_height_mm').value = h;
            updateLiveLabelUI();
        });
    });

    // Thermal Yellow toggle
    const toggleThermalThemeBtn = document.getElementById('toggleThermalThemeBtn');
    let isThermalYellow = false;

    toggleThermalThemeBtn.addEventListener('click', function () {
        isThermalYellow = !isThermalYellow;
        if (isThermalYellow) {
            liveLabelCanvas.style.backgroundColor = '#fef08a';
            this.textContent = '⚪ Standard White Mode';
            this.className = 'px-3 py-1.5 bg-slate-800 text-white font-bold rounded-lg text-xs transition';
        } else {
            liveLabelCanvas.style.backgroundColor = '#ffffff';
            this.textContent = '🟡 Thermal Yellow Mode';
            this.className = 'px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 font-bold rounded-lg text-xs transition';
        }
    });

    // Input watchers & UI Canvas updater
    const inputs = document.querySelectorAll('.setting-input');
    inputs.forEach(input => {
        input.addEventListener('input', updateLiveLabelUI);
        input.addEventListener('change', updateLiveLabelUI);
    });

    function updateLiveLabelUI() {
        const w = parseFloat(document.getElementById('set_width_mm').value) || 20;
        const h = parseFloat(document.getElementById('set_height_mm').value) || 30;

        const orientationRadio = document.querySelector('input[name="orientation"]:checked');
        const orientation = orientationRadio ? orientationRadio.value : 'portrait';

        const pSize = parseFloat(document.getElementById('set_product_font_size').value) || 9;
        const pBold = document.getElementById('set_product_font_bold').checked;
        const pX = parseFloat(document.getElementById('set_product_pos_x').value) || 1.5;
        const pY = parseFloat(document.getElementById('set_product_pos_y').value) || 2;

        const lSize = parseFloat(document.getElementById('set_last5_font_size').value) || 11;
        const lBold = document.getElementById('set_last5_font_bold').checked;
        const lX = parseFloat(document.getElementById('set_last5_pos_x').value) || 1.5;
        const lY = parseFloat(document.getElementById('set_last5_pos_y').value) || 7;

        const dmSize = parseFloat(document.getElementById('set_datamatrix_size').value) || 15;
        const dmX = parseFloat(document.getElementById('set_datamatrix_pos_x').value) || 2.5;
        const dmY = parseFloat(document.getElementById('set_datamatrix_pos_y').value) || 12;

        // Apply width/height based on orientation
        let canvasW = w;
        let canvasH = h;
        if (orientation === 'landscape') {
            canvasW = Math.max(w, h);
            canvasH = Math.min(w, h);
        }

        liveLabelCanvas.style.width = (canvasW * 5) + 'px';
        liveLabelCanvas.style.height = (canvasH * 5) + 'px';

        const pEl = document.getElementById('prev_product_name');
        pEl.style.fontSize = (pSize * 0.9) + 'px';
        pEl.style.fontWeight = pBold ? '700' : '400';
        pEl.style.left = (pX * 5) + 'px';
        pEl.style.top = (pY * 5) + 'px';

        const lEl = document.getElementById('prev_last5');
        lEl.style.fontSize = (lSize * 0.9) + 'px';
        lEl.style.fontWeight = lBold ? '800' : '400';
        lEl.style.left = (lX * 5) + 'px';
        lEl.style.top = (lY * 5) + 'px';

        const dmWrap = document.getElementById('prev_dm_wrap');
        dmWrap.style.left = (dmX * 5) + 'px';
        dmWrap.style.top = (dmY * 5) + 'px';

        const dmCanvas = document.getElementById('prev_dm_canvas');
        dmCanvas.style.width = (dmSize * 5) + 'px';
        dmCanvas.style.height = (dmSize * 5) + 'px';
    }

    // Mouse drag & drop engine
    enableDragElement(document.getElementById('prev_product_name'), 'set_product_pos_x', 'set_product_pos_y');
    enableDragElement(document.getElementById('prev_last5'), 'set_last5_pos_x', 'set_last5_pos_y');
    enableDragElement(document.getElementById('prev_dm_wrap'), 'set_datamatrix_pos_x', 'set_datamatrix_pos_y');

    function enableDragElement(el, posXInputId, posYInputId) {
        let isDragging = false;
        let startX, startY, initialLeft, initialTop;

        el.addEventListener('mousedown', function (e) {
            e.preventDefault();
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;

            initialLeft = parseFloat(el.style.left) || 0;
            initialTop = parseFloat(el.style.top) || 0;

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        });

        function onMouseMove(e) {
            if (!isDragging) return;

            const dx = e.clientX - startX;
            const dy = e.clientY - startY;

            const newLeftPx = Math.max(0, initialLeft + dx);
            const newTopPx = Math.max(0, initialTop + dy);

            el.style.left = newLeftPx + 'px';
            el.style.top = newTopPx + 'px';

            const mmX = (newLeftPx / 5).toFixed(1);
            const mmY = (newTopPx / 5).toFixed(1);

            const inputX = document.getElementById(posXInputId);
            const inputY = document.getElementById(posYInputId);
            if (inputX) inputX.value = mmX;
            if (inputY) inputY.value = mmY;
        }

        function onMouseUp() {
            isDragging = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        }
    }

    // Save Label Settings AJAX
    document.getElementById('saveSettingsBtn').addEventListener('click', function () {
        const form = document.getElementById('labelSettingsForm');
        const formData = new FormData(form);

        fetch('{{ route("settings.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                let errorMsg = data.message || 'Սխալ կարգավորումները պահպանելիս:';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                throw new Error(errorMsg);
            }
            return data;
        })
        .then(data => {
            if (data.success) {
                alert('✅ ' + (data.message || 'Լեյբլի կարգավորումները հաջողությամբ պահպանվեցին:'));
                window.location.reload();
            } else {
                alert(data.message || 'Սխալ կարգավորումները պահպանելիս:');
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || 'Սխալ՝ կապի խափանման պատճառով:');
        });
    });
});
</script>
@endsection
