<x-guest-layout>
    <div class="glass-card max-w-lg w-full p-10 sm:p-12 relative overflow-hidden">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">NETWORK JOIN</h1>
            <p class="text-[10px] font-black text-purple-400 tracking-[0.5em] uppercase opacity-70">Initialize Admin Profile</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-4">Company / Organization Name</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" required 
                    class="w-full input-ios" placeholder="Cyberdyne Systems">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-4">Full Legal Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    class="w-full input-ios" placeholder="John Connor">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-4">Uplink Address (Email)</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                    class="w-full input-ios" placeholder="admin@nexus.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-500 text-[10px] font-bold px-4 uppercase" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-4">Access Key</label>
                    <input type="password" name="password" required 
                        class="w-full input-ios" placeholder="••••••••">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-4">Confirm Key</label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full input-ios" placeholder="••••••••">
                </div>
                <x-input-error :messages="$errors->get('password')" class="col-span-2 mt-2 text-pink-500 text-[10px] font-bold px-4 uppercase" />
            </div>

            <div class="pt-4 border-t border-white/5 space-y-4">
                <button type="submit" class="w-full btn-neon py-5 text-[12px] tracking-[0.3em] uppercase flex justify-center items-center gap-3">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                    <span>REQUEST ACCESS</span>
                </button>
                
                <p class="text-center">
                    <a class="text-[10px] font-black text-white/30 hover:text-cyan-400 uppercase tracking-widest transition-all" href="{{ route('login') }}">
                        Already joined the mainframe?
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
