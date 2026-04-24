<?php

use Livewire\Volt\Component;
use App\Models\Friendship;

new class extends Component {
    /**
     * @var Friendship|null $incomingRequest
     * @var array[Friendship] $pendingRequests
     */

    public $incomingRequest = null;
    public $pendingRequests = [];

    public function getRequest()
    {
        
    }
    
}; ?>

<div x-show="showRequests" class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-8 backdrop-blur-sm"
    x-transition:enter="transition opacity duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition opacity duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;">

    <div class="absolute inset-0 transition-colors duration-300"
        :class="theme === 'dark' ? 'bg-black/40' : 'bg-gray-900/20'" @click="showRequests = false"></div>

    <div class="relative w-full max-w-6xl rounded-3xl overflow-hidden shadow-2xl border flex flex-col h-[85vh] max-h-[800px] transition-colors duration-300"
        :class="theme === 'dark' ? 'bg-[#1e1e21] border-white/5' : 'bg-white border-gray-200'">

        <div class="px-8 py-6 border-b sticky top-0 z-20 backdrop-blur-md transition-colors duration-300"
            :class="theme === 'dark' ? 'border-[#2a2a2d]/50 bg-[#1e1e21]/30' : 'border-gray-200 bg-white/80'">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight"
                        :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Friend Requests</h1>
                    <p class="mt-1 text-sm" :class="theme === 'dark' ? 'text-[#71717a]' : 'text-gray-500'">Manage your
                        pending invitations and expand your network.</p>
                </div>
                <button @click="showRequests = false" class="transition-colors p-2 rounded-full"
                    :class="theme === 'dark' ? 'text-[#71717a] hover:text-white hover:bg-white/5' : 'text-gray-400 hover:text-gray-900 hover:bg-gray-100'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-8 transition-colors duration-300"
            :class="theme === 'dark' ? 'bg-transparent' : 'bg-gray-50/50'">
            <div class="max-w-5xl mx-auto w-full">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-bold uppercase tracking-widest"
                        :class="theme === 'dark' ? 'text-[#71717a]' : 'text-gray-500'">
                        Pending Incoming (2)
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    <div class="border rounded-3xl p-6 flex flex-col items-center text-center transition-all duration-300 hover:shadow-xl"
                        :class="theme === 'dark' ? 'bg-[#1e1e21] border-white/5 hover:border-white/10 hover:shadow-black/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow-gray-200/50'">
                        <img src="https://ui-avatars.com/api/?size=150&background=ec4899&color=fff&name=John+Doe"
                            class="w-24 h-24 rounded-full object-cover shadow-md mb-4 border-2"
                            :class="theme === 'dark' ? 'border-[#2a2a2d]' : 'border-gray-50'">
                        <h3 class="text-xl font-bold" :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">John
                            Doe</h3>
                        <p class="text-[12px] font-mono tracking-wide mt-1.5 mb-6 px-3 py-1 rounded-md"
                            :class="theme === 'dark' ? 'text-pink-500 bg-pink-500/10' : 'text-pink-600 bg-pink-50'">
                            JDoe_123456789012
                        </p>
                        <div class="flex items-center gap-3 w-full">
                            <button class="flex-1 py-3 text-xs font-bold rounded-xl transition-colors"
                                :class="theme === 'dark' ? 'bg-[#2a2a2d] hover:bg-[#3f3f46] text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                                DECLINE
                            </button>
                            <button
                                class="flex-1 py-3 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl transition-colors shadow-lg active:scale-[0.98]"
                                :class="theme === 'dark' ? 'shadow-pink-500/20' : 'shadow-pink-500/30'">
                                ACCEPT
                            </button>
                        </div>
                    </div>

                    <div class="border rounded-3xl p-6 flex flex-col items-center text-center transition-all duration-300 hover:shadow-xl"
                        :class="theme === 'dark' ? 'bg-[#1e1e21] border-white/5 hover:border-white/10 hover:shadow-black/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow-gray-200/50'">
                        <img src="https://ui-avatars.com/api/?size=150&background=8b5cf6&color=fff&name=Jane+Smith"
                            class="w-24 h-24 rounded-full object-cover shadow-md mb-4 border-2"
                            :class="theme === 'dark' ? 'border-[#2a2a2d]' : 'border-gray-50'">
                        <h3 class="text-xl font-bold" :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Jane
                            Smith</h3>
                        <p class="text-[12px] font-mono tracking-wide mt-1.5 mb-6 px-3 py-1 rounded-md"
                            :class="theme === 'dark' ? 'text-pink-500 bg-pink-500/10' : 'text-pink-600 bg-pink-50'">
                            JSmith_9876543210
                        </p>
                        <div class="flex items-center gap-3 w-full">
                            <button class="flex-1 py-3 text-xs font-bold rounded-xl transition-colors"
                                :class="theme === 'dark' ? 'bg-[#2a2a2d] hover:bg-[#3f3f46] text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                                DECLINE
                            </button>
                            <button
                                class="flex-1 py-3 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl transition-colors shadow-lg active:scale-[0.98]"
                                :class="theme === 'dark' ? 'shadow-pink-500/20' : 'shadow-pink-500/30'">
                                ACCEPT
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>