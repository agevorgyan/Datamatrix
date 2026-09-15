@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-slate-100 space-y-6">
        <div class="text-center">
            <div class="mx-auto w-auto h-16 flex items-center justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Նոր Գաղտնաբառի Սահմանում</h2>
            <p class="text-xs text-slate-500 mt-1">Մուտքագրեք Ձեր էլ․ հասցեն և նոր գաղտնաբառը</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Էլ․ Փոստ (Email)</label>
                <input type="email" name="email" id="email" required value="{{ old('email', $email) }}" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 text-xs font-semibold transition">
                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Նոր Գաղտնաբառ</label>
                <input type="password" name="password" id="password" required minlength="8"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 text-xs font-semibold transition"
                    placeholder="Առնվազն 8 նիշ">
                @error('password')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Կրկնել Նոր Գաղտնաբառը</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 text-xs font-semibold transition"
                    placeholder="Կրկնեք նոր գաղտնաբառը">
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition duration-200">
                🔒 Պահպանել Նոր Գաղտնաբառը
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-slate-900">← Վերադառնալ Մուտքի Էջ</a>
        </div>
    </div>
</div>
@endsection
