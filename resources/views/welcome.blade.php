@extends('layouts.guest')

@section('content')

<!-- Header with Login -->
<div class="absolute top-0 right-0 w-full p-4 md:p-6 flex justify-end z-50">
    @auth
        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md hover:bg-white/10 hover:border-cyan-500/30 transition-all duration-300">
            <span class="text-[10px] md:text-xs font-black text-white/80 uppercase tracking-widest group-hover:text-cyan-400 transition-colors">Dashboard</span>
            <div class="w-8 h-8 rounded-full bg-cyan-500/20 flex items-center justify-center group-hover:bg-cyan-500/30 group-hover:scale-110 transition-all drop-shadow-[0_0_10px_rgba(0,255,204,0.3)]">
                <i class="fa-solid fa-border-all text-cyan-400 text-sm"></i>
            </div>
        </a>
    @else
        <a href="{{ route('login') }}" class="group flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md hover:bg-white/10 hover:border-cyan-500/30 transition-all duration-300">
            <span class="text-[10px] md:text-xs font-black text-white/80 uppercase tracking-widest group-hover:text-cyan-400 transition-colors">Tizimga Kirish</span>
            <div class="w-8 h-8 rounded-full bg-cyan-500/20 flex items-center justify-center group-hover:bg-cyan-500/30 group-hover:scale-110 transition-all drop-shadow-[0_0_10px_rgba(0,255,204,0.3)]">
                <i class="fa-solid fa-right-to-bracket text-cyan-400 text-sm"></i>
            </div>
        </a>
    @endauth
</div>

