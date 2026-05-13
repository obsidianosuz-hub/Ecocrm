@extends('layouts.cyber')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('partials.admin_sidebar')
    @elseif(auth()->user()->role === 'cashier')
        @include('partials.cashier_sidebar')
    @elseif(auth()->user()->role === 'operator')
        @include('partials.operator_sidebar')
    @endif
@endsection

@section('content')
<div class="flex-1 min-h-0 flex flex-col gap-8 overflow-hidden pointer-events-auto">

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 shrink-0 px-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-1 h-8 bg-cyan-400"></div>
                <h1 class="text-3xl font-black text-white tracking-tighter uppercase">IDENTITY CONFIG</h1>
            </div>
            <p class="text-[11px] font-black uppercase tracking-[0.4em] text-white/30">Neural Authentication & Interface Parameters</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mx-4 p-4 glass-panel border-green-500/30 bg-green-500/5 text-green-400 text-[10px] font-black uppercase tracking-[0.3em] flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-sm"></i>
            <span>SYSTEM UPDATE SUCCESS: {{ session('success') }}</span>
        </div>
    @endif

    <div class="flex-1 overflow-y-auto slim-scroll px-4 pb-8">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Core Profile Configuration -->
            <div class="xl:col-span-2 space-y-8">
                <div class="glass-panel p-10">
                    <div class="panel-title mb-10">
                        <i class="fa-solid fa-user-gear text-cyan-400"></i>
                        <span>CORE USER PARAMETERS</span>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest block">Display Identity</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white focus:border-cyan-400/50 outline-none transition-all" placeholder="Enter Full Name">
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest block">Visual Avatar Node</label>
                                <div class="flex items-center gap-6">
                                    <div class="relative w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover" alt="Avatar">
                                        @else
                                            <i class="fa-solid fa-user text-white/10"></i>
                                        @endif
                                        <input type="file" name="user_avatar" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                            <i class="fa-solid fa-camera text-white/60 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] text-white/20 font-bold uppercase leading-relaxed">Select specialized image file for network representation.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest block">Neural Language</label>
                                <div class="relative">
                                    <select name="ui_language" class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white focus:border-cyan-400/50 outline-none transition-all appearance-none">
                                        <option value="uz" {{ old('ui_language', auth()->user()->ui_language) == 'uz' ? 'selected' : '' }}>Uzbek Language</option>
                                        <option value="ru" {{ old('ui_language', auth()->user()->ui_language) == 'ru' ? 'selected' : '' }}>Russian Language</option>
                                        <option value="en" {{ old('ui_language', auth()->user()->ui_language) == 'en' ? 'selected' : '' }}>English Language</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-white/20 pointer-events-none"></i>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest block">Visual Scale</label>
                                <div class="relative">
                                    <select name="ui_font_size" class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white focus:border-cyan-400/50 outline-none transition-all appearance-none">
                                        <option value="text-sm" {{ old('ui_font_size', auth()->user()->ui_font_size) == 'text-sm' ? 'selected' : '' }}>Standard Matrix</option>
                                        <option value="text-base" {{ old('ui_font_size', auth()->user()->ui_font_size) == 'text-base' ? 'selected' : '' }}>Extended Matrix</option>
                                        <option value="text-lg" {{ old('ui_font_size', auth()->user()->ui_font_size) == 'text-lg' ? 'selected' : '' }}>Macro Matrix</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-white/20 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/5">
                            <button type="submit" class="btn-ios btn-neon w-full py-5 text-[11px] font-black uppercase tracking-[0.3em] flex items-center justify-center gap-4">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>COMMIT IDENTITY UPDATES</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats & Network Status -->
            <div class="space-y-8">
                <div class="glass-panel p-10">
                    <div class="panel-title mb-10">
                        <i class="fa-solid fa-network-wired text-purple-400"></i>
                        <span>SYSTEM STATUS</span>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="p-6 bg-white/5 rounded-2xl border border-white/10 group hover:border-cyan-400/30 transition-all">
                            <div class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em] mb-3">Communication Node</div>
                            <div class="text-sm font-bold text-white tracking-tight" x-text="'{{ auth()->user()->email }}'"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-6 bg-white/5 rounded-2xl border border-white/10 group hover:border-purple-400/30 transition-all">
                                <div class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em] mb-3">Access Tier</div>
                                <div class="text-lg font-black text-purple-400 uppercase tracking-tighter" x-text="'{{ auth()->user()->role }}'"></div>
                            </div>
                            <div class="p-6 bg-white/5 rounded-2xl border border-white/10 group hover:border-cyan-400/30 transition-all">
                                <div class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em] mb-3">Exp Level</div>
                                <div class="text-lg font-black text-cyan-400 tracking-tighter" x-text="'{{ auth()->user()->xp }} XP'"></div>
                            </div>
                        </div>

                        <div class="p-8 bg-cyan-400/5 rounded-3xl border border-cyan-400/20 relative overflow-hidden group">
                            <div class="relative z-10">
                                <h3 class="text-xs font-black text-cyan-400 uppercase tracking-[0.2em] mb-4">OBSIDIAN OPERATIONAL STATUS</h3>
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></div>
                                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Connection Stable</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-robot absolute -right-4 -bottom-4 text-8xl text-cyan-400/5 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-700"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
