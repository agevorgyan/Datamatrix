@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('history.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                    &larr; Վերադառնալ Պատմությանը
                </a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-mono font-bold text-slate-400">Batch #{{ $printJob->id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">{{ $printJob->product_name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Ֆայլ՝ <b>{{ $printJob->file_name }}</b> • Ընդհանուր կոդեր՝ <b>{{ number_format($printJob->total_codes) }}</b> • {{ $printJob->created_at->format('Y-m-d H:i') }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard.print', $printJob->id) }}" target="_blank" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-2">
                <span>🖨️ Տպել Խմբաքանակը</span>
            </a>

            <form method="POST" action="{{ route('history.destroy', $printJob->id) }}" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք ջնջել այս խմբաքանակը:');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs rounded-xl border border-rose-200 transition">
                    🗑️ Ջնջել
                </button>
            </form>
        </div>
    </div>

    <!-- Codes Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <span class="text-xs font-bold uppercase text-slate-700">📋 Խմբաքանակի Կոդերի Ցանկ</span>
            <span class="text-xs font-semibold text-slate-500">Ցուցադրված է 50 կոդ մեկ էջում</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100 text-slate-800 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Բուն Կոդ (DataMatrix Code)</th>
                        <th class="p-3 text-sky-700">Վերջին 5 նիշեր</th>
                        <th class="p-3">Տպագրման Ամսաթիվ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono">
                    @foreach($codes as $index => $code)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 text-slate-400 font-bold">{{ $codes->firstItem() + $index }}</td>
                            <td class="p-3 text-slate-900 break-all">{{ $code->code }}</td>
                            <td class="p-3 font-bold text-sky-700 bg-sky-50/50">{{ $code->last_5_chars }}</td>
                            <td class="p-3 text-slate-500 text-[11px]">{{ $code->printed_at ? $code->printed_at->format('Y-m-d H:i:s') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $codes->links() }}
        </div>
    </div>
</div>
@endsection
