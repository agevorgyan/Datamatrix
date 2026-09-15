<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xprinter XP-356B Thermal Print - {{ $printJob->product_name }}</title>
    
    <!-- Tailwind CSS for screen controls -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- BWIP-JS DataMatrix Generator Engine -->
    <script src="https://cdn.jsdelivr.net/npm/bwip-js@3.4.4/dist/bwip-js-min.js"></script>

    <style>
        /* Exact millimeter print page rules */
        @page {
            size: {{ $setting->width_mm }}mm {{ $setting->height_mm }}mm {{ $setting->orientation === 'landscape' ? 'landscape' : 'portrait' }};
            margin: {{ $setting->margin_top_mm ?? 0 }}mm {{ $setting->margin_right_mm ?? 0 }}mm {{ $setting->margin_bottom_mm ?? 0 }}mm {{ $setting->margin_left_mm ?? 0 }}mm;
        }

        @media print {
            html, body {
                width: {{ $setting->width_mm }}mm;
                height: {{ $setting->height_mm }}mm;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                zoom: {{ ($setting->print_scale ?? 100) / 100 }};
            }

            .no-print {
                display: none !important;
            }

            .label-page {
                page-break-after: always;
                break-after: page;
                margin: 0 !important;
                margin-bottom: {{ $setting->label_gap_mm ?? 0 }}mm !important;
                border: none !important;
                box-shadow: none !important;
            }
        }

        /* Screen Preview Styling */
        .label-page {
            width: {{ $setting->width_mm }}mm;
            height: {{ $setting->height_mm }}mm;
            position: relative;
            background: #ffffff;
            overflow: hidden;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Text positioning & sizing */
        .product-name-layer {
            position: absolute;
            left: {{ $setting->product_pos_x }}mm;
            top: {{ $setting->product_pos_y }}mm;
            font-size: {{ $setting->product_font_size }}pt;
            font-weight: {{ $setting->product_font_bold ? '700' : '400' }};
            line-height: 1;
            white-space: nowrap;
            overflow: hidden;
            color: #000000;
        }

        .last5-layer {
            position: absolute;
            left: {{ $setting->last5_pos_x }}mm;
            top: {{ $setting->last5_pos_y }}mm;
            font-size: {{ $setting->last5_font_size }}pt;
            font-weight: {{ $setting->last5_font_bold ? '800' : '400' }};
            line-height: 1;
            color: #000000;
        }

        .datamatrix-layer {
            position: absolute;
            left: {{ $setting->datamatrix_pos_x }}mm;
            top: {{ $setting->datamatrix_pos_y }}mm;
        }

        .datamatrix-canvas {
            width: {{ $setting->datamatrix_size }}mm;
            height: {{ $setting->datamatrix_size }}mm;
            display: block;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased">

    <!-- Top Floating Toolbar (Hidden during print) -->
    <div class="no-print sticky top-0 z-50 bg-slate-900 text-white px-6 py-4 shadow-xl flex items-center justify-between border-b border-slate-800">
        <div>
            <h1 class="text-base font-bold flex items-center space-x-2">
                <span>🖨️ Տպագրության Պատրաստ Խմբաքանակ</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-600 text-white">{{ $printJob->total_codes }} լեյբլ</span>
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Ապրանք՝ <b>{{ $printJob->product_name }}</b> • Չափս՝ <b>{{ $setting->width_mm }}x{{ $setting->height_mm }}մմ</b> • Layout: <b>{{ ucfirst($setting->orientation) }}</b> • DPI: <b>{{ $setting->print_dpi ?? 203 }} DPI</b> • Scale: <b>{{ $setting->print_scale ?? 100 }}%</b>
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition">
                Փակել
            </button>
            <button onclick="triggerThermalPrint()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>ՏՊԵԼ ՀԻՄԱ (Print)</span>
            </button>
        </div>
    </div>

    <!-- Screen Scrollable Container -->
    <div class="p-6 md:p-10 flex flex-wrap justify-center gap-4 no-print-bg">
        @foreach($codes as $index => $item)
            <div class="label-page" id="label-{{ $index }}">
                <div class="product-name-layer">
                    {{ $printJob->product_name }}
                </div>
                <div class="last5-layer">
                    {{ $item->last_5_chars }}
                </div>
                <div class="datamatrix-layer">
                    <canvas class="datamatrix-canvas" id="canvas-dm-{{ $index }}"></canvas>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // High-DPI DataMatrix Canvas Batch Renderer
        const codeItems = @json($codes);
        const printDpi = {{ $setting->print_dpi ?? 203 }};
        const dpiScaleMap = { 203: 4, 300: 6, 600: 10 };
        const bwipScaleFactor = dpiScaleMap[printDpi] || 4;

        function renderAllDataMatrixCodes() {
            codeItems.forEach((item, index) => {
                const canvasId = `canvas-dm-${index}`;
                try {
                    bwipjs.toCanvas(canvasId, {
                        bcid: 'datamatrix',
                        text: item.code,
                        scale: bwipScaleFactor, // Scaled for high thermal DPI density
                        padding: 0
                    });
                } catch (err) {
                    console.error(`Error rendering DataMatrix index ${index}:`, err);
                }
            });
        }

        function triggerThermalPrint() {
            window.print();
        }

        window.addEventListener('DOMContentLoaded', () => {
            renderAllDataMatrixCodes();
        });
    </script>
</body>
</html>
