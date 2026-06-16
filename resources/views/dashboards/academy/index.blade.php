@extends('layouts.cyber')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('partials.admin_sidebar')
    @elseif(auth()->user()->role === 'cashier')
        @include('partials.cashier_sidebar')
    @endif
@endsection

@section('content')
<div class="w-full flex-1 flex flex-col gap-6" x-data="academyHub()">
    <!-- Header with back button -->
    <div class="flex items-center gap-4 mt-2 mb-2">
        <button onclick="window.history.back()" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-cyan-400 hover:border-cyan-400/50 transition-all shrink-0">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <h1 class="text-xl md:text-2xl font-orbitron font-bold tracking-widest text-cyan-400 uppercase">ACADEMY HUB</h1>
    </div>

    <!-- Quick Actions Row -->
    <div class="flex flex-wrap gap-4 mb-2">
        <a href="{{ route('admin.academy.students.index') }}" class="px-4 py-2 bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 hover:bg-cyan-500 hover:text-black transition-all text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> O'QUVCHI QO'SHISH
        </a>
        <button @click="showAddTeacherModal = true" class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-black transition-all text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-2">
            <i class="fa-solid fa-chalkboard-user"></i> O'QITUVCHI QO'SHISH
        </button>
        <a href="{{ route('admin.academy.groups.index') }}" class="px-4 py-2 bg-purple-500/10 text-purple-400 border border-purple-500/20 hover:bg-purple-500 hover:text-black transition-all text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i> GURUH OCHISH
        </a>
        <a href="{{ route('admin.academy.courses.index') }}" class="px-4 py-2 bg-pink-500/10 text-pink-400 border border-pink-500/20 hover:bg-pink-500 hover:text-black transition-all text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-2">
            <i class="fa-solid fa-book-medical"></i> KURS YARATISH
        </a>
        <a href="{{ route('admin.academy.rooms.index') }}" class="px-4 py-2 bg-white/5 text-white/60 border border-white/20 hover:bg-white hover:text-black transition-all text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-2">
            <i class="fa-solid fa-door-open"></i> YANGI XONA
        </a>
        <button @click="showAttendanceModal = true" class="px-4 py-2 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 hover:bg-yellow-500 hover:text-black transition-all text-[10px] font-bold uppercase tracking-widest rounded flex items-center gap-2">
            <i class="fa-solid fa-clipboard-check"></i> BAHO VA DAVOMAT
        </button>
    </div>

    <!-- Top Stats Grid -->
    <div class="stats-grid">
        <a href="{{ route('admin.academy.students.index') }}" class="stat-card hover:bg-white/5 transition-all group" style="border-left: 4px solid var(--neon-cyan); border-image: none;">
            <div class="stat-title group-hover:text-cyan-400">Jami O'quvchilar</div>
            <div class="stat-value">{{ $studentsCount }}</div>
            <div class="text-[10px] mt-2 text-cyan-400 font-bold uppercase tracking-widest"><i class="fa-solid fa-users"></i> Baza boshqaruvi</div>
        </a>
        <a href="{{ route('admin.academy.groups.index') }}" class="stat-card hover:bg-white/5 transition-all group" style="border-left: 4px solid var(--neon-purple); border-image: none;">
            <div class="stat-title group-hover:text-purple-400">Faol Guruhlar</div>
            <div class="stat-value">{{ $groupsCount }}</div>
            <div class="text-[10px] mt-2 text-purple-400 font-bold uppercase tracking-widest"><i class="fa-solid fa-layer-group"></i> Jadval nazorati</div>
        </a>
        <a href="{{ route('admin.academy.courses.index') }}" class="stat-card hover:bg-white/5 transition-all group" style="border-left: 4px solid var(--neon-pink); border-image: none;">
            <div class="stat-title group-hover:text-pink-400">Kurslar</div>
            <div class="stat-value">{{ $coursesCount }}</div>
            <div class="text-[10px] mt-2 text-pink-400 font-bold uppercase tracking-widest"><i class="fa-solid fa-book-open"></i> O'quv dasturlari</div>
        </a>
        <a href="{{ route('admin.academy.rooms.index') }}" class="stat-card hover:bg-white/5 transition-all group" style="border-left: 4px solid #fff; border-image: none;">
            <div class="stat-title group-hover:text-white">Xonalar</div>
            <div class="stat-value">{{ $roomsCount }}</div>
            <div class="text-[10px] mt-2 text-white/50 font-bold uppercase tracking-widest"><i class="fa-solid fa-door-open"></i> Bandlik vizualizatsiyasi</div>
        </a>
    </div>

    <!-- Main Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-1 min-h-[400px]">
        <!-- Recent Students -->
        <div class="glass-panel p-6 flex flex-col h-full">
            <div class="panel-title flex justify-between items-center mb-6">
                <div>
                    <i class="fa-solid fa-user-astronaut text-cyan-400 mr-2"></i>
                    <span>Yangi O'quvchilar</span>
                </div>
                <button class="btn-ios text-[10px] py-1 px-3 border border-white/10 hover:bg-white/5">ARCHIVE <i class="fa-solid fa-chevron-right text-[8px] ml-1"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto slim-scroll space-y-3">
                @forelse($recentStudents as $student)
                <div class="p-3 rounded-2xl bg-white/5 border border-white/5 flex justify-between items-center hover:bg-white/10 transition-colors">
                    <div>
                        <div class="font-bold text-sm">{{ $student->name }}</div>
                        <div class="text-[10px] text-white/50 font-mono">{{ $student->phone }}</div>
                    </div>
                    <div class="text-[10px] font-bold px-2 py-1 bg-cyan-500/10 text-cyan-400 rounded border border-cyan-500/20 uppercase">
                        {{ $student->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full opacity-30 italic">
                    <i class="fa-solid fa-user-slash text-4xl mb-4"></i>
                    <p>O'quvchilar topilmadi</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Active Groups -->
        <div class="glass-panel p-6 flex flex-col h-full">
            <div class="panel-title flex justify-between items-center mb-6">
                <div>
                    <i class="fa-solid fa-layer-group text-purple-400 mr-2"></i>
                    <span>Faol Guruhlar</span>
                </div>
                <button class="btn-ios text-[10px] py-1 px-3 border border-white/10 hover:bg-white/5">ARCHIVE <i class="fa-solid fa-chevron-right text-[8px] ml-1"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto slim-scroll space-y-3 pr-2">
                @forelse($activeGroups as $group)
                <div class="p-3 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 transition-all flex items-center gap-3">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-bold text-sm text-purple-400">{{ $group->name }}</div>
                            <div class="text-[10px] font-bold opacity-60 uppercase">{{ $group->course->name ?? 'N/A' }}</div>
                        </div>
                        <div class="flex justify-between items-center text-[10px] opacity-60">
                            <span><i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $group->teacher->name ?? 'N/A' }}</span>
                            <span><i class="fa-solid fa-door-open mr-1"></i> {{ $group->room->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.academy.attendance.students', $group->id) }}" class="w-8 h-8 rounded-full bg-yellow-500/10 text-yellow-400 flex items-center justify-center hover:bg-yellow-500 hover:text-black transition-all shrink-0 shadow-[0_0_10px_rgba(234,179,8,0.2)]" title="Davomat va Baho">
                        <i class="fa-solid fa-clipboard-check text-xs"></i>
                    </a>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full opacity-30 italic">
                    <i class="fa-solid fa-ban text-4xl mb-4"></i>
                    <p>Faol guruhlar topilmadi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Attendance Table Row -->
    <div class="glass-panel p-6 mt-6 mb-6">
        <div class="panel-title flex justify-between items-center mb-6">
            <div>
                <i class="fa-solid fa-calendar-check text-emerald-400 mr-2"></i>
                <span class="uppercase tracking-widest font-bold">Davomat Jadvali</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 max-h-[500px] overflow-y-auto slim-scroll p-2">
            @forelse($attendanceStats as $stat)
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 hover:bg-white/10 transition-all duration-300 relative overflow-hidden group hover:shadow-[0_0_20px_rgba(56,189,248,0.1)] hover:-translate-y-1">
                    <!-- Glow effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative z-10 flex flex-col h-full space-y-3">
                        <!-- Header: Name and Badge -->
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-white text-sm truncate">{{ $stat['name'] }}</h3>
                                <p class="text-white/40 text-[10px] uppercase tracking-wider truncate mt-0.5"><i class="fa-solid fa-users text-white/20 mr-1"></i>{{ $stat['groups'] ?: 'Biriktirilmagan' }}</p>
                            </div>
                            <div class="flex-shrink-0 mt-0.5">
                                @if($stat['today'] === 'present')
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 border border-green-500/30 rounded text-[9px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(34,197,94,0.1)]">KELDI</span>
                                @elseif($stat['today'] === 'late')
                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 rounded text-[9px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(234,179,8,0.1)]">KECHIKDI</span>
                                @elseif($stat['today'] === 'absent')
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded text-[9px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(239,68,68,0.1)]">KELMADI</span>
                                @else
                                    <span class="px-2 py-1 bg-white/5 text-white/30 border border-white/10 rounded text-[9px] font-bold uppercase tracking-widest">-</span>
                                @endif
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-white/10 to-transparent my-1"></div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <!-- Weekly -->
                            <div class="bg-[#0f172a]/50 rounded-lg p-2.5 border border-white/5 group-hover:border-white/10 transition-colors">
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-2 flex items-center justify-between">
                                    <span>Haftalik</span>
                                    <i class="fa-solid fa-calendar-week text-white/20"></i>
                                </p>
                                <div class="flex justify-between items-end">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] text-white/30 uppercase">Davomat</span>
                                        <span class="text-cyan-400 font-mono text-sm font-bold">{{ $stat['weekly_present'] }}k</span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-[8px] text-white/30 uppercase">Baho</span>
                                        <span class="text-blue-400 font-bold font-mono text-sm">{{ $stat['weekly_grade'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Monthly -->
                            <div class="bg-[#0f172a]/50 rounded-lg p-2.5 border border-white/5 group-hover:border-white/10 transition-colors">
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-2 flex items-center justify-between">
                                    <span>Oylik</span>
                                    <i class="fa-solid fa-calendar-days text-white/20"></i>
                                </p>
                                <div class="flex justify-between items-end">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] text-white/30 uppercase">Keldi/Yo'q</span>
                                        <span class="font-mono text-xs font-bold mt-0.5">
                                            <span class="text-green-400">{{ $stat['monthly_present'] }}</span><span class="text-white/20">/</span><span class="text-red-400">{{ $stat['monthly_absent'] }}</span>
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-[8px] text-white/30 uppercase">Baho</span>
                                        <span class="text-blue-400 font-bold font-mono text-sm">{{ $stat['monthly_grade'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Daily Grade (Bottom row) -->
                        <div class="flex justify-between items-center bg-[#0f172a]/50 rounded-lg px-3 py-2 border border-white/5 mt-auto group-hover:border-white/10 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-star text-yellow-500/50 text-[10px]"></i>
                                <span class="text-[9px] text-white/50 uppercase tracking-wider font-bold">Bugungi Baho</span>
                            </div>
                            <span class="text-blue-400 font-bold font-mono text-sm drop-shadow-[0_0_5px_rgba(96,165,250,0.3)]">{{ $stat['daily_grade'] }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-white/20">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                    <p class="text-xs uppercase tracking-widest font-bold">Ma'lumotlar yo'q</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Add Teacher Modal -->
    <div x-show="showAddTeacherModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div @click.away="showAddTeacherModal = false" class="bg-[#111] w-full max-w-lg border border-white/10 rounded-lg shadow-2xl overflow-hidden">
            <div class="p-4 border-b border-white/5 bg-white/5 flex justify-between items-center">
                <h3 class="text-xs font-bold uppercase tracking-widest text-emerald-400"><i class="fa-solid fa-chalkboard-user"></i> Yangi O'qituvchi Qo'shish</h3>
                <button @click="showAddTeacherModal = false" class="text-white/40 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.academy.teachers.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto slim-scroll">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-white/40 mb-1">F.I.O.</label>
                        <input type="text" name="name" required placeholder="To'liq ism-sharifi" class="w-full bg-black border border-white/10 rounded p-2 text-xs text-white focus:border-emerald-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-white/40 mb-1">Telefon Raqami</label>
                        <input type="text" name="phone" placeholder="+998901234567" class="w-full bg-black border border-white/10 rounded p-2 text-xs text-white focus:border-emerald-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-white/40 mb-1">Pasport Seriyasi</label>
                        <input type="text" name="passport_serial" placeholder="AA1234567" class="w-full bg-black border border-white/10 rounded p-2 text-xs text-white focus:border-emerald-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-white/40 mb-1">Email / Login</label>
                        <input type="email" name="email" required placeholder="teacher@itcloud.uz" class="w-full bg-black border border-white/10 rounded p-2 text-xs text-white focus:border-emerald-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-white/40 mb-1">Parol</label>
                        <input type="password" name="password" required placeholder="********" class="w-full bg-black border border-white/10 rounded p-2 text-xs text-white focus:border-emerald-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-white/40 mb-1">Rasm (Face ID uchun)</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full bg-black border border-white/10 rounded p-1.5 text-[10px] text-white focus:border-emerald-400 outline-none transition-all">
                    </div>
                </div>
                <button type="submit" class="w-full mt-4 py-4 bg-emerald-500/20 text-emerald-400 border border-emerald-500 font-bold text-[10px] uppercase tracking-[0.3em] hover:bg-emerald-500 hover:text-black transition-all">SAQLASH VA TIZIMGA QO'SHISH</button>
            </form>
        </div>
    </div>

    <!-- Select Group for Attendance Modal -->
    <div x-show="showAttendanceModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div @click.outside="showAttendanceModal = false" class="glass-panel w-full max-w-md p-6 relative border border-yellow-500/30 shadow-[0_0_50px_rgba(234,179,8,0.1)]">
            <button @click="showAttendanceModal = false" class="absolute top-4 right-4 text-white/50 hover:text-white"><i class="fa-solid fa-times"></i></button>
            <h2 class="text-xl font-bold text-yellow-400 mb-6 uppercase tracking-widest"><i class="fa-solid fa-clipboard-check mr-2"></i> Davomat va Baho</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] uppercase text-white/50 tracking-widest mb-1 block">Guruhni Tanlang</label>
                    <select x-model="selectedGroupId" class="w-full bg-black border border-white/10 rounded p-2 text-xs text-white focus:border-yellow-400 outline-none transition-all">
                        <option value="">-- Guruh --</option>
                        @foreach($activeGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->teacher->name ?? 'O\'qituvchisiz' }})</option>
                        @endforeach
                    </select>
                </div>
                <button @click="if(selectedGroupId) window.location.href='/academy/attendance/'+selectedGroupId" class="w-full py-3 mt-4 bg-yellow-500/20 text-yellow-400 border border-yellow-500 hover:bg-yellow-500 hover:text-black font-bold uppercase tracking-widest transition-all rounded text-xs shadow-[0_0_15px_rgba(234,179,8,0.3)]">DAVOM ETISH</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('academyHub', () => ({
            showAddTeacherModal: false,
            showAttendanceModal: false,
            selectedGroupId: '',
            init() {
                console.log('Academy Module initialized');
            }
        }));
    });
</script>
@endsection
