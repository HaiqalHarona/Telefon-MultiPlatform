<?php

use Livewire\Volt\Component;
use App\Models\Conversation;
use App\Models\Message;

new class extends Component {
    public $selectedConversationId = null;
    public $body = '';
    public $messagesLoadedCount = 20;

    public function layout()
    {
        return 'layouts.app';
    }

    public function conversations()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        // Get existing conversations
        $convos = Conversation::forUser($user->_id)
            ->with(['lastMessage'])
            ->latest('last_activity_at')
            ->get();

        // Track users who already have direct conversations
        $convoUserIds = [];
        foreach ($convos as $c) {
            if ($c->type === 'direct') {
                $otherId = collect($c->participant_ids)->reject(fn($id) => (string) $id === (string) $user->_id)->first();
                if ($otherId) {
                    $convoUserIds[] = (string) $otherId;
                } elseif (count($c->participant_ids) === 1) {
                    $convoUserIds[] = (string) $user->_id;
                } // Self convo
            }
        }

        // Get friends
        $friends = $user->friends()->with('friend')->get()->pluck('friend')->filter();

        // Merge with friends/self who DON'T have conversations yet
        $contacts = collect([$user])
            ->concat($friends)
            ->filter(fn($u) => !in_array((string) $u->_id, $convoUserIds))
            ->map(function ($u) use ($user) {
                // Return a lightweight object that mimics Conversation display needs
                return [
                    '_id' => null,
                    'virtual_user_id' => (string) $u->_id,
                    'is_virtual' => true,
                    'name' => $u->_id === $user->_id ? 'You (Saved Messages)' : $u->name,
                    'avatar' => $u->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($u->name),
                    'status' => $u->status ?? 'offline',
                    'last_activity_at' => null,
                    'last_message' => null,
                ];
            });

        // Map real conversations to the same format for consistency in the loop or handle both
        $realConvos = $convos->map(function ($c) {
            $info = $c->getDisplayInfo();
            return [
                '_id' => $c->_id,
                'virtual_user_id' => null,
                'is_virtual' => false,
                'name' => $info['name'],
                'avatar' => $info['avatar'],
                'status' => $info['status'] ?? 'offline',
                'last_activity_at' => $c->last_activity_at,
                'last_message' => $c->lastMessage?->body,
            ];
        });

        return $realConvos->concat($contacts);
    }

    public function selectConversation($id, $userId = null)
    {
        if (!$id && $userId) {
            // Use model helper to find or create
            $convo = Conversation::findOrCreateDirect(auth()->id(), $userId);
            $this->selectedConversationId = $convo->_id;
        } else {
            $this->selectedConversationId = $id;
        }

        $this->messagesLoadedCount = 20;
        $this->dispatch('scroll-bottom');
    }

    public function loadMore()
    {
        $this->messagesLoadedCount += 20;
    }

    public function selectedConversation()
    {
        if (!$this->selectedConversationId) {
            return null;
        }

        $convo = Conversation::find($this->selectedConversationId);
        if (!$convo) {
            return null;
        }

        // Load messages with count
        $messages = $convo->messages()->latest()->take($this->messagesLoadedCount)->get()->reverse();

        // This is to attach the messages to the model instance
        // so the template can still use $selected->messages
        $convo->setRelation('messages', $messages);

        return $convo;
    }

    public function sendMessage()
    {
        if (!$this->selectedConversationId || empty(trim($this->body))) {
            return;
        }

        $message = Message::create([
            'conversation_id' => $this->selectedConversationId,
            'sender_id' => auth()->id(),
            'body' => trim($this->body),
            'type' => 'text',
        ]);

        Conversation::find($this->selectedConversationId)->update([
            'last_message_id' => $message->_id,
            'last_activity_at' => now(),
        ]);

        $this->reset('body');

        // Refresh messages
        $this->messagesLoadedCount = max(20, $this->messagesLoadedCount);

        $this->dispatch('scroll-bottom');
    }
};

?>

