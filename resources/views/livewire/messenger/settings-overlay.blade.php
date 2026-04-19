<!-- PROFILE/SETTINGS OVERLAY -->
<div x-show="showSettings" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-8 backdrop-blur-sm"
     x-transition:enter="transition opacity duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition opacity duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display:none;">
    
    <div class="absolute inset-0 bg-black/40" @click="showSettings = false"></div>

    <div class="relative w-full max-w-2xl lg:max-w-5xl bg-[#1e1e21] rounded-3xl overflow-hidden shadow-2xl border border-white/5 flex flex-col md:flex-row h-full lg:h-[85vh] max-h-[600px] lg:max-h-[850px]">
        <div class="w-full md:w-56 bg-[#18181b] p-6 border-r border-white/5 hidden md:block">
            <h2 class="text-xl font-bold mb-8">Settings</h2>
            <nav class="h-[calc(100%-4rem)] flex flex-col">
                <div class="space-y-4 flex-1">
                    <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'text-pink-500 bg-pink-500/10' : 'text-[#71717a]'" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl transition font-medium">Profile</button>
                    <button @click="activeTab = 'appearance'" :class="activeTab === 'appearance' ? 'text-pink-500 bg-pink-500/10' : 'text-[#71717a]'" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl transition font-medium">Appearance</button>
                </div>

                <button class="flex items-center gap-3 w-full px-4 py-3 rounded-xl transition font-medium text-red-500 hover:bg-red-500/10 mt-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </nav>
        </div>
        
        <div class="flex-1 p-6 md:p-8 overflow-y-auto">
            <div @click="showSettings = false" class="md:hidden mb-4 p-2 text-pink-500 flex items-center gap-2 font-bold cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Back
            </div>

            <div x-show="activeTab === 'profile'" class="space-y-8">
                <div class="flex flex-col items-center">
                    <img src="https://i.pravatar.cc/150?img=1" class="w-24 md:w-32 h-24 md:h-32 rounded-3xl object-cover border-4 border-pink-500/20 shadow-xl" alt="Me">
                    <p class="mt-4 text-sm text-[#71717a]">Click to change avatar</p>
                </div>
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#432818]/50 uppercase tracking-wider custom-label">Display Name</label>
                        <input type="text" value="{{ auth()->user()->name }}" class="w-full bg-[#fdfaf7] border border-[#ece0d1] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-500/50 transition-colors">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#432818]/50 uppercase tracking-wider custom-label">User Tag</label>
                        <div class="flex items-center gap-2">
                             <input type="text" readonly value="{{ auth()->user()->user_tag ?? 'Not Set' }}" class="flex-1 bg-[#fdfaf7]/50 border border-[#ece0d1] rounded-xl px-4 py-3 text-sm text-[#432818]/60 cursor-not-allowed">
                             <button class="p-3 bg-pink-500/10 text-pink-600 rounded-xl hover:bg-pink-500/20 transition-colors">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                             </button>
                        </div>
                        <p class="text-[10px] text-[#432818]/40 mt-1">Share this tag with friends so they can add you.</p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'appearance'" class="space-y-6">
                <h3 class="text-lg font-bold">Theme</h3>
                <div class="grid grid-cols-2 gap-4">
                    <button @click="theme = 'light'; localStorage.setItem('theme', 'light')" 
                            :class="theme === 'light' ? 'border-pink-500 bg-pink-500/5' : 'border-white/5'" 
                            class="p-6 border-2 rounded-2xl text-center group transition">
                        <span class="font-bold text-sm">Light Mode</span>
                    </button>
                    <button @click="theme = 'dark'; localStorage.setItem('theme', 'dark')" 
                            :class="theme === 'dark' ? 'border-pink-500 bg-pink-500/5' : 'border-white/5'" 
                            class="p-6 border-2 rounded-2xl text-center group transition">
                        <span class="font-bold text-sm">Dark Mode</span>
                    </button>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button @click="showSettings = false" class="px-6 py-2 rounded-xl text-pink-500 font-bold border border-pink-500/20">Save</button>
            </div>
        </div>
    </div>
</div>
