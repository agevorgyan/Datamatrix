@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="mx-auto w-auto h-16 flex items-center justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Գրանցում</h2>
            <p class="text-sm text-slate-500 mt-1">Ստեղծեք անձնական հաշիվ DataMatrix տպագրության համար</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Անուն Ազգանուն</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 transition" 
                    placeholder="Արմեն Պետրոսյան">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Էլ․ Փոստ (Email)</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 transition" 
                    placeholder="example@elab.am">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Գաղտնաբառ</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 transition"
                    placeholder="առնվազն 8 նիշ">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Կրկնել Գաղտնաբառը</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none text-slate-800 transition"
                    placeholder="կրկնեք գաղտնաբառը">
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-500 hover:to-blue-500 text-white font-semibold rounded-xl shadow-lg shadow-sky-600/30 transition duration-200 mt-2">
                Ստեղծել Հաշիվ
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Արդեն ունե՞ք հաշիվ: 
            <a href="{{ route('login') }}" class="font-semibold text-sky-600 hover:underline">Մուտք գործեք</a>
        </div>
    </div>
</div>
@endsection