<div class="flex h-full w-full bg-[#18181b] overflow-hidden antialiased text-white" x-data="{
    activeTab: 'chats',
    showSettings: false,
    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', this.theme);
    }
}">

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
                    class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Chats</span>
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
                    class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Theme</span>
            </button>

            <button @click="showSettings = true; activeTab = 'settings'"
                class="p-3 text-[#71717a] transition group relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                </svg>
                <span
                    class="absolute left-full ml-3 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 dark:bg-black dark:text-white light:bg-white light:text-gray-900 light:shadow-md light:border light:border-gray-200">Settings</span>
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
                        style="-webkit-mask-image: url('{{ asset('images/messenger/group.svg') }}'); mask-image: url('{{ asset('images/messenger/group.svg') }}'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; mask-position: center;"></span>
                </button>

                <button class="group p-2 rounded-full transition hover:bg-gray-100 hover:scale-110">
                    <span class="block w-6 h-6 bg-gray-600 transition group-hover:bg-blue-500"
                        style="-webkit-mask-image: url('{{ asset('images/messenger/person_add.svg') }}'); mask-image: url('{{ asset('images/messenger/person_add.svg') }}'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; mask-position: center;"></span>
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

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse ($this->conversations() as $item)
                @php
                    $id = $item['_id'];
                    $vId = $item['virtual_user_id'];
                    $time = $item['last_activity_at'] ? \Carbon\Carbon::parse($item['last_activity_at']) : null;
                    $isSelected = $id && $selectedConversationId === $id;
                @endphp
                <div wire:click="selectConversation('{{ $id }}', '{{ $vId }}')"
                    class="flex items-center gap-4 p-4 hover:bg-[#202024] cursor-pointer transition relative group border-b border-white/[0.02] 
                    {{ $isSelected ? 'bg-pink-500/10 border-l-4 border-l-pink-500' : '' }}">
                    <div class="relative w-14 h-14 flex-shrink-0">
                        <img src="{{ $item['avatar'] }}" alt="Avatar"
                            class="w-full h-full rounded-2xl object-cover shadow-lg">
                        @if ($item['status'] === 'online')
                            <div
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-[#18181b]">
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 pr-1">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="text-white text-[15px] font-semibold truncate">{{ $item['name'] }}</h3>
                            <span class="text-[11px] text-[#71717a] font-medium">
                                @if ($time)
                                    {{ $time->isToday() ? $time->format('g:i A') : ($time->isYesterday() ? 'Yesterday' : $time->format('M d')) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[#a1a1aa] text-[13px] truncate pr-2">
                                {{ $item['last_message'] ?? 'No messages yet' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full p-8 text-center opacity-40">
                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <p class="text-sm">No conversations found.<br>Search for friends to start chatting!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MAIN CHAT CANVAS -->
    <div class="flex-1 flex flex-col relative bg-[#09090b] z-10 w-full">

        @if ($selected = $this->selectedConversation())
            @php
                $selInfo = $selected->getDisplayInfo();
            @endphp
            <!-- Chat Header -->
            <div
                class="h-16 flex items-center justify-between px-6 py-4 bg-[#1e1e21]/80 backdrop-blur-md border-b border-[#2a2a2d] z-10 sticky top-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 shadow-md">
                        <img src="{{ $selInfo['avatar'] }}" alt="{{ $selInfo['name'] }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-white text-[15px] font-bold">{{ $selInfo['name'] }}</h2>
                        <p class="text-emerald-500 text-[11px] font-medium flex items-center gap-1.5">
                            @if (($selInfo['status'] ?? '') === 'online')
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Online
                            @else
                                <span class="w-1.5 h-1.5 bg-[#71717a] rounded-full"></span> Offline
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-5 text-[#a1a1aa]">
                    <button class="transition"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg></button>
                    <button class="transition hidden md:block"><svg class="w-5 h-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg></button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto px-6 py-8 space-y-4 custom-scrollbar bg-transparent flex flex-col"
                x-data="{
                    scrollToBottom() { $el.scrollTop = $el.scrollHeight },
                        scrollAnchor: 0,
                        isLoading: false,
                        init() {
                            this.observer = new IntersectionObserver((entries) => {
                                if (entries[0].isIntersecting && !this.isLoading) {
                                    this.isLoading = true;
                                    this.scrollAnchor = $el.scrollHeight - $el.scrollTop;
                                    $wire.loadMore().then(() => {
                                        this.isLoading = false;
                                        this.$nextTick(() => {
                                            $el.scrollTop = $el.scrollHeight - this.scrollAnchor;
                                        });
                                    });
                                }
                            }, { threshold: 0.1 });
                            this.observer.observe(this.$refs.loadMoreTrigger);
                        }
                }" x-init="scrollToBottom()" x-on:scroll-bottom.window="scrollToBottom()"
                wire:key="convo-{{ $selected->_id }}">

                <div x-ref="loadMoreTrigger" class="h-8 w-full flex items-center justify-center">
                    <div wire:loading wire:target="loadMore"
                        class="flex items-center gap-2 text-pink-500/50 text-xs font-medium">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Loading history...
                    </div>
                </div>

                @forelse ($selected->messages as $message)
                    <div
                        class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} group">
                        <div
                            class="flex flex-col max-w-[70%] {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }} gap-1">
                            <div
                                class="px-4 py-2.5 rounded-2xl shadow-sm relative {{ $message->sender_id === auth()->id() ? 'bg-pink-500 text-white rounded-tr-none' : 'bg-[#202024] text-white rounded-tl-none border border-white/5' }}">
                                <p class="text-[14.5px] leading-relaxed">{{ $message->body }}</p>
                            </div>
                            <span
                                class="text-[10px] text-[#71717a] font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ $message->created_at->format('g:i A') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex items-center justify-center text-[#71717a] text-sm italic">
                        No messages here yet. Break the ice!
                    </div>
                @endforelse
            </div>
        @else
            <!-- Placeholder Empty State -->
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
        @endif

        <!-- Chat Input Footer (STRETCHED) -->
        <div class="px-6 py-5 bg-[#1e1e21]/95 backdrop-blur-md border-t border-[#2a2a2d]">
            <div class="flex items-end gap-4 w-full">
                <button
                    class="p-3 text-[#a1a1aa] hover:text-white transition rounded-xl hover:bg-white/5 flex-shrink-0"><svg
                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                        </path>
                    </svg></button>

                <div
                    class="flex-1 bg-[#18181b] border border-[#2a2a2d] rounded-2xl shadow-inner relative focus-within:ring-1 focus-within:ring-pink-500/50 transition-all flex items-center min-h-[52px]">
                    <button class="p-3 text-[#71717a] hover:text-pink-400 transition absolute left-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </button>
                    <textarea wire:model="body" wire:keydown.enter.prevent="sendMessage" rows="1" placeholder="Write something..."
                        class="w-full bg-transparent border-none text-white text-[15px] placeholder-[#71717a] pl-12 pr-4 py-3.5 resize-none focus:ring-0 outline-none h-full"></textarea>
                </div>

                <button wire:click="sendMessage"
                    class="p-4 bg-pink-500 hover:bg-pink-600 text-white rounded-2xl transition shadow-xl shadow-pink-500/20 active:scale-95 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @include('livewire.messenger.settings-overlay')

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 4px;
        }

        /* Tooltip style overrides for light mode */
        .light .group-hover\:opacity-100 {
            background-color: #ffffff !important;
            color: #18181b !important;
            border: 1px solid #e4e4e7 !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
        }
    </style>
</div>
