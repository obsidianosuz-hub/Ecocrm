@extends('layouts.cyber')

@section('content')
<div class="flex-1 min-h-0 flex flex-col gap-8 overflow-hidden">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 shrink-0 px-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-1 h-8 bg-purple-500"></div>
                <h1 class="text-3xl font-black text-white tracking-tighter uppercase">MASTER COMMAND CENTER</h1>
            </div>
            <p class="text-[11px] font-black uppercase tracking-[0.4em] text-white/30">System-level Node & Tenant Management</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mx-4 p-4 glass-panel border-green-500/30 bg-green-500/5 text-green-400 text-[10px] font-black uppercase tracking-[0.3em]">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex-1 overflow-y-auto slim-scroll px-4 pb-8 space-y-8">
        <!-- Pending Approval Section -->
        <div class="glass-panel p-8">
            <div class="panel-title mb-8">
                <i class="fa-solid fa-hourglass-half text-cyan-400"></i>
                <span>PENDING ADMIN REQUESTS</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendingCompanies as $company)
                    <div class="glass-panel p-6 bg-white/5 border-white/10 hover:border-cyan-400/30 transition-all">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-black text-white uppercase" x-text="'{{ $company->name }}'"></h3>
                                <p class="text-[9px] font-black text-white/30 tracking-widest mt-1 uppercase" x-text="'SLUG: {{ $company->slug }}'"></p>
                            </div>
                            <div class="px-2 py-1 rounded bg-cyan-400/10 text-cyan-400 text-[8px] font-black uppercase tracking-widest">AWAITING TASDICK</div>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-user-tie text-xs text-white/20"></i>
                                <span class="text-[11px] font-bold text-white/60 uppercase" x-text="'{{ $company->users->first()->name ?? 'N/A' }}'"></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-envelope text-xs text-white/20"></i>
                                <span class="text-[11px] font-bold text-white/60" x-text="'{{ $company->users->first()->email ?? 'N/A' }}'"></span>
                            </div>
                        </div>
                        
                        <form action="{{ route('master.companies.approve', $company->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full btn-ios btn-neon py-3 text-[10px] font-black uppercase tracking-widest">APPROVE & INITIALIZE NODE</button>
                        </form>
                    </div>
                @endforeach
                @if($pendingCompanies->isEmpty())
                    <div class="col-span-full py-20 flex flex-col items-center opacity-20">
                        <i class="fa-solid fa-ghost text-5xl mb-4"></i>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em]">Zero Pending Requests</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Active Ecosystem Section -->
        <div class="glass-panel p-8">
            <div class="panel-title mb-8">
                <i class="fa-solid fa-network-wired text-purple-400"></i>
                <span>ACTIVE ECOSYSTEM NODES</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-white/20 uppercase tracking-[0.2em] border-b border-white/5">
                            <th class="py-4 px-4">Node Name</th>
                            <th class="py-4 px-4">Identifier</th>
                            <th class="py-4 px-4">Active Staff</th>
                            <th class="py-4 px-4">Launch Date</th>
                            <th class="py-4 px-4">Operational Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeCompanies as $company)
                            <tr class="group hover:bg-white/5 transition-all text-xs border-b border-white/5">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-purple-400/30">
                                            <i class="fa-solid fa-building text-[10px] text-white/20"></i>
                                        </div>
                                        <span class="font-black text-white uppercase tracking-tight" x-text="'{{ $company->name }}'"></span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-white/40 font-mono uppercase" x-text="'{{ $company->slug }}'"></td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-0.5 rounded-full bg-cyan-400/10 text-cyan-400 font-black text-[9px]" x-text="'{{ $company->users_count }} OPERATIVES'"></span>
                                </td>
                                <td class="py-4 px-4 text-white/40" x-text="'{{ $company->created_at->format('Y-m-d H:i') }}'"></td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></div>
                                        <span class="text-[9px] font-black text-green-400 uppercase tracking-widest">Online</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
