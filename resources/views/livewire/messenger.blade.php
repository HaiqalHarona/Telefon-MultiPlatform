<?php

use Livewire\Volt\Component;

new class extends Component {
    public function layout()
    {
        return 'layouts.app';
    }
};

?>

<div class="flex h-full w-full bg-[#18181b] overflow-hidden antialiased text-white" 
     x-data="{ 
        view: 'list', 
        activeTab: 'chats', 
        showSettings: false,
        isMobile: window.innerWidth < 768,
        openChat() { 
            if (this.isMobile) this.view = 'chat'; 
        },
        closeChat() {
            this.view = 'list';
        }
     }"
     x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })">
    
    <!-- NAVIGATION RAIL (Responsive) -->
    <!-- Desktop: Left Rail -->
    <div class="w-[68px] flex-shrink-0 flex flex-col items-center py-6 bg-[#1e1e21] border-r border-[#2a2a2d] z-30 hidden md:flex">
        <div class="space-y-6 flex-1 flex flex-col items-center">
            <div class="p-3 text-pink-500 mb-4">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>

            <button @click="activeTab = 'chats'; showSettings = false; if(isMobile) view='list'" 
                    :class="activeTab === 'chats' ? 'text-white' : 'text-[#71717a]'"
                    class="p-3 rounded-xl transition relative group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Chats</span>
            </button>

            <button @click="activeTab = 'profile'; showSettings = true" 
                    :class="activeTab === 'profile' ? 'text-white' : 'text-[#71717a]'"
                    class="p-3 rounded-xl transition relative group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Profile</span>
            </button>
        </div>

        <div class="space-y-4 flex flex-col items-center">
            <button @click="toggleTheme()" class="p-3 text-[#71717a] transition group relative">
                <svg x-show="theme === 'dark'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"></path></svg>
                <svg x-show="theme === 'light'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <span class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Theme</span>
            </button>

            <button @click="showSettings = true; activeTab = 'settings'" 
                    class="p-3 text-[#71717a] transition group relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                <span class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Settings</span>
            </button>
            <img src="https://i.pravatar.cc/150?img=1" alt="Profile" class="w-10 h-10 rounded-full border-2 border-pink-500/20 mb-4 cursor-pointer hover:scale-105 transition active:scale-95">
        </div>
    </div>

    <!-- Mobile: Bottom Bar -->
    <div class="fixed bottom-0 left-0 right-0 h-16 bg-[#1e1e21] border-t border-[#2a2a2d] z-[40] flex items-center justify-around px-4 md:hidden" x-show="view === 'list'">
        <button @click="activeTab = 'chats'; showSettings = false; view='list'" class="p-2" :class="activeTab === 'chats' ? 'text-pink-500' : 'text-[#71717a]'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </button>
        <button @click="activeTab = 'profile'; showSettings = true" class="p-2" :class="activeTab === 'profile' ? 'text-pink-500' : 'text-[#71717a]'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </button>
        <button @click="toggleTheme()" class="p-2 text-[#71717a]">
            <svg x-show="theme === 'dark'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M12 5a7 7 0 100 14 7 7 0 000-14z"></path></svg>
            <svg x-show="theme === 'light'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
        <button @click="showSettings = true; activeTab = 'settings'" class="p-2" :class="activeTab === 'settings' ? 'text-pink-500' : 'text-[#71717a]'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </button>
    </div>

    <!-- CONTACT SIDEBAR -->
    <div x-show="!isMobile || view === 'list'" 
         class="w-full md:w-[380px] lg:w-[420px] flex-shrink-0 flex flex-col border-r border-[#2a2a2d] bg-[#18181b] z-20 pb-16 md:pb-0"
         x-transition:enter="transition-transform duration-300 md:duration-0"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between px-6 py-5 bg-[#1e1e21]">
            <h1 class="text-xl font-bold text-white">Messages</h1>
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-full transition"><svg class="w-5 h-5 text-[#a1a1aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="px-6 py-4 border-b border-[#2a2a2d]">
            <div class="relative flex items-center w-full h-11 rounded-xl bg-[#202024] px-4 overflow-hidden focus-within:ring-1 focus-within:ring-pink-500/50 transition-all">
                <svg class="w-5 h-5 text-[#71717a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search chats..." class="w-full bg-transparent border-none focus:ring-0 text-sm text-white placeholder-[#71717a] ml-3 outline-none h-full">
            </div>
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <!-- Pinned Chat -->
            <div class="flex items-center gap-4 p-4 hover:bg-[#202024] cursor-pointer transition relative group bg-[#1e1e21]" @click="openChat()">
                <div class="relative w-14 h-14 flex-shrink-0">
                    <img src="https://i.pravatar.cc/150?img=33" alt="Avatar" class="w-full h-full rounded-2xl object-cover shadow-lg">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-[#18181b]"></div>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-white text-[15px] font-semibold truncate">Design Team Sync</h3>
                        <span class="text-[11px] text-pink-400 font-bold">10:42 AM</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-[#a1a1aa] text-[13px] truncate pr-2">Okay, I'll update the Figma file now. 👍</p>
                        <span class="bg-pink-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg shadow-pink-500/20">3</span>
                    </div>
                </div>
            </div>

            @for ($i = 0; $i < 3; $i++)
            <div class="flex items-center gap-4 p-4 hover:bg-[#202024] cursor-pointer transition relative group" @click="openChat()">
                <div class="relative w-14 h-14 flex-shrink-0">
                    <img src="https://i.pravatar.cc/150?img={{ 47+$i }}" alt="Avatar" class="w-full h-full rounded-2xl object-cover">
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-white text-[15px] font-medium truncate">User {{ $i + 1 }}</h3>
                        <span class="text-[11px] text-[#71717a]">Yesterday</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-[#a1a1aa] text-[13px] truncate pr-2">Actually, let's meet at 5 PM instead.</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- MAIN CHAT CANVAS -->
    <div x-show="!isMobile || view === 'chat'" 
         class="flex-1 flex flex-col relative bg-[#09090b] z-10 w-full"
         x-transition:enter="transition-transform duration-300 md:duration-0"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0">
         
        <!-- Chat Header -->
        <div class="h-16 flex items-center justify-between px-6 py-4 bg-[#1e1e21]/80 backdrop-blur-md border-b border-[#2a2a2d] z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button @click="closeChat()" class="p-2 -ml-2 text-[#a1a1aa] hover:text-white md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 shadow-md">
                    <img src="https://i.pravatar.cc/150?img=33" alt="Design Team Sync" class="w-full h-full object-cover">
                </div>
                <div>
                    <h2 class="text-white text-[15px] font-bold">Design Team Sync</h2>
                    <p class="text-emerald-500 text-[11px] font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Online
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-5 text-[#a1a1aa]">
                <button class="transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
                <button class="transition hidden md:block"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto px-6 py-8 space-y-8 custom-scrollbar bg-transparent" 
             x-data="{ scrollToBottom() { this.$refs.timeline.scrollTop = this.$refs.timeline.scrollHeight } }" 
             x-ref="timeline" 
             x-init="setTimeout(() => scrollToBottom(), 100)">
            
            <div class="flex justify-center my-6">
                <span class="bg-[#202024] text-[#71717a] text-[11px] font-bold px-4 py-1.5 rounded-full uppercase tracking-[0.1em] shadow-sm border border-white/5">Today</span>
            </div>

            <div class="flex items-end gap-3 w-full">
                <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mb-1 shadow-md">
                    <img src="https://i.pravatar.cc/150?img=47" alt="Alex" class="w-full h-full object-cover">
                </div>
                <div class="bg-[#1e1e21] border border-[#2a2a2d] text-white p-4 rounded-2xl rounded-bl-sm shadow-xl relative max-w-[85%] md:max-w-[70%] lg:max-w-[50%]">
                    <p class="text-[14px] leading-relaxed">Hey team! I've finalized the dashboard layout for the mobile screens. Let's review the spacing on the navigation rail.</p>
                </div>
            </div>

            <div class="flex items-end justify-end gap-3 w-full ml-auto">
                <div class="bg-gradient-to-tr from-pink-600 to-pink-500 text-white p-4 rounded-2xl rounded-br-sm shadow-xl shadow-pink-500/10 max-w-[85%] md:max-w-[70%] lg:max-w-[50%]">
                    <p class="text-[14px] leading-relaxed">The navigation rail looks perfect. It gives the app a very premium, native feel. Great work!</p>
                </div>
            </div>
        </div>

        <!-- Chat Input Footer (STRETCHED) -->
        <div class="px-6 py-5 bg-[#1e1e21]/95 backdrop-blur-md border-t border-[#2a2a2d]">
            <div class="flex items-end gap-4 w-full">
                <button class="p-3 text-[#a1a1aa] hover:text-white transition rounded-xl hover:bg-white/5 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg></button>
                
                <div class="flex-1 bg-[#18181b] border border-[#2a2a2d] rounded-2xl shadow-inner relative focus-within:ring-1 focus-within:ring-pink-500/50 transition-all flex items-center min-h-[52px]">
                    <button class="p-3 text-[#71717a] hover:text-pink-400 transition absolute left-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                    <textarea rows="1" placeholder="Write something..." class="w-full bg-transparent border-none text-white text-[15px] placeholder-[#71717a] pl-12 pr-4 py-3.5 resize-none focus:ring-0 outline-none h-full"></textarea>
                </div>

                <button class="p-4 bg-pink-500 hover:bg-pink-600 text-white rounded-2xl transition shadow-xl shadow-pink-500/20 active:scale-95 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                </button>
            </div>
        </div>
    </div>

    @include('livewire.messenger.settings-overlay')

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 4px; }
        /* Tooltip style overrides for light mode */
        .light .group-hover\:opacity-100 { 
            background-color: #ffffff !important; 
            color: #18181b !important; 
            border: 1px solid #e4e4e7 !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
        }
    </style>
</div>
