@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-slate-100 space-y-6">
        <div class="text-center">
            <div class="mx-auto w-auto h-16 flex items-center justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Մոռացե՞լ եք Գաղտնաբառը</h2>
            <p class="text-xs text-slate-500 mt-1">Մուտքագրեք Ձեր էլ․ հասցեն, և մենք Ձեզ կուղարկենք գաղտնաբառի վերականգնման հղումը</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Էլ․ Փոստ (Email)</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 text-xs font-semibold transition" 
                    placeholder="example@elab.am">
                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-500 hover:to-blue-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/30 transition duration-200">
                📩 Ուղարկել Վերականգնման Հղումը
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-slate-900">← Վերադառնալ Մուտքի Էջ</a>
            <a href="{{ route('register') }}" class="font-semibold text-sky-600 hover:underline">Գրանցվել</a>
        </div>
    </div>
</div>
@endsection
