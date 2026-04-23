<?php

use Livewire\Volt\Component;
use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\User;

?>

<div class="flex h-full w-full bg-[#18181b] overflow-hidden antialiased text-white" x-data="{
    activeTab: 'chats',
    showSettings: false,
    showAddFriend: false,
    addFriendTab: 'id',
    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', this.theme);
    }
}"
    x-on:friend-request-sent.window="showAddFriend = false">

    <!-- NAVIGATION RAIL -->
    <div class="w-[68px] flex-shrink-0 flex flex-col items-center py-6 bg-[#1e1e21] border-r border-[#2a2a2d] z-30 flex">
        <div class="space-y-6 flex-1 flex flex-col items-center">
            <div class="p-3 text-pink-500 mb-4">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
                </svg>
            </div>

            <button @click="activeTab = 'chats'; showSettings = false"
                :class="activeTab === 'chats' ? 'text-white' : 'text-[#71717a]'"
                class="p-3 rounded-xl transition relative group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
                <span
                    class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Chats</span>
            </button>
        </div>

        <div class="space-y-4 flex flex-col items-center">
            <button @click="toggleTheme()" class="p-3 text-[#71717a] transition group relative">
                <svg x-show="theme === 'dark'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z">
                    </path>
                </svg>
                <svg x-show="theme === 'light'" class="w-6 h-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                    </path>
                </svg>
                <span
                    class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Theme</span>
            </button>

            <button @click="showSettings = true; activeTab = 'settings'"
                class="p-3 text-[#71717a] transition group relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                </svg>
                <span
                    class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Settings</span>
            </button>
        </div>
    </div>


    <!-- CONTACT SIDEBAR -->
    <div
        class="w-[320px] md:w-[380px] lg:w-[420px] flex-shrink-0 flex flex-col border-r border-[#2a2a2d] bg-[#18181b] z-20">

        <!-- Sidebar Header -->
        <div class="flex items-center justify-between px-6 py-5 bg-[#1e1e21]">
            <h1 class="text-xl font-bold text-white">Messages</h1>
            <div class="flex items-center gap-2">
                <button class="group p-2 rounded-full transition hover:bg-gray-100 hover:scale-110">
                    <span class="block w-6 h-6 bg-gray-600 transition group-hover:bg-blue-500"
                        style="-webkit-mask-image: url('<?php echo e(asset('images/messenger/group.svg')); ?>'); mask-image: url('<?php echo e(asset('images/messenger/group.svg')); ?>'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; mask-position: center;"></span>
                </button>

                <button @click="showAddFriend = true"
                    class="group p-2 rounded-full transition hover:bg-gray-100 hover:scale-110">
                    <span class="block w-6 h-6 bg-gray-600 transition group-hover:bg-blue-500"
                        style="-webkit-mask-image: url('<?php echo e(asset('images/messenger/person_add.svg')); ?>'); mask-image: url('<?php echo e(asset('images/messenger/person_add.svg')); ?>'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; mask-position: center;"></span>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="px-6 py-4 border-b border-[#2a2a2d]">
            <div
                class="relative flex items-center w-full h-11 rounded-xl bg-[#202024] px-4 overflow-hidden focus-within:ring-1 focus-within:ring-pink-500/50 transition-all">
                <svg class="w-5 h-5 text-[#71717a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search chats..."
                    class="w-full bg-transparent border-none focus:ring-0 text-sm text-white placeholder-[#71717a] ml-3 outline-none h-full">
            </div>
        </div>

        <!-- Chat List — implement your own conversation list here -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            
        </div>
    </div>


    <!-- MAIN CHAT CANVAS -->
    <div class="flex-1 flex flex-col relative bg-[#09090b] z-10 w-full">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selected = $this->selectedConversation()): ?>
            <?php
                $selInfo = $selected->getDisplayInfo();
            ?>

            <!-- Chat Header -->
            <div
                class="h-16 flex items-center justify-between px-6 py-4 bg-[#1e1e21]/80 backdrop-blur-md border-b border-[#2a2a2d] z-10 sticky top-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 shadow-md">
                        <img src="<?php echo e($selInfo['avatar']); ?>" alt="<?php echo e($selInfo['name']); ?>"
                            class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-white text-[15px] font-bold"><?php echo e($selInfo['name']); ?></h2>
                        <p class="text-emerald-500 text-[11px] font-medium flex items-center gap-1.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($selInfo['status'] ?? '') === 'online'): ?>
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Online
                            <?php else: ?>
                                <span class="w-1.5 h-1.5 bg-[#71717a] rounded-full"></span> Offline
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-5 text-[#a1a1aa]">
                    <button class="transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <button class="transition hidden md:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages area — implement your own message list here -->
            <div class="flex-1 overflow-y-auto px-6 py-8 custom-scrollbar bg-transparent">
                
            </div>

            <!-- Chat Input — implement your own send message here -->
            <div class="px-6 py-5 bg-[#1e1e21]/95 backdrop-blur-md border-t border-[#2a2a2d]">
                
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center space-y-4">
                    <div class="p-6 bg-[#1e1e21] rounded-3xl inline-block border border-white/5 shadow-2xl">
                        <svg class="w-12 h-12 text-pink-500/50 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Your Chat Canvas</h2>
                        <p class="text-[#71717a] text-sm">Select a conversation from the left to start messaging.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>


    <!-- ADD FRIEND MODAL -->
    <div x-show="showAddFriend" class="fixed inset-0 z-[110] flex items-center justify-center p-4 backdrop-blur-sm"
        x-transition:enter="transition opacity duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition opacity duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;">

        <div class="absolute inset-0 bg-black/60" @click="showAddFriend = false"></div>

        <div class="relative w-full max-w-md bg-[#1e1e21] rounded-3xl overflow-hidden shadow-2xl border border-white/5 p-6 md:p-8"
            x-data="{
                tag: '<?php echo e(auth()->user()->user_tag ?? 'Not Set'); ?>',
                link: 'https://telefon.app/j/<?php echo e(auth()->user()->user_tag ?? 'default'); ?>',
                copied: false,
                copy(text) {
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                }
            }">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Add Contacts</h3>
                <button @click="showAddFriend = false" class="text-[#71717a] hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- MODAL NAV BAR -->
            <div class="relative flex bg-[#18181b] p-1 rounded-2xl mb-8 overflow-hidden">
                <div class="absolute top-1 bottom-1 left-1 transition-all duration-300 ease-out bg-[#202024] rounded-xl shadow-sm z-0"
                    :style="addFriendTab === 'id' ? 'width: 32%; left: 4px' : (addFriendTab === 'search' ?
                        'width: 32%; left: 34%' : 'width: 32%; left: 66%')">
                </div>

                <button @click="addFriendTab = 'id'"
                    :class="addFriendTab === 'id' ? 'text-pink-500' : 'text-[#71717a] hover:text-white'"
                    class="relative flex-1 py-2.5 text-xs font-bold rounded-xl transition duration-200 z-10">
                    BY ID
                </button>
                <button @click="addFriendTab = 'search'"
                    :class="addFriendTab === 'search' ? 'text-pink-500' : 'text-[#71717a] hover:text-white'"
                    class="relative flex-1 py-2.5 text-xs font-bold rounded-xl transition duration-200 z-10">
                    SEARCH
                </button>
                <button @click="addFriendTab = 'link'"
                    :class="addFriendTab === 'link' ? 'text-pink-500' : 'text-[#71717a] hover:text-white'"
                    class="relative flex-1 py-2.5 text-xs font-bold rounded-xl transition duration-200 z-10">
                    INVITE
                </button>
            </div>

            <!-- SLIDING TAB CONTENT -->
            <div class="relative overflow-hidden w-full">
                <div class="flex transition-transform duration-500 ease-in-out w-[300%]"
                    :style="addFriendTab === 'id' ? 'transform: translateX(0%)' : (addFriendTab === 'search' ?
                        'transform: translateX(-33.333%)' : 'transform: translateX(-66.666%)')">

                    <!-- TAB: BY ID -->
                    <div class="w-1/3 flex-shrink-0 px-1">
                        <form wire:submit.prevent="addFriend" class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-[#71717a] uppercase tracking-wider ml-1">User
                                    Tag ID</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-4 text-pink-500 font-bold">@</span>
                                    <input type="text" wire:model="searchUserTag" placeholder="SanCo_usertag"
                                        class="w-full bg-[#18181b] border border-[#2a2a2d] rounded-xl pl-10 pr-12 py-3 text-sm text-white placeholder-[#52525b] focus:ring-1 focus:ring-pink-500/50 outline-none transition-all">
                                    <button type="button" wire:click="searchContact"
                                        class="absolute right-2 p-2 text-[#71717a] hover:text-pink-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['searchUserTag'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                                        x-transition:leave="transition ease-in duration-500"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                        <span class="text-red-500 text-[15px] mt-1"><?php echo e($message); ?></span>
                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($searchResult): ?>
                                <div
                                    class="p-5 bg-[#202024] border border-white/5 rounded-2xl flex flex-col items-center text-center animate-in fade-in zoom-in-95 duration-200">
                                    <div class="relative mb-3">
                                        <img src="<?php echo e($searchResult->avatar ?? 'https://ui-avatars.com/api/?size=100&background=ec4899&color=fff&name=' . urlencode($searchResult->name)); ?>"
                                            referrerpolicy="no-referrer"
                                            class="w-16 h-16 rounded-2xl border border-white/10 object-cover shadow-md">
                                        <div
                                            class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-emerald-500 rounded-full border-2 border-[#202024]">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h4 class="text-lg font-bold text-white tracking-tight">
                                            <?php echo e($searchResult->name); ?></h4>
                                        <p
                                            class="text-[15px] text-pink-500 font-mono tracking-wider uppercase opacity-80">
                                            <?php echo e($searchResult->user_tag); ?>

                                        </p>
                                    </div>
                                    <button type="submit"
                                        class="w-full py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl transition-all active:scale-[0.97]">
                                        ADD CONTACT
                                    </button>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </form>
                    </div>

                    <!-- TAB: SEARCH -->
                    <div class="w-1/3 flex-shrink-0 px-1">
                        <div class="space-y-6">
                            <div class="relative">
                                <input type="text" placeholder="Search by user tag"
                                    class="w-full bg-[#18181b] border border-[#2a2a2d] rounded-2xl px-5 py-3.5 text-white placeholder-[#71717a] focus:ring-1 focus:ring-pink-500/50 outline-none transition-all">
                                <svg class="absolute right-5 top-1/2 -translate-y-1/2 w-5 h-5 text-[#71717a]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center py-8 text-[#71717a] opacity-50 text-center">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <p class="text-sm">Enter a search term to find people</p>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: INVITE LINK -->
                    <div class="w-1/3 flex-shrink-0 px-1">
                        <div class="space-y-6">
                            <div class="bg-pink-500/5 border border-pink-500/10 rounded-2xl p-5">
                                <p class="text-sm text-[#a1a1aa] mb-4">Share this link with your friends to instantly
                                    connect on Telefon.</p>
                                <div class="flex items-center gap-2">
                                    <input type="text" readonly :value="link"
                                        class="flex-1 bg-[#18181b] border border-[#2a2a2d] rounded-xl px-4 py-3 text-xs text-[#71717a] outline-none">
                                    <button @click="copy(link)"
                                        class="p-3 bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition shadow-lg shadow-pink-500/10 active:scale-95">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FOOTER: OWN TAG -->
            <div class="mt-10 pt-6 border-t border-white/5">
                <div class="flex items-center justify-between bg-[#18181b] p-4 rounded-2xl border border-white/5">
                    <div>
                        <p class="text-[10px] font-bold text-[#71717a] uppercase tracking-tighter mb-0.5">Your User ID
                        </p>
                        <p class="text-white font-mono text-sm" x-text="tag"></p>
                    </div>
                    <button @click="copy(tag)" :class="copied ? 'bg-emerald-500' : 'bg-white/5 hover:bg-white/10'"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl transition duration-300">
                        <span class="text-xs font-bold" :class="copied ? 'text-white' : 'text-[#71717a]'"
                            x-text="copied ? 'COPIED!' : 'COPY'"></span>
                        <svg x-show="!copied" class="w-4 h-4 text-[#71717a]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                        <svg x-show="copied" class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('livewire.messenger.settings-overlay', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 4px;
        }
    </style>
</div><?php /**PATH C:\Users\johan\Desktop\Laravel\Telefon-MultiPlatform\resources\views\livewire/messenger.blade.php ENDPATH**/ ?>