<div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-24 overflow-x-hidden pt-24 md:pt-32">
    <!-- Main Hero Node -->
    <div class="flex flex-col items-center text-center space-y-8 md:space-y-12 mb-20 md:mb-32">
        <div class="inline-flex items-center gap-3 px-4 md:px-6 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
            <div class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-cyan-400 animate-pulse"></div>
            <span class="text-[8px] md:text-[10px] font-black text-white/80 uppercase tracking-[0.3em] md:tracking-[0.5em]">Neural Protocol Activated | ITCloud Ecosystem</span>
        </div>
        
        <h1 class="text-5xl sm:text-7xl md:text-[10rem] font-black text-white tracking-tighter leading-[0.8] uppercase break-words">
            OBSIDIAN<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500">NETWORKS</span>
        </h1>

        <div class="max-w-3xl space-y-6 px-2">
            <p class="text-base md:text-2xl text-white/60 leading-relaxed font-light">
                <span class="text-white font-black uppercase text-[10px] md:text-sm tracking-widest block mb-2 md:mb-4 opacity-50">Kompaniya Haqida</span>
                <span class="text-white font-bold">ITCloud</span> — bu O'zbekistondagi eng ilg'or raqamli transformatsiya markazi. Biz biznesingizni yangi bosqichga olib chiqadigan intellektual yechimlarni ishlab chiqamiz. <span class="text-cyan-400 font-bold">Obsidian OS</span> — barcha biznes jarayonlarni bitta markazlashgan "miya" orqali boshqarish imkonini beradi.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6 pt-6 w-full">
            <a href="{{ route('login') }}" class="w-full sm:w-auto btn-neon px-12 py-5 md:py-7 text-[11px] md:text-[13px] tracking-[0.3em] md:tracking-[0.4em]">INITIATE CONNECTION</a>
            <a href="#about-crm" class="group flex items-center gap-3 text-[10px] md:text-[11px] font-black text-white/40 hover:text-white transition-all uppercase tracking-[0.3em] md:tracking-[0.4em]">
                CRM AFZALLIKLARI
                <i class="fa-solid fa-chevron-down group-hover:translate-y-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <div id="about-crm" class="flex flex-col gap-12 mb-20 md:mb-32">
        
        <!-- CRM Advantages -->
        <div class="glass-panel p-8 md:p-14 border-cyan-500/20 flex flex-col items-center text-center">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20 mb-6 drop-shadow-[0_0_15px_rgba(0,255,204,0.2)]">
                <i class="fa-solid fa-rocket text-3xl md:text-4xl text-cyan-400"></i>
            </div>
            <h2 class="text-2xl md:text-4xl font-black text-white uppercase tracking-tighter mb-10">CRM Tizimining Afzalliklari</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 w-full">
                @php
                    $adv = [
                        ['title' => 'To\'liq Avtomatlashtirish', 'desc' => 'Qo\'lda bajariladigan ishlarni 90% gacha qisqartiring.'],
                        ['title' => 'Real-Vaqt Analitikasi', 'desc' => 'Monitoring va hisobotlarni jonli ravishda kuzatib boring.'],
                        ['title' => 'Markazlashgan Baza', 'desc' => 'Barcha ma\'lumotlar bitta xavfsiz joyda saqlanadi.'],
                        ['title' => 'Intellektual AI', 'desc' => 'Muhim vazifalar haqida sun\'iy intellekt xabar beradi.'],
                    ];
                @endphp
                @foreach($adv as $a)
                    <div class="p-6 md:p-8 rounded-3xl bg-white/5 border border-white/5 hover:bg-white/10 hover:border-cyan-500/30 hover:-translate-y-2 transition-all duration-300 group flex flex-col items-center">
                        <div class="text-[11px] md:text-xs font-black text-cyan-400 uppercase tracking-widest mb-4 group-hover:text-cyan-300 transition-colors">{{ $a['title'] }}</div>
                        <p class="text-[11px] md:text-sm text-white/40 leading-relaxed group-hover:text-white/70 transition-colors">{{ $a['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Application Areas -->
        <div class="glass-panel p-8 md:p-14 border-purple-500/20 flex flex-col items-center text-center">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 mb-6 drop-shadow-[0_0_15px_rgba(176,38,255,0.2)]">
                <i class="fa-solid fa-briefcase text-3xl md:text-4xl text-purple-400"></i>
            </div>
            <h2 class="text-2xl md:text-4xl font-black text-white uppercase tracking-tighter mb-10">Qo'llash Sohalari</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6 w-full">
                @php
                    $fields = [
                        ['icon' => 'fa-graduation-cap', 'name' => 'O\'quv Markazlari'],
                        ['icon' => 'fa-hospital', 'name' => 'Tibbiyot'],
                        ['icon' => 'fa-shop', 'name' => 'Savdo'],
                        ['icon' => 'fa-industry', 'name' => 'Ishlab Chiqarish'],
                        ['icon' => 'fa-hotel', 'name' => 'Xizmatlar'],
                        ['icon' => 'fa-building-columns', 'name' => 'Konsalting'],
                    ];
                @endphp
                @foreach($fields as $f)
                    <div class="p-5 md:p-6 rounded-3xl bg-white/5 border border-white/5 hover:bg-white/10 hover:border-purple-500/30 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center space-y-4">
                        <i class="fa-solid {{ $f['icon'] }} text-2xl md:text-3xl text-purple-400 opacity-80 group-hover:opacity-100 transition-opacity"></i>
                        <div class="text-[9px] md:text-[10px] font-black text-white/60 uppercase tracking-widest text-center">{{ $f['name'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Core Modules Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-20 md:mb-32">
        @php
            $features = [
                ['icon' => 'fa-microchip', 'color' => 'cyan', 'title' => 'CRM & ERP', 'desc' => 'Samarali boshqaruv.'],
                ['icon' => 'fa-graduation-cap', 'color' => 'purple', 'title' => 'ACADEMY', 'desc' => 'O\'quv jarayoni.'],
                ['icon' => 'fa-vault', 'color' => 'pink', 'title' => 'TREASURY', 'desc' => 'Moliyaviy shaffoflik.'],
                ['icon' => 'fa-shield-halved', 'color' => 'blue', 'title' => 'SECURITY', 'desc' => 'Ma\'lumotlar himoyasi.']
            ];
        @endphp
        @foreach($features as $f)
            <div class="glass-panel p-6 md:p-8 space-y-3 md:space-y-4 hover:border-{{ $f['color'] }}-400/30 transition-all group">
                <i class="fa-solid {{ $f['icon'] }} text-2xl md:text-3xl text-{{ $f['color'] }}-400 group-hover:scale-110 transition-transform"></i>
                <h3 class="text-white font-black uppercase tracking-widest text-[10px] md:text-xs">{{ $f['title'] }}</h3>
                <p class="text-[9px] md:text-[10px] text-white/40 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Footer Node -->
    <div class="pt-8 md:pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-8">
        <div class="flex items-center gap-4 md:gap-6">
            <span class="text-[8px] md:text-[11px] font-black text-white/20 uppercase tracking-[0.4em] md:tracking-[0.8em] text-center">© 2026 ITCLOUD NEURAL NETWORKS</span>
        </div>
        <div class="flex gap-6 md:gap-8">
            <a href="#" class="text-[9px] md:text-[10px] font-black text-white/30 hover:text-white uppercase tracking-widest transition-all">Documentation</a>
            <a href="#" class="text-[9px] md:text-[10px] font-black text-white/30 hover:text-white uppercase tracking-widest transition-all">Support</a>
        </div>
    </div>
</div>
