@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200 space-y-6">
    <h1 class="text-3xl font-bold text-slate-900 pb-4 border-b border-slate-100">
        📜 Օգտագործման Պայմաններ (Terms of Use)
    </h1>

    <div class="prose text-slate-700 text-sm leading-relaxed space-y-4">
        <p>
            Բարի գալուստ <b>datamatrix.elab.am</b> համակարգ։ Սույն օգտագործման պայմանները սահմանում են համակարգից օգտվելու կանոնները։
        </p>

        <h3 class="text-base font-bold text-slate-900 mt-6">1. Ծառայության Նկարագրությունը</h3>
        <p>
            Համակարգը նախատեսված է CSV ֆայլերից DataMatrix կոդերի ներմուծման, ձևավորման և Xprinter XP-356B պրինտերով 20x30մմ (կամ այլ չափսերի) լեյբլների տպագրության համար։
        </p>

        <h3 class="text-base font-bold text-slate-900 mt-6">2. Օգտատիրոջ Հաշիվը և Անվտանգությունը</h3>
        <p>
            Օգտատերը պատասխանատու է իր մուտքանվան և գաղտնաբառի պահպանման, ինչպես նաև իր հաշվի միջոցով իրականացվող բոլոր գործողությունների համար։
        </p>

        <h3 class="text-base font-bold text-slate-900 mt-6">3. Տվյալների Պահպանում</h3>
        <p>
            Բոլոր ներբեռնված CSV ֆայլերը և տպագրության պատմությունը պահվում են ապահով տվյալների բազայում՝ առանց արտաքին cloud ծառայությունների փոխանցման։
        </p>

        <h3 class="text-base font-bold text-slate-900 mt-6">4. Մտավոր Սեփականություն</h3>
        <p>
            Համակարգը պատրաստված է սիրով <b>elab.am</b>-ի կողմից։ Բոլոր իրավունքները պաշտպանված են։
        </p>
    </div>

    <div class="pt-6 border-t border-slate-100 text-xs text-slate-400">
        Վերջին թարմացում՝ 2026թ․
    </div>
</div>
@endsection
