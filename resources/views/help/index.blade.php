@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-8 w-auto bg-white/10 p-1 rounded border border-white/20">
                    <span class="text-xs uppercase font-bold tracking-wider text-emerald-400">User Documentation & Guide</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    ❓ Օգնություն & Օգտագործողի Ուղեցույց
                </h1>
                <p class="text-slate-300 text-xs md:text-sm mt-1 max-w-2xl">
                    Այստեղ ներկայացված են համակարգի հնարավորությունները և ծրագրից օգտվելու մանրամասն քայլերը։
                </p>
            </div>
        </div>
    </div>

    <!-- Main Help Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Card 1: Features Overview -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold">
                1
            </div>
            <h3 class="text-lg font-bold text-slate-900">📖 Ծրագրի Հնարավորությունները</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Ծրագիրը նախատեսված է CSV ֆայլերից DataMatrix կոդերի ներմուծման, ձևավորման և <b>ունիվերսալ ջերմային (Thermal Roll) պրինտերներով</b> լեյբլների արագ տպագրության համար։
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 list-disc list-inside pt-1">
                <li>Անձնական հաշվով մուտք և տվյալների պահպանում</li>
                <li>CSV ֆայլերի 5-տողանի ակնթարթային preview ստուգում</li>
                <li>Drag & Drop ինտերակտիվ լեյբլի դիզայներ</li>
                <li>Վերջին 5 նիշերի ավտոմատ առանձնացում</li>
                <li>Տպագրության պատմություն, Visual Badges, Batch Delete, CSV Export</li>
            </ul>
        </div>

        <!-- Card 2: CSV Upload Guide -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-3">
            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-800 flex items-center justify-center text-xl font-bold">
                2
            </div>
            <h3 class="text-lg font-bold text-slate-900">📥 CSV Ֆայլի Պատրաստում & Import</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                CSV ֆայլը պետք է պարունակի <b>մեկ սյունյակով բուն կոդերը</b> (առանց վերնագրի կամ 1-ին սյունյակում):
            </p>
            <div class="bg-slate-900 text-emerald-400 p-3 rounded-xl font-mono text-[11px]">
                010486000543210921A1001<br>
                010486000543210921A1002<br>
                010486000543210921A1003
            </div>
            <p class="text-[11px] text-slate-500">
                Ֆայլը ներբեռնելուց հետո Dashboard-ում ցուցադրվում է առաջին 5 տողերի աղյուսակը՝ ճշգրտությունը ստուգելու համար։
            </p>
        </div>

        <!-- Card 3: Label Designer & Drag & Drop -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-800 flex items-center justify-center text-xl font-bold">
                3
            </div>
            <h3 class="text-lg font-bold text-slate-900">⚙️ Լեյբլների Ձևավորում Drag & Drop-ով</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                <b><a href="{{ route('settings.edit') }}" class="text-emerald-600 font-bold hover:underline">Կարգավորումներ</a></b> էջում կարող եք փոխել լեյբլի չափսերը (մմ), տառատեսակները և դիրքերը։
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 list-disc list-inside">
                <li><b>Drag & Drop:</b> Մկնիկով քաշեք տեքստերը կամ DataMatrix-ը canvas-ի վրա</li>
                <li><b>Quick Presets:</b> 1 սեղմումով ընտրեք 20x30մմ, 30x40մմ կամ 50x30մմ</li>
                <li><b>Thermal Yellow Mode:</b> Տեսեք լեյբլը դեղին ջերմային թղթի վրա</li>
            </ul>
        </div>

        <!-- Card 4: Universal Thermal Printing Guide -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                4
            </div>
            <h3 class="text-lg font-bold text-slate-900">🖨️ Ունիվերսալ Ջերմային Տպագրություն</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Ծրագիրը ունիվերսալ է և աշխատում է բոլոր տեսակի ջերմային պրինտերների հետ (Xprinter, Zebra, TSPL, Honeywell, Godex):
            </p>
            <div class="bg-amber-50 border border-amber-200 p-3 rounded-xl text-amber-900 text-xs space-y-1">
                <div class="font-bold">⚠️ Chrome/Edge Տպագրման Կարգավորումներ․</div>
                <div>1. Destination: Ընտրեք Ձեր լեյբլ պրինտերը</div>
                <div>2. Paper Size: Ընտրեք Ձեր լեյբլի չափսը (օր․՝ 20x30mm)</div>
                <div>3. Margins: Դրեք <b>None</b> (0մմ)</div>
                <div>4. Scale: Դրեք <b>100% (Default)</b></div>
            </div>
        </div>

    </div>

    <!-- FAQ Accordion Section -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
        <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
            <span>❓ Հաճախ Տրվող Հարցեր (FAQ)</span>
        </h3>

        <div class="space-y-3 divide-y divide-slate-100 text-xs">
            <div class="pt-2">
                <h4 class="font-bold text-slate-900 text-sm">Ի՞նչ առավելագույն քանակի կոդեր կարելի է տպել մեկ CSV ֆայլով:</h4>
                <p class="text-slate-600 mt-1">
                    Համակարգը օպտիմալացված է մինչև 10,000 կոդ պարունակող ֆայլերի համար։ Մեծ ֆայլերը մշակվում են chunk-երով՝ բրաուզերը կախելուց պաշտպանելու համար։
                </p>
            </div>

            <div class="pt-3">
                <h4 class="font-bold text-slate-900 text-sm">Որտե՞ղ են պահվում իմ ներբեռնած տվյալները:</h4>
                <p class="text-slate-600 mt-1">
                    Բոլոր տվյալները պահվում են ապահով MySQL տվյալների բազայում՝ առանց արտաքին Cloud ծառայությունների փոխանցման։ Յուրաքանչյուր օգտատեր տեսնում է միայն իր պատմությունը։
                </p>
            </div>

            <div class="pt-3">
                <h4 class="font-bold text-slate-900 text-sm">Ինչպե՞ս արտահանել տպագրության պատմությունը:</h4>
                <p class="text-slate-600 mt-1">
                    <b><a href="{{ route('history.index') }}" class="text-emerald-600 font-bold hover:underline">Պատմություն</a></b> էջում սեղմեք "Արտահանել CSV" կոճակը՝ ամբողջ պատմությունը UTF-8 BOM CSV ֆայլով ներբեռնելու համար։
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
