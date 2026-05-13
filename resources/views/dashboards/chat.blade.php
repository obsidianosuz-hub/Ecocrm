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
<div class="mb-4 flex flex-col md:flex-row justify-between md:items-end border-b border-[var(--border-color)] border-opacity-30 pb-4 shrink-0">
    <div class="flex items-center gap-4">
        <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-[var(--electric-blue)] hover:border-[var(--electric-blue)] transition-all shrink-0">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div>
            <h1 class="text-2xl md:text-3xl font-orbitron font-bold tracking-widest text-[var(--electric-blue)] drop-shadow-[0_0_10px_var(--electric-blue)] uppercase">The Syndicate Chat</h1>
            <p class="text-sm opacity-70 mt-1 font-mono tracking-widest uppercase text-[var(--text-color)]">Xodimlar o'rtasida xavfsiz va tezkor yozishmalar</p>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="p-3 mb-4 border border-[var(--active-color)] bg-[var(--active-color)] text-[var(--bg-color)] font-bold uppercase tracking-widest text-xs shrink-0 drop-shadow-[0_0_5px_var(--active-color)]">
        >> {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="p-3 mb-4 border border-red-500 bg-red-500 bg-opacity-10 text-red-500 font-bold uppercase tracking-widest text-xs shrink-0 drop-shadow-[0_0_5px_rgba(255,0,0,0.5)]">
        >> {{ session('error') }}
    </div>
@endif

<div x-data="{ activeTab: 'chat', editMsgId: null, editMsgText: '' }" class="flex-1 flex flex-col min-h-0">
    <div class="flex gap-2 md:gap-4 mb-4 border-b border-[var(--border-color)] border-opacity-30 pb-2 shrink-0 overflow-x-auto slim-scroll">
        <button @click="activeTab = 'chat'" class="px-4 py-2 text-xs font-bold uppercase tracking-widest transition-all border-b-2 whitespace-nowrap" :class="activeTab === 'chat' ? 'border-[var(--electric-blue)] text-[var(--electric-blue)] drop-shadow-[0_0_5px_var(--electric-blue)]' : 'border-transparent text-[var(--text-color)] opacity-60 hover:opacity-100'">
            Jamoa Chati
        </button>
        <button @click="activeTab = 'tasks'" class="px-4 py-2 text-xs font-bold uppercase tracking-widest transition-all border-b-2 whitespace-nowrap" :class="activeTab === 'tasks' ? 'border-[var(--cyber-yellow)] text-[var(--cyber-yellow)] drop-shadow-[0_0_5px_var(--cyber-yellow)]' : 'border-transparent text-[var(--text-color)] opacity-60 hover:opacity-100'">
            Vazifalar & Nazorat <span class="bg-[var(--cyber-yellow)] text-[var(--bg-color)] px-1.5 py-0.5 ml-2 font-mono text-xs">{{ count($tasks) }}</span>
        </button>
    </div>

    <!-- Chat Tab -->
    <div x-show="activeTab === 'chat'" class="cyber-panel flex flex-col overflow-hidden w-full flex-1">
        <div class="bg-black bg-opacity-50 p-3 border-b border-[var(--border-color)] border-opacity-30 w-full flex justify-between items-center text-[var(--text-color)] shrink-0">
            <span class="font-orbitron font-bold text-sm tracking-widest uppercase flex items-center gap-2 text-[var(--electric-blue)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                Umumiy Guruh
            </span>
            <div class="flex items-center gap-4 text-xs font-mono">
                <span class="text-[var(--bg-color)] bg-[var(--active-color)] font-bold px-2 py-0.5 border border-[var(--active-color)] flex items-center gap-1 shadow-[0_0_5px_var(--active-color)]">
                    <span class="w-2 h-2 rounded-full bg-[var(--bg-color)] animate-ping inline-block shadow-[0_0_5px_var(--bg-color)]"></span> Online Aktiv
                </span>
                @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('chat.clear') }}" onsubmit="return confirm('Barcha xabarlarni butunlay o\'chirib tashlaysizmi?')">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 px-2 py-1 uppercase tracking-widest border border-red-500 border-opacity-30 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-[0_0_10px_rgba(255,0,0,0.2)]">Tozalash</button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 slim-scroll bg-black bg-opacity-50" id="chat-container">
            @forelse($messages as $msg)
                @if($msg->sender_id == auth()->id())
                    <div class="flex justify-end relative group">
                        <div class="mr-2 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                            <button @click="editMsgId = {{ $msg->id }}; editMsgText = '{{ addslashes(str_replace(' (tahrirlandi)', '', $msg->message)) }}'" class="text-sm text-[var(--electric-blue)] hover:text-white uppercase"><svg class="w-4 h-4" transform="scale(-1, 1)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                            <form method="POST" action="{{ route('chat.message.delete', $msg->id) }}" onsubmit="return confirm('Xabarni o\'chirasizmi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-white uppercase"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                        </div>
                        <div class="max-w-[80%] md:max-w-[70%] text-right font-mono">
                            <div class="bg-[var(--electric-blue)] text-[var(--bg-color)] font-bold border border-[var(--electric-blue)] p-3 shadow-[0_0_10px_rgba(0,240,255,0.4)] mb-1 text-xs md:text-sm text-left inline-block">
                                {{ $msg->message }}
                                @if($msg->file_path)
                                    <div class="mt-2 text-xs border-t border-[var(--bg-color)] border-opacity-30 pt-2">
                                        <a href="/storage/{{ $msg->file_path }}" target="_blank" class="underline flex items-center gap-1 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg> 
                                            FILE_ATTACHMENT
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs text-[var(--text-color)] opacity-60 pr-1 uppercase tracking-wider">Siz • {{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start relative group">
                        <div class="max-w-[80%] md:max-w-[70%] font-mono">
                            <div class="text-sm font-bold mb-1 pl-1 tracking-widest uppercase" style="color: {{ $msg->sender->role == 'admin' ? '#ef4444' : ($msg->sender->role == 'operator' ? 'var(--electric-blue)' : 'var(--active-color)') }}">
                                {{ $msg->sender->name ?? 'Tizim' }} ({{ ucfirst($msg->sender->role ?? 'Bot') }})
                            </div>
                            <div class="bg-black bg-opacity-80 text-[var(--text-color)] p-3 border border-[var(--border-color)] border-opacity-50 mb-1 text-xs md:text-sm inline-block {{ str_contains($msg->message, 'SYSTEM ALERT') || str_contains($msg->message, 'NEW DIRECTIVE') ? 'border-[var(--cyber-yellow)] text-[var(--cyber-yellow)] shadow-[inset_0_0_10px_rgba(252,238,10,0.2)]' : '' }}">
                                {{ $msg->message }}
                                @if($msg->file_path)
                                    <div class="mt-2 text-xs border-t border-[var(--text-color)] border-opacity-30 pt-2">
                                        <a href="/storage/{{ $msg->file_path }}" target="_blank" class="underline flex items-center gap-1 text-[var(--electric-blue)] hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg> 
                                            INCOMING_FILE
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs text-[var(--text-color)] opacity-60 pl-1 uppercase tracking-wider">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity flex items-center mt-4">
                            <form method="POST" action="{{ route('chat.message.delete', $msg->id) }}" onsubmit="return confirm('Xabarni o\'chirasizmi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-white uppercase"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                        </div>
                        @endif
                    </div>
                @endif
            @empty
                <div class="h-full flex flex-col items-center justify-center opacity-40 text-center text-[var(--text-color)] font-mono text-xs uppercase tracking-widest">
                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <p>Hech qanday xabar yo'q.<br>Suhbatni boshlash uchun pastga yozing.</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-black bg-opacity-50 border-t border-[var(--border-color)] border-opacity-30 shrink-0" x-data="{ fileName: '' }">
            <!-- Normal Send -->
            <form x-show="!editMsgId" method="POST" action="{{ route('chat.send') }}" enctype="multipart/form-data" class="flex gap-2 items-center relative">
                @csrf
                <label class="cursor-pointer bg-[var(--bg-color)] p-2 border border-[var(--border-color)] border-opacity-50 text-[var(--electric-blue)] hover:border-[var(--electric-blue)] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    <input type="file" name="attachment" class="hidden" x-ref="fileInput" @change="fileName = $refs.fileInput.files[0]?.name || ''">
                </label>
                
                <div class="flex-1 bg-[var(--bg-color)] border border-[var(--border-color)] flex items-center px-3 py-1 focus-within:border-[var(--electric-blue)] focus-within:shadow-[0_0_10px_rgba(0,240,255,0.2)] transition-colors relative">
                    <div class="text-[var(--electric-blue)] mr-2 font-mono text-xs">></div>
                    <input type="text" name="message" autofocus placeholder="Xabar yozing (CMD_INPUT)..." class="w-full bg-transparent text-[var(--text-color)] font-mono text-xs py-1.5 focus:outline-none placeholder-opacity-50 uppercase" autocomplete="off">
                    <!-- File Name Indicator -->
                    <div x-show="fileName" class="absolute -top-6 left-0 text-xs text-[var(--cyber-yellow)] tracking-widest bg-[var(--bg-color)] border border-[var(--cyber-yellow)] px-2 py-0.5 truncate max-w-full" x-text="'ATTACHED: ' + fileName" style="display: none;"></div>
                </div>
                
                <button type="submit" class="bg-[var(--electric-blue)] text-[var(--bg-color)] px-4 py-2 hover:bg-transparent hover:text-[var(--electric-blue)] hover:border-[var(--electric-blue)] border border-transparent transition-all flex items-center justify-center uppercase tracking-widest font-bold text-sm">
                    SEND_TX
                </button>
            </form>

            <!-- Edit Send -->
            <form x-show="editMsgId" method="POST" :action="'/chat/message/' + editMsgId" class="flex gap-2 items-center" style="display: none;">
                @csrf
                <div class="flex-1 bg-[var(--bg-color)] border border-[var(--cyber-yellow)] flex items-center px-3 py-1 shadow-[0_0_10px_rgba(252,238,10,0.2)]">
                    <div class="text-[var(--cyber-yellow)] mr-2 font-mono text-xs">EDIT></div>
                    <input type="text" name="message" x-model="editMsgText" required class="w-full bg-transparent text-[var(--cyber-yellow)] font-mono text-xs py-1.5 focus:outline-none uppercase" autocomplete="off">
                </div>
                <button type="button" @click="editMsgId = null" class="bg-transparent border border-[var(--border-color)] text-[var(--text-color)] px-3 py-2 hover:text-white transition-colors uppercase tracking-widest text-sm">
                    CANCEL
                </button>
                <button type="submit" class="bg-[var(--cyber-yellow)] text-[var(--bg-color)] px-4 py-2 hover:bg-transparent hover:text-[var(--cyber-yellow)] border border-[var(--cyber-yellow)] transition-colors flex items-center justify-center font-bold tracking-widest text-sm uppercase shadow-[0_0_10px_rgba(252,238,10,0.3)]">
                    UPDATE
                </button>
            </form>
        </div>
    </div>
    
    <!-- Tasks Section -->
    <div x-show="activeTab === 'tasks'" style="display: none;" class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1 min-h-0">

        <!-- Assign Task Form (Admin & Cashier) -->
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cashier')
        <div class="cyber-panel p-4 flex flex-col md:col-span-1 shrink-0 bg-opacity-20 border-[var(--cyber-yellow)]">
            <h3 class="font-orbitron font-bold text-[var(--cyber-yellow)] mb-3 border-b border-[var(--cyber-yellow)] border-opacity-30 pb-2 flex justify-between items-center text-xs uppercase tracking-widest">
                Topshiriq Berish Center
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </h3>
            <form method="POST" action="{{ route('chat.task.assign') }}" class="space-y-3 font-mono">
                @csrf
                <div>
                    <label class="block text-[var(--text-color)] opacity-70 mb-1 text-[10px] uppercase tracking-widest font-black">Agent (Mijrochi)</label>
                    <select name="assigned_to" required class="w-full bg-black/40 border border-white/10 p-2 text-[var(--electric-blue)] focus:outline-none focus:border-[var(--cyber-yellow)] text-xs appearance-none font-bold">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} [{{ strtoupper($u->role) }}]</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[var(--text-color)] opacity-70 mb-1 text-[10px] uppercase tracking-widest font-black">Mission Objective</label>
                    <input type="text" name="title" required placeholder="DIRETIVE_TITLE_01" class="w-full bg-black/40 border border-white/10 p-2 text-white focus:outline-none focus:border-[var(--cyber-yellow)] text-xs uppercase placeholder-opacity-20 font-bold">
                </div>
                <div>
                    <label class="block text-[var(--text-color)] opacity-70 mb-1 text-[10px] uppercase tracking-widest font-black">Operational Intel (Tavsif)</label>
                    <textarea name="description" required rows="2" class="w-full bg-black/40 border border-white/10 p-2 text-white focus:outline-none focus:border-[var(--cyber-yellow)] text-[11px] resize-none placeholder-opacity-20 uppercase" placeholder="DECRYPTED_DETAILS..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-red-500 mb-1 text-[10px] uppercase tracking-widest font-black">Penalty (Fine)</label>
                        <input type="number" name="fine_amount" value="50000" required class="w-full bg-red-500/10 border border-red-500/30 p-2 text-red-500 focus:outline-none focus:border-red-500 text-xs font-black">
                    </div>
                    <div>
                        <label class="block text-green-400 mb-1 text-[10px] uppercase tracking-widest font-black">Reward (Bonus)</label>
                        <input type="number" name="reward_amount" value="0" required class="w-full bg-green-500/10 border border-green-500/30 p-2 text-green-400 focus:outline-none focus:border-green-500 text-xs font-black">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[var(--cyber-yellow)] mb-1 text-[10px] uppercase tracking-widest font-black">XP Yield</label>
                        <input type="number" name="xp_reward" value="100" required class="w-full bg-[var(--cyber-yellow)]/10 border border-[var(--cyber-yellow)]/30 p-2 text-[var(--cyber-yellow)] focus:outline-none focus:border-[var(--cyber-yellow)] text-xs font-black">
                    </div>
                    <div>
                        <label class="block text-[var(--electric-blue)] mb-1 text-[10px] uppercase tracking-widest font-black">Sync Deadline</label>
                        <input type="datetime-local" name="deadline" required class="w-full bg-[var(--electric-blue)]/10 border border-[var(--electric-blue)]/30 p-2 text-[var(--electric-blue)] focus:outline-none focus:border-[var(--electric-blue)] text-xs font-bold">
                    </div>
                </div>
                <button type="submit" class="w-full py-3 bg-[var(--cyber-yellow)] text-[var(--bg-color)] font-black text-xs uppercase tracking-[0.2em] mt-2 hover:bg-white transition-all shadow-[0_0_20px_rgba(252,238,10,0.3)] group">
                    <span class="group-hover:scale-110 inline-block transition-transform">Initiate Directive</span>
                </button>
            </form>
        </div>
        @else
        <!-- Staff View Placeholder if needed -->
        <div class="cyber-panel p-6 flex flex-col md:col-span-1 shrink-0 bg-opacity-20 border-[var(--electric-blue)] justify-center items-center text-center">
            <i class="fa-solid fa-user-secret text-4xl text-[var(--electric-blue)] opacity-30 mb-4 animate-pulse"></i>
            <h3 class="text-xs font-black uppercase tracking-widest opacity-50">Operational Agent Hub</h3>
            <p class="text-[10px] opacity-30 mt-2">Siz faqat berilgan vazifalarni bajarish va hisobot berish huquqiga egasiz.</p>
        </div>
        @endif

        <!-- Active Tasks List -->
        <div class="cyber-panel p-4 flex flex-col md:col-span-2 overflow-y-auto slim-scroll relative border border-white/5 shadow-[inset_0_0_20px_rgba(255,255,255,0.02)]">
            <h3 class="font-orbitron font-bold text-white mb-4 border-b border-white/5 pb-2 flex justify-between items-center sticky top-0 bg-black/60 backdrop-blur-lg pt-2 z-10 uppercase tracking-[0.2em] text-xs">
                Active Directives Node
                <span class="bg-white text-black text-[10px] px-2 py-0.5 font-black shadow-[0_0_10px_rgba(255,255,255,0.3)]">{{ count($tasks) }} UNITS</span>
            </h3>
            
            <div class="space-y-4 font-mono w-full">
                @forelse($tasks as $task)
                    <div x-data="taskComponent('{{ $task->deadline }}', '{{ $task->status }}')" class="p-5 border transition-all relative overflow-hidden group" :class="status === 'completed' ? 'border-green-500/20 bg-green-500/5 opacity-60' : (status != 'completed' && timeLeft <= 0 ? 'border-red-500 shadow-[inset_0_0_20px_rgba(255,0,0,0.1)]' : 'border-white/5 bg-white/[0.02] hover:bg-white/[0.05]')">
                        
                        <!-- Header: Title & Identity -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <template x-if="status === 'pending'">
                                        <span class="text-[9px] font-black bg-cyan-500 text-black px-2 py-0.5 uppercase tracking-widest animate-pulse">Live</span>
                                    </template>
                                    <template x-if="status === 'awaiting_verification'">
                                        <span class="text-[9px] font-black bg-purple-500 text-white px-2 py-0.5 uppercase tracking-widest">Awaiting Verification</span>
                                    </template>
                                    <template x-if="status === 'extension_pending'">
                                        <span class="text-[9px] font-black bg-yellow-500 text-black px-2 py-0.5 uppercase tracking-widest">Extension Requested</span>
                                    </template>
                                    <template x-if="status === 'completed'">
                                        <span class="text-[9px] font-black bg-green-500 text-black px-2 py-0.5 uppercase tracking-widest">Archive Fixed</span>
                                    </template>
                                    <template x-if="status === 'failed'">
                                        <span class="text-[9px] font-black bg-red-500 text-white px-2 py-0.5 uppercase tracking-widest">Failed Directive</span>
                                    </template>
                                    <span class="text-[9px] text-white/30 uppercase tracking-widest">REF_ID: #{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h4 class="font-black text-sm text-white uppercase tracking-wider leading-tight">{{ $task->title }}</h4>
                            </div>
                            
                            <!-- Countdown Display -->
                            <div x-show="status === 'pending' || status === 'extension_pending' || status === 'awaiting_verification'" class="text-right">
                                <div class="text-[18px] font-black leading-none" :class="timeLeft < 3600 ? 'text-red-500 animate-pulse' : 'text-cyan-400'" x-text="countdownText"></div>
                                <div class="text-[8px] text-white/30 uppercase tracking-[0.3em] mt-1">Remaining Time</div>
                            </div>
                        </div>

                        <!-- Intel Details -->
                        <div class="mb-4">
                            <p class="text-[11px] text-white/60 leading-relaxed mb-4 p-3 bg-black/40 border-l border-white/10 italic">"{{ $task->description }}"</p>
                            
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                                <div class="bg-black/20 p-2 border border-white/5">
                                    <div class="text-[8px] text-white/30 uppercase tracking-widest mb-1">Assigned To</div>
                                    <div class="text-[10px] font-bold text-cyan-400">{{ $task->assignee->name ?? 'UNKNOWN' }}</div>
                                </div>
                                <div class="bg-black/20 p-2 border border-white/5">
                                    <div class="text-[8px] text-white/30 uppercase tracking-widest mb-1">Penalty</div>
                                    <div class="text-[10px] font-bold text-red-500">{{ number_format($task->fine_amount) }} UZS</div>
                                </div>
                                <div class="bg-black/20 p-2 border border-white/5">
                                    <div class="text-[8px] text-white/30 uppercase tracking-widest mb-1">Bonus Yield</div>
                                    <div class="text-[10px] font-bold text-green-400">{{ number_format($task->reward_amount) }} UZS / {{ $task->xp_reward }} XP</div>
                                </div>
                                <div class="bg-black/20 p-2 border border-white/5">
                                    <div class="text-[8px] text-white/30 uppercase tracking-widest mb-1">Sync Deadline</div>
                                    <div class="text-[10px] font-bold text-white/60">{{ \Carbon\Carbon::parse($task->deadline)->format('H:i d.m.Y') }}</div>
                                </div>
                            </div>
                        </div>

                         <!-- Proof View -->
                         @if($task->proof_file)
                            <div class="mb-4 p-3 bg-cyan-400/5 border border-cyan-400/20 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-file-shield text-cyan-400 text-xl"></i>
                                    <div>
                                        <div class="text-[10px] font-black text-cyan-400 uppercase tracking-widest">Evidence Node Connected</div>
                                        <div class="text-[10px] text-white/40 font-mono">{{ basename($task->proof_file) }}</div>
                                    </div>
                                </div>
                                <a href="/storage/{{ $task->proof_file }}" target="_blank" class="text-[10px] font-black bg-cyan-400 text-black px-4 py-1.5 uppercase hover:bg-white transition-all">Download Proof</a>
                            </div>
                        @endif

                        @if($task->extension_requested)
                            <div class="mb-4 p-3 bg-yellow-500/10 border border-yellow-500/30 text-yellow-500 rounded-lg text-xs italic">
                                <strong class="uppercase not-italic text-[9px] block mb-1">Extension Justification:</strong>
                                "{{ $task->extension_reason }}"
                            </div>
                        @endif

                        <!-- Action Controls (Agent) -->
                        @if($task->assigned_to == auth()->id() && $task->status != 'completed' && $task->status != 'failed')
                        <div x-data="{ showSubmit: false, showExtend: false, showFail: false }" class="mt-4 pt-4 border-t border-white/5">
                            <div x-show="!showSubmit && !showExtend && !showFail" class="flex flex-wrap gap-2">
                                <button @click="showSubmit = true" class="flex-1 py-2 bg-green-500 text-black font-black text-[10px] uppercase tracking-widest hover:bg-white transition-all shadow-[0_0_15px_rgba(34,197,94,0.3)]">Mission Accomplished (Bajarildi)</button>
                                <button @click="showExtend = true" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/50 text-yellow-500 font-black text-[10px] uppercase tracking-widest hover:bg-yellow-500 hover:text-black transition-all">Request Delay (Jarayonda)</button>
                                <button @click="showFail = true" class="px-4 py-2 bg-red-500/10 border border-red-500/50 text-red-500 font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">Abort (Bajarilmadi)</button>
                            </div>

                            <form x-show="showSubmit" method="POST" action="{{ route('chat.task.submit', $task->id) }}" enctype="multipart/form-data" class="space-y-3" style="display: none;">
                                @csrf
                                <input type="hidden" name="status" value="done">
                                <div class="bg-black/60 p-4 border border-green-500/30">
                                    <label class="block text-[10px] font-black text-green-400 uppercase mb-2">Upload Mission Evidence (PDF, PNG, JPG)</label>
                                    <input type="file" name="proof_file" required class="block w-full text-xs text-white/40 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-[10px] file:font-black file:bg-green-500 file:text-black hover:file:bg-white">
                                    <div class="flex gap-2 mt-4">
                                        <button type="submit" class="flex-1 py-2 bg-green-500 text-black font-black text-[10px] uppercase">Upload & Submit</button>
                                        <button type="button" @click="showSubmit = false" class="px-4 py-2 border border-white/10 text-white/50 text-[10px] uppercase">Cancel</button>
                                    </div>
                                </div>
                            </form>

                            <form x-show="showExtend" method="POST" action="{{ route('chat.task.submit', $task->id) }}" class="space-y-3" style="display: none;">
                                @csrf
                                <input type="hidden" name="status" value="in_progress">
                                <div class="bg-black/60 p-4 border border-yellow-500/30">
                                    <label class="block text-[10px] font-black text-yellow-500 uppercase mb-2">Extension Reason</label>
                                    <textarea name="extension_reason" required class="w-full bg-black/40 border border-white/10 p-2 text-white text-xs uppercase placeholder-opacity-20 resize-none" placeholder="WHY_DO_YOU_NEED_MORE_TIME?"></textarea>
                                    <div class="flex gap-2 mt-4">
                                        <button type="submit" class="flex-1 py-2 bg-yellow-500 text-black font-black text-[10px] uppercase">Submit Request</button>
                                        <button type="button" @click="showExtend = false" class="px-4 py-2 border border-white/10 text-white/50 text-[10px] uppercase">Cancel</button>
                                    </div>
                                </div>
                            </form>

                            <form x-show="showFail" method="POST" action="{{ route('chat.task.submit', $task->id) }}" class="space-y-3" style="display: none;">
                                @csrf
                                <input type="hidden" name="status" value="failed">
                                <div class="bg-black/60 p-4 border border-red-500/30">
                                    <label class="block text-[10px] font-black text-red-500 uppercase mb-2">Failure Reason</label>
                                    <textarea name="extension_reason" required class="w-full bg-black/40 border border-white/10 p-2 text-white text-xs uppercase placeholder-opacity-20 resize-none" placeholder="WHY_WAS_IT_ABORTED?"></textarea>
                                    <div class="flex gap-2 mt-4">
                                        <button type="submit" class="flex-1 py-2 bg-red-500 text-white font-black text-[10px] uppercase">Submit Failure</button>
                                        <button type="button" @click="showFail = false" class="px-4 py-2 border border-white/10 text-white/50 text-[10px] uppercase">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Action Controls (Admin / Cashier) -->
                        @if((auth()->user()->role === 'admin' || auth()->user()->role === 'cashier') && $task->status != 'completed')
                            <div x-data="{ showExtendAdmin: false }" class="mt-4 pt-4 border-t border-white/5">
                                <div x-show="!showExtendAdmin" class="flex gap-2">
                                    @if($task->status == 'awaiting_verification')
                                        <form method="POST" action="{{ route('chat.task.verify', $task->id) }}" class="flex-1 flex gap-2">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="flex-1 py-2 bg-cyan-400 text-black font-black text-[10px] uppercase tracking-widest hover:bg-white transition-all shadow-[0_0_15px_rgba(0,240,255,0.3)]">Verify & Release Reward</button>
                                            <button type="submit" name="action" value="reject" class="px-4 py-2 border border-red-500 text-red-500 font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">Reject Proof</button>
                                        </form>
                                    @endif

                                    <button @click="showExtendAdmin = true" class="flex-1 py-2 border border-yellow-500/30 text-yellow-500 font-black text-[9px] uppercase tracking-widest hover:bg-yellow-500 hover:text-black transition-all">Manage Timeline (Extend)</button>

                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('chat.task.delete', $task->id) }}" onsubmit="return confirm('Wipe Directive Permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 px-3 border border-red-500/20 text-red-500/50 hover:bg-red-500 hover:text-white transition-all"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                        </form>
                                    @endif
                                </div>

                                <div x-show="showExtendAdmin" class="bg-black/60 p-4 border border-yellow-500/30 mt-2" style="display: none;">
                                    <form method="POST" action="{{ route('chat.task.verify', $task->id) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="extend">
                                        <label class="block text-[10px] font-black text-yellow-400 uppercase mb-2">Adjust Sync Deadline</label>
                                        <input type="datetime-local" name="new_deadline" required class="w-full bg-black/40 border border-white/10 p-2 text-white text-xs">
                                        <div class="flex gap-2 mt-4">
                                            <button type="submit" class="flex-1 py-2 bg-yellow-500 text-black font-black text-[10px] uppercase">Extend Mission</button>
                                            <button type="button" @click="showExtendAdmin = false" class="px-4 py-2 border border-white/10 text-white/50 text-[10px] uppercase">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center text-white/20 py-20 bg-white/[0.01] border border-dashed border-white/5 rounded-2xl">
                        <i class="fa-solid fa-ghost text-5xl mb-4"></i>
                        <p class="text-xs font-black uppercase tracking-[0.4em]">Zero Directives</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('taskComponent', (deadline, status) => ({
            deadline: new Date(deadline).getTime(),
            status: status,
            timeLeft: 0,
            countdownText: '00:00:00',
            timer: null,

            init() {
                this.updateCountdown();
                this.timer = setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },

            updateCountdown() {
                const now = new Date().getTime();
                this.timeLeft = Math.max(0, this.deadline - now);
                
                if (this.timeLeft <= 0) {
                    this.countdownText = 'FAILED';
                    clearInterval(this.timer);
                    return;
                }

                const hours = Math.floor(this.timeLeft / (1000 * 60 * 60));
                const minutes = Math.floor((this.timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((this.timeLeft % (1000 * 60)) / 1000);

                this.countdownText = 
                    String(hours).padStart(2, '0') + ':' + 
                    String(minutes).padStart(2, '0') + ':' + 
                    String(seconds).padStart(2, '0');
            }
        }));
    });

    document.addEventListener("DOMContentLoaded", function() {
        var chatDiv = document.getElementById("chat-container");
        if(chatDiv) {
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }
    });
</script>
@endsection
