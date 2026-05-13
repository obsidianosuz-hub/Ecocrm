@extends('layouts.cyber')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center min-h-[60vh]">
    <div class="glass-panel p-10 text-center relative overflow-hidden border-red-500/30">
        <div class="absolute inset-0 bg-red-500/5 mix-blend-overlay pointer-events-none"></div>
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-50"></div>
        
        <i class="fa-solid fa-lock text-red-500 text-6xl mb-6 drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]"></i>
        
        <h1 class="text-4xl md:text-6xl font-orbitron font-black text-white tracking-widest uppercase mb-4 text-shadow-sm">403</h1>
        
        <div class="inline-block border border-red-500/20 bg-red-500/10 px-6 py-2 rounded-lg mb-6">
            <span class="text-red-400 font-bold uppercase tracking-widest text-sm">Access Denied / Ruxsat Etilmagan</span>
        </div>
        
        <p class="text-white/60 font-mono text-sm max-w-md mx-auto mb-8">
            {{ $exception->getMessage() ?: 'Tizimning ushbu qismiga kirish yoki bu amalni bajarish uchun sizda yetarli huquqlar mavjud emas.' }}
        </p>
        
        <button onclick="window.history.back()" class="btn-ios px-8 py-3 bg-red-500/20 border-red-500/50 text-red-400 hover:bg-red-500/40 hover:text-white transition-all uppercase font-bold tracking-widest text-xs rounded-lg">
            <i class="fa-solid fa-arrow-left mr-2"></i> ORTGA QAYTISH
        </button>
    </div>
</div>
@endsection
