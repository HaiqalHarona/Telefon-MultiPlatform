<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<div x-show="showSettings" class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-8 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display:none;"
    x-data="{
        profileImagePreview: '<?php echo e(auth()->user()->avatar ?? 'https://ui-avatars.com/api/?background=ec4899&color=fff&name=' . urlencode(auth()->user()->name)); ?>',
        cropper: null,
        showCropModal: false,
        initCropper(imageElement) {
            if (this.cropper) {
                this.cropper.destroy();
            }
            this.cropper = new Cropper(imageElement, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                background: false,
            });
        },
        handleImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.showCropModal = true;
                    this.$nextTick(() => {
                        this.$refs.cropImage.src = e.target.result;
                        this.initCropper(this.$refs.cropImage);
                    });
                };
                reader.readAsDataURL(file);
                // Clear the input so the same file can be selected again
                event.target.value = '';
            }
        },
        applyCrop() {
            if (this.cropper) {
                const canvas = this.cropper.getCroppedCanvas({
                    width: 256,
                    height: 256
                });
                this.profileImagePreview = canvas.toDataURL('image/jpeg', 0.85);
                $wire.profileAvatar = this.profileImagePreview;
                this.showCropModal = false;
                this.cropper.destroy();
                this.cropper = null;
            }
        }
    }"
    x-cloak>

    <div class="absolute inset-0 bg-gray-900/40 dark:bg-black/60 transition-colors duration-300"
        @click="showSettings = false; activeTab = 'chats'"></div>

    <div
        class="relative w-full max-w-3xl lg:max-w-5xl bg-white dark:bg-[#1e1e21] rounded-3xl overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.2)] dark:shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-gray-200 dark:border-white/10 flex flex-col md:flex-row h-full lg:h-[80vh] max-h-[600px] lg:max-h-[750px] transition-colors duration-300">

        <div
            class="w-full md:w-64 bg-gray-50 dark:bg-[#141416] p-6 lg:p-8 border-r border-gray-200 dark:border-white/5 hidden md:flex flex-col relative z-10 transition-colors duration-300">
            <h2
                class="text-2xl font-bold text-gray-900 dark:text-white mb-8 tracking-tight transition-colors duration-300">
                Settings</h2>
            <nav class="flex-1 flex flex-col gap-2">
                <button @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'text-pink-500 dark:text-pink-400 bg-pink-500/10 border-pink-500/20' : 'text-gray-500 dark:text-[#a1a1aa] hover:bg-gray-200/50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white border-transparent'"
                    class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl transition-all duration-200 font-medium border text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </button>
                <button @click="activeTab = 'appearance'"
                    :class="activeTab === 'appearance' ? 'text-pink-500 dark:text-pink-400 bg-pink-500/10 border-pink-500/20' : 'text-gray-500 dark:text-[#a1a1aa] hover:bg-gray-200/50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white border-transparent'"
                    class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl transition-all duration-200 font-medium border text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                        </path>
                    </svg>
                    Appearance
                </button>
            </nav>

            <form method="POST" action="<?php echo e(route('logout')); ?>"
                class="mt-auto pt-6 border-t border-gray-200 dark:border-white/5 transition-colors duration-300">
                <?php echo csrf_field(); ?>
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl transition-all duration-200 font-medium text-red-500 dark:text-red-400 hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-300 border border-transparent hover:border-red-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <div
            class="flex-1 p-6 md:p-10 pb-28 overflow-y-auto relative bg-white dark:bg-[#1e1e21] custom-scrollbar transition-colors duration-300">
            <div @click="showSettings = false; activeTab = 'chats'"
                class="md:hidden mb-6 p-2 text-pink-500 flex items-center gap-2 font-bold cursor-pointer hover:bg-pink-500/10 rounded-xl transition w-max">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg> Back
            </div>

            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300 delay-75"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="max-w-xl mx-auto space-y-10" style="display:none;">

                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">My
                        Profile</h3>
                    <p class="text-gray-500 dark:text-[#a1a1aa] text-sm transition-colors duration-300">Manage your
                        personal information and how others see you.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                    <div class="relative group cursor-pointer flex-shrink-0">
                        <div
                            class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-gray-200 dark:border-white/10 shadow-xl dark:shadow-2xl transition-all duration-300 group-hover:scale-105 group-hover:border-pink-500/50">
                            <img :src="profileImagePreview" referrerpolicy="no-referrer"
                                class="w-full h-full object-cover" alt="Me">
                            <label for="avatarUpload"
                                class="absolute inset-0 bg-black/50 dark:bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm cursor-pointer">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="file" id="avatarUpload" class="hidden" accept="image/*" @change="handleImageSelect">
                            </label>
                        </div>
                    </div>
                    <div class="text-center sm:text-left pt-2">
                        <h4 class="text-gray-900 dark:text-white font-bold text-xl transition-colors duration-300" x-text="$wire.profileName || '<?php echo e(auth()->user()->name); ?>'">
                        </h4>
                        <p
                            class="text-pink-500 dark:text-pink-400 text-sm font-medium mt-1 transition-colors duration-300">
                            <?php echo e(auth()->user()->user_tag ?? '#NotSet'); ?>

                        </p>
                        <label for="avatarUpload"
                            class="inline-block mt-4 px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-700 dark:text-white text-xs font-semibold rounded-lg transition-colors border border-gray-200 dark:border-white/10 cursor-pointer">Change
                            Avatar</label>
                    </div>
                </div>

                <div
                    class="space-y-6 bg-gray-50 dark:bg-[#141416] p-6 rounded-3xl border border-gray-200 dark:border-white/5 transition-colors duration-300">
                    <div class="space-y-2">
                        <label
                            class="text-[12px] font-bold text-gray-500 dark:text-[#a1a1aa] uppercase tracking-wider flex items-center gap-2 transition-colors duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Display Name
                        </label>
                        <input type="text" wire:model.live="profileName"
                            class="w-full bg-white dark:bg-[#1e1e21] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-pink-500/50 focus:ring-1 focus:ring-pink-500/50 transition-all shadow-sm dark:shadow-inner">
                    </div>

                    <div class="space-y-2" x-data="{ copied: false }">
                        <label
                            class="text-[12px] font-bold text-gray-500 dark:text-[#a1a1aa] uppercase tracking-wider flex items-center gap-2 transition-colors duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            User Tag
                        </label>
                        <div class="flex items-center gap-3 relative">
                            <input x-ref="userTag" type="text" readonly
                                value="<?php echo e(auth()->user()->user_tag ?? 'Not Set'); ?>"
                                class="flex-1 bg-gray-100 dark:bg-[#1e1e21]/80 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3.5 text-sm text-gray-500 dark:text-[#71717a] cursor-not-allowed shadow-inner select-all transition-colors duration-300">

                            <button type="button"
                                @click="navigator.clipboard.writeText($refs.userTag.value); copied = true; setTimeout(() => copied = false, 2000)"
                                class="p-3.5 rounded-xl transition-all duration-200 flex-shrink-0 border"
                                :class="copied ? 'bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 border-emerald-500/20' : 'bg-pink-500/10 text-pink-500 dark:text-pink-400 border-pink-500/20 hover:bg-pink-500/20 hover:scale-105'">
                                <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <svg x-show="copied" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>

                            <div x-show="copied" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute -top-10 right-0 bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg"
                                style="display: none;">
                                Copied!
                                <div class="absolute -bottom-1 right-5 w-2.5 h-2.5 bg-emerald-500 transform rotate-45">
                                </div>
                            </div>
                        </div>
                        <p
                            class="text-[11px] text-gray-400 dark:text-[#71717a] mt-2 flex items-center gap-1.5 transition-colors duration-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Share this tag with friends so they can add you.
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'appearance'" x-transition:enter="transition ease-out duration-300 delay-75"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="max-w-xl mx-auto space-y-8" style="display:none;">

                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">
                        Appearance</h3>
                    <p class="text-gray-500 dark:text-[#a1a1aa] text-sm transition-colors duration-300">Customize how
                        the application looks to you.</p>
                </div>

                <div
                    class="space-y-4 bg-gray-50 dark:bg-[#141416] p-6 rounded-3xl border border-gray-200 dark:border-white/5 transition-colors duration-300">
                    <label
                        class="text-[12px] font-bold text-gray-500 dark:text-[#a1a1aa] uppercase tracking-wider block mb-4 transition-colors duration-300">Theme</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <button @click="$store.theme.set('light')"
                            :class="$store.theme.current === 'light' ? 'border-pink-500 bg-pink-500/10 ring-1 ring-pink-500/50' : 'border-gray-200 dark:border-white/10 bg-white dark:bg-[#1e1e21] hover:border-gray-300 dark:hover:border-white/30'"
                            class="p-6 border-2 rounded-2xl text-center group transition-all duration-200 relative overflow-hidden">
                            <div
                                class="w-12 h-12 bg-white rounded-full mx-auto mb-4 shadow-sm border border-gray-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="font-bold text-sm text-gray-900 dark:text-white transition-colors duration-300">Light
                                Mode</span>
                            <div x-show="$store.theme.current === 'light'" class="absolute top-3 right-3 text-pink-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </button>

                        <button @click="$store.theme.set('dark')"
                            :class="$store.theme.current === 'dark' ? 'border-pink-500 bg-pink-500/10 ring-1 ring-pink-500/50' : 'border-gray-200 dark:border-white/10 bg-white dark:bg-[#1e1e21] hover:border-gray-300 dark:hover:border-white/30'"
                            class="p-6 border-2 rounded-2xl text-center group transition-all duration-200 relative overflow-hidden">
                            <div
                                class="w-12 h-12 bg-gray-900 rounded-full mx-auto mb-4 shadow-inner border border-gray-700 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="font-bold text-sm text-gray-900 dark:text-white transition-colors duration-300">Dark
                                Mode</span>
                            <div x-show="$store.theme.current === 'dark'" class="absolute top-3 right-3 text-pink-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="absolute bottom-0 left-0 right-0 p-6 md:px-10 bg-gradient-to-t from-white via-white dark:from-[#1e1e21] dark:via-[#1e1e21] to-transparent flex justify-end gap-3 pointer-events-none pt-12 transition-colors duration-300">
                <div class="pointer-events-auto flex gap-3 w-full sm:w-auto">
                    <button @click="showSettings = false; activeTab = 'chats'"
                        class="flex-1 sm:flex-none px-6 py-3 rounded-xl text-gray-500 dark:text-[#a1a1aa] font-semibold hover:bg-gray-100 dark:hover:bg-white/5 transition-colors border border-transparent">Cancel</button>
                    <button @click="$wire.updateProfile().then(() => { showSettings = false; activeTab = 'chats' })"
                        class="flex-1 sm:flex-none px-8 py-3 rounded-xl bg-pink-500 hover:bg-pink-600 text-white font-bold transition-all shadow-[0_0_15px_rgba(236,72,153,0.3)] hover:shadow-[0_0_20px_rgba(236,72,153,0.5)] transform hover:-translate-y-0.5">Save
                        Changes</button>
                </div>
            </div>
        </div>
    <!-- Crop Modal -->
    <div x-show="showCropModal" class="fixed inset-0 z-[120] flex items-center justify-center p-4 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display:none;" x-cloak>
        <div class="absolute inset-0 bg-gray-900/40 dark:bg-black/80 transition-colors duration-300" @click="showCropModal = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-[#1e1e21] rounded-3xl overflow-hidden shadow-2xl border border-gray-200 dark:border-white/10 p-6 flex flex-col transition-colors duration-300">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 transition-colors duration-300">Crop Avatar</h3>
            <div class="relative w-full aspect-square bg-gray-100 dark:bg-black rounded-xl overflow-hidden mb-6 border border-gray-200 dark:border-white/10 transition-colors duration-300">
                <img x-ref="cropImage" class="block max-w-full">
            </div>
            <div class="flex justify-end gap-3 mt-auto">
                <button @click="showCropModal = false; if(cropper){cropper.destroy();cropper=null;}"
                    class="px-5 py-2.5 rounded-xl text-gray-600 dark:text-[#a1a1aa] font-semibold hover:bg-gray-100 dark:hover:bg-white/5 transition-colors border border-transparent">Cancel</button>
                <button @click="applyCrop()"
                    class="px-5 py-2.5 rounded-xl bg-pink-500 hover:bg-pink-600 text-white font-bold transition-all shadow-[0_0_15px_rgba(236,72,153,0.3)] hover:shadow-[0_0_20px_rgba(236,72,153,0.5)]">Apply</button>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\johan\Desktop\Laravel\SanCo\resources\views/livewire/messenger/settings-overlay.blade.php ENDPATH**/ ?>