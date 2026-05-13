@extends('layouts.cyber')

@section('sidebar')
    @include('partials.admin_sidebar')
@endsection

@section('content')
<div class="w-full flex-1 flex flex-col gap-6">
    <!-- Header with Filters -->
    <div class="glass-panel p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <button onclick="window.history.back()" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-cyan-400 hover:border-cyan-400/50 transition-all shrink-0">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <i class="fa-solid fa-ghost text-cyan-400 text-2xl"></i>
                <div>
                    <h1 class="text-xl font-orbitron font-bold text-white uppercase tracking-widest">Ghost Log Archive</h1>
                    <p class="text-[10px] text-cyan-400/60 font-black uppercase tracking-[0.2em]">Neural Pattern Repository</p>
                </div>
            </div>
            
            <form action="{{ route('admin.audit_logs.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <select name="user_id" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[11px] text-white outline-none focus:border-cyan-400/50">
                    <option value="">Filter by User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[11px] text-white outline-none focus:border-cyan-400/50">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[11px] text-white outline-none focus:border-cyan-400/50">
                
                <button type="submit" class="p-2.5 bg-cyan-400 text-black rounded-xl hover:bg-cyan-300 transition-all">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                
                <a href="{{ route('admin.audit_logs.pdf', request()->all()) }}" target="_blank" class="p-2.5 bg-purple-600 text-white rounded-xl hover:bg-purple-500 transition-all flex items-center gap-2 text-[11px] font-bold uppercase">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>PDF Export</span>
                </a>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="glass-panel p-6 flex-1 min-h-0 overflow-hidden flex flex-col">
        <div class="overflow-x-auto flex-1 slim-scroll pr-2">
            <table class="w-full text-left font-mono text-[11px]">
                <thead>
                    <tr class="text-white/40 border-b border-white/5 uppercase tracking-widest">
                        <th class="py-4 font-black">Timestamp</th>
                        <th class="py-4 font-black">Operative</th>
                        <th class="py-4 font-black">Directive / Action</th>
                        <th class="py-4 font-black">Data Payload</th>
                        <th class="py-4 font-black">Identity (IP)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($logs as $log)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="py-4 text-cyan-400/60 font-bold">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                            <td class="py-4 font-bold text-white">{{ $log->user->name ?? 'SYSTEM AUTO' }}</td>
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20 font-black uppercase text-[9px]">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="py-4 max-w-xs">
                                <div class="truncate text-white/50 group-hover:text-white/80 transition-colors" title="{{ json_encode($log->new_values) }}">
                                    @if($log->new_values)
                                        <code>{{ json_encode($log->new_values) }}</code>
                                    @else
                                        <span class="opacity-20 italic">No Payload</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 opacity-40">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center opacity-20 italic">
                                <i class="fa-solid fa-ghost text-4xl mb-4 block"></i>
                                NO NEURAL PATTERNS DETECTED IN SELECTED RANGE
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 pt-6 border-t border-white/5">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<style>
    /* Custom pagination styling to match the theme */
    .pagination { @apply flex gap-2; }
    .page-item { @apply rounded-lg overflow-hidden; }
    .page-link { @apply bg-white/5 border border-white/10 px-4 py-2 text-[11px] text-white/60 hover:bg-cyan-400 hover:text-black transition-all block; }
    .page-item.active .page-link { @apply bg-cyan-400 text-black border-cyan-400; }
    .page-item.disabled .page-link { @apply opacity-20 pointer-events-none; }
</style>
@endsection
