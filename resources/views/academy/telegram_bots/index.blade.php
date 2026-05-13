@extends('layouts.cyber')

@section('sidebar')
    @include('partials.admin_sidebar')
@endsection

@section('content')
<div class="flex-1 min-h-0 overflow-y-auto w-full p-4 lg:p-8 space-y-6 slim-scroll">
    <div class="glass-panel p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6 pt-4">
            <div class="panel-title mb-0 shrink-0 flex items-center gap-4">
                <button onclick="window.history.back()" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-cyan-400 hover:border-cyan-400/50 transition-all shrink-0">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div>
                    <i class="fa-brands fa-telegram text-cyan-400"></i>
                    <span class="text-sm md:text-xl">TELEGRAM BOT (XABARNOMA)</span>
                </div>
            </div>
            
            @if(session('success'))
                <div class="text-green-400 text-xs font-bold font-mono py-2">{{ session('success') }}</div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add Bot Form -->
            <div class="lg:col-span-1">
                <div class="glass-panel bg-white/5 p-4 border border-white/10 relative">
                    <h3 class="text-xs font-mono mb-4 text-cyan-400 uppercase tracking-widest font-bold">Yangi Bot (Chat ID) Ulanish</h3>
                    <form action="{{ route('admin.academy.telegram_bots.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-white/50 tracking-widest mb-1">QISQACHA NOMI</label>
                            <input type="text" name="name" required placeholder="Masalan: FrontEnd guruhi bot" class="w-full bg-black/40 border border-white/10 rounded p-2 text-xs text-white focus:border-cyan-400/50 outline-none transition-all placeholder:text-white/20">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-white/50 tracking-widest mb-1">BOT TOKEN (BotFather)</label>
                            <input type="text" name="bot_token" required placeholder="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" class="w-full bg-black/40 border border-white/10 rounded p-2 text-xs text-white focus:border-cyan-400/50 outline-none transition-all placeholder:text-white/20">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-white/50 tracking-widest mb-1">GURUH CHAT ID</label>
                            <input type="text" name="chat_id" required placeholder="-1001234567890" class="w-full bg-black/40 border border-white/10 rounded p-2 text-xs text-white focus:border-cyan-400/50 outline-none transition-all placeholder:text-white/20">
                            <p class="text-[9px] text-white/30 mt-1">Botni shu guruhga qo'shib, unga admin huquqini bering.</p>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full py-2 bg-cyan-500/20 text-cyan-400 border border-cyan-500 text-xs font-bold uppercase tracking-widest hover:bg-cyan-500 hover:text-black transition-colors rounded-sm shadow-[0_0_15px_rgba(0,255,204,0.3)] hover:shadow-none">ULASH</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Bots -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($bots as $bot)
                    <div class="glass-panel bg-white/5 border border-white/10 p-4 transition-colors relative flex flex-col h-full">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <i class="fa-brands fa-telegram text-2xl text-blue-400"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-white uppercase">{{ $bot->name }}</h4>
                                    <span class="text-[10px] font-mono text-cyan-400">ID: {{ $bot->id }}</span>
                                </div>
                            </div>
                            <form action="{{ route('admin.academy.telegram_bots.destroy', $bot->id) }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-white/40 hover:text-red-400 transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div class="mt-4 space-y-2 mt-auto">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-white/40 font-bold mb-1">CHAT ID</span>
                                <span class="text-xs font-mono text-white/80 bg-black/40 px-2 py-1 rounded truncate border border-white/5">{{ $bot->chat_id }}</span>
                            </div>
                            <!-- Security measure: mask token mostly -->
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-white/40 font-bold mb-1">BOT TOKEN</span>
                                <span class="text-[10px] font-mono text-white/50 truncate">{{ substr($bot->bot_token, 0, 10) }}••••••••••</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="md:col-span-2 py-10 flex flex-col items-center justify-center opacity-30 border border-dashed border-white/20 rounded-xl">
                        <i class="fa-solid fa-robot text-4xl mb-4"></i>
                        <p class="text-xs font-bold font-mono tracking-widest uppercase">Telegram botlar ulanmagan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
