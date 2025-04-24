@extends('layouts.app')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('gallery', () => ({
            init() {
                this.started = false;
                this.showContent = true;
                this.activeTab = 'photos';
                this.activeIndex = 0;
                this.photos = ['photo1.jpg', 'photo2.jpg', 'photo3.jpg', 'photo4.jpg'];
                this.videos = ['video1.mp4', 'video2.mp4'];
                this.songs = ['Taylor Swift - White Horse.mp3', 'Chase Atlantic - Swim.mp3', 'NIKI - You ll Be in My Heart.mp3'];
                this.tabs = ['photos', 'videos', 'songs'];
            },
            nextTab() {
                const currentIndex = this.tabs.indexOf(this.activeTab);
                const nextIndex = (currentIndex + 1) % this.tabs.length;
                this.activeTab = this.tabs[nextIndex];
            },
            prevTab() {
                const currentIndex = this.tabs.indexOf(this.activeTab);
                const prevIndex = (currentIndex - 1 + this.tabs.length) % this.tabs.length;
                this.activeTab = this.tabs[prevIndex];
            },
            startGallery() {
                this.started = true;
                this.showContent = true;
            }
        }));
    });
</script>

<div class="space-y-4" x-data="gallery" x-init="init()" @next-tab.window="nextTab()" @prev-tab.window="prevTab()" x-ref="gallery">
    <!-- Start Screen -->
    <div x-show="!started" class="text-center space-y-4">
        <div class="text-xs animate-pulse">PRESS START</div>
        <button @click="startGallery()" 
                class="px-4 py-2 border-2 border-[#0f380f] bg-[#9bbc0f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[10px] focus:outline-none transition-colors">
            START
        </button>
    </div>

    <!-- Main Content -->
    <div x-show="started" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="text-[8px] block mb-2">← BACK</a>

    <!-- Tab Navigation -->
    <div class="flex justify-between text-[10px] mb-4">
        <button @click="activeTab = 'photos'" :class="{ 'text-[#0f380f] font-bold': activeTab === 'photos', 'opacity-50': activeTab !== 'photos' }">PHOTOS</button>
        <button @click="activeTab = 'videos'" :class="{ 'text-[#0f380f] font-bold': activeTab === 'videos', 'opacity-50': activeTab !== 'videos' }">VIDEOS</button>
        <button @click="activeTab = 'songs'" :class="{ 'text-[#0f380f] font-bold': activeTab === 'songs', 'opacity-50': activeTab !== 'songs' }">SONGS</button>
    </div>

    <!-- Button Instructions -->
    <div class="text-[8px] text-center mb-2">
        <span class="mr-4">A: PREV TAB</span>
        <span>B: NEXT TAB</span>
    </div>

    <!-- Content Area -->
    <div class="border-4 border-[#0f380f] p-2 mt-4 overflow-y-auto max-h-[240px] gameboy-scrollbar">
        <!-- Photos Tab -->
        <div x-show="activeTab === 'photos'" class="space-y-4">
            <div class="text-center mb-4">
                <div class="text-[12px] font-bold text-[#0f380f]">HERE'S THE TOP PHOTOS OF YOU</div>
                <div class="text-[8px] text-[#306230]">Every moment with you is picture perfect ♥</div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <template x-for="(photo, index) in photos" :key="index">
                    <div class="aspect-square border-2 border-[#0f380f] p-1 cursor-pointer transition-opacity duration-500"
                         :class="{ 'opacity-100': showContent, 'opacity-0': !showContent }"
                         @click="activeIndex = index; showContent = false; setTimeout(() => showContent = true, 300)">
                        <img :src="'/images/' + photo" class="w-full h-full object-cover" alt="Memory">
                    </div>
                </template>
            </div>
        </div>

        <!-- Videos Tab -->
        <div x-show="activeTab === 'videos'" class="space-y-4">
            <div class="text-center mb-4">
                <div class="text-[12px] font-bold text-[#0f380f]">HERE'S THE TOP VIDEOS OF YOU</div>
                <div class="text-[8px] text-[#306230]">Capturing your beautiful moments in motion ♥</div>
            </div>
            <div class="space-y-4" x-data="{
            currentVideo: null,
            isPlaying: false,
            currentTime: 0,
            duration: 0,
            progress: 0,
            volume: 0.5,
            formatTime(seconds) {
                return new Date(seconds * 1000).toISOString().substr(14, 5);
            }
        }">
            <template x-for="(video, index) in videos" :key="index">
                <div class="border-2 border-[#0f380f] p-2 transition-opacity duration-500 bg-[#9bbc0f] song-item" 
                     :class="{ 'opacity-100': showContent, 'opacity-0': !showContent }">
                    <div class="text-[10px] mb-2 font-bold" x-text="video"></div>
                    
                    <div class="relative aspect-video bg-[#0f380f] overflow-hidden mb-2">
                        <video class="w-full h-full" 
                               :src="'/videos/' + video" 
                               :data-video="video"
                               @loadedmetadata="$el.volume = volume" 
                               @timeupdate="currentTime = Math.floor($el.currentTime); duration = Math.floor($el.duration); progress = ($el.currentTime / $el.duration) * 100"
                               @ended="isPlaying = false"
                               preload="metadata">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Custom Video Controls -->
                    <div class="space-y-2">
                        <!-- Progress Bar -->
                        <div class="h-2 bg-[#0f380f] relative cursor-pointer" 
                             @click="const video = $el.closest('.song-item').querySelector('video'); if(video && duration) { video.currentTime = ($event.offsetX / $event.target.offsetWidth) * duration; }">
                            <div class="h-full bg-[#306230] transition-all duration-300"
                                 :style="`width: ${progress}%`"></div>
                        </div>
                        
                        <!-- Time Display -->
                        <div class="flex justify-between text-[8px] font-mono">
                            <span x-text="formatTime(currentTime)">00:00</span>
                            <span x-text="formatTime(duration)">00:00</span>
                        </div>
                        
                        <!-- Control Buttons -->
                        <div class="flex justify-between items-center">
                            <button class="px-3 py-1 border-2 border-[#0f380f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[8px] focus:outline-none"
                                    @click="const video = $el.closest('.song-item').querySelector('video'); if(video) { 
                                        if (currentVideo !== video.dataset.video) {
                                            if (currentVideo) {
                                                const oldVideo = document.querySelector(`video[data-video='${currentVideo}']`);
                                                if (oldVideo) {
                                                    oldVideo.pause();
                                                    oldVideo.currentTime = 0;
                                                }
                                            }
                                            currentVideo = video.dataset.video;
                                            isPlaying = true;
                                            video.play();
                                        } else {
                                            isPlaying = !isPlaying;
                                            isPlaying ? video.play() : video.pause();
                                        }
                                    }">
                                <span x-text="currentVideo === video && isPlaying ? 'PAUSE' : 'PLAY'">PLAY</span>
                            </button>
                            
                            <!-- Volume Control -->
                            <div class="flex items-center space-x-1">
                                <span class="text-[8px]">VOL</span>
                                <input type="range" 
                                       class="w-16 h-1 gameboy-range" 
                                       min="0" 
                                       max="1" 
                                       step="0.1"
                                       x-model="volume"
                                       @input="const video = $el.closest('.song-item').querySelector('video'); if(video) { video.volume = volume; }">
                            </div>
                            
                            <button class="px-3 py-1 border-2 border-[#0f380f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[8px] focus:outline-none"
                                    @click="const video = $el.closest('.song-item').querySelector('video'); if(video) { video.pause(); video.currentTime = 0; isPlaying = false; currentVideo = null; }">
                                STOP
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        </div>

        <!-- Songs Tab -->
        <div x-show="activeTab === 'songs'" class="space-y-4">
            <div class="text-center mb-4">
                <div class="text-[12px] font-bold text-[#0f380f]">HERE'S THE TOP SONGS OF YOU</div>
                <div class="text-[8px] text-[#306230]">The soundtrack of our love story ♥</div>
            </div>
            <div class="space-y-4" x-data="{
            currentSong: null,
            isPlaying: false,
            currentTime: 0,
            duration: 0,
            progress: 0,
            volume: 0.5,

            formatTime(seconds) {
                return new Date(seconds * 1000).toISOString().substr(14, 5);
            },

            seekAudio(event, audioEl) {
                if (audioEl && audioEl.duration) {
                    const rect = event.target.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const width = rect.width;
                    const percentage = x / width;
                    audioEl.currentTime = percentage * audioEl.duration;
                }
            }
        }">
            <template x-for="(song, index) in songs" :key="index">
                <div class="border-2 border-[#0f380f] p-2 bg-[#9bbc0f] song-item">
                    <div class="text-[10px] mb-2 font-bold" x-text="song"></div>
                    
                    <!-- Hidden Audio Element -->
                    <audio :src="'/songs/' + song" :data-song="song" @loadedmetadata="$el.volume = volume" preload="metadata" class="hidden"
                           @timeupdate="currentTime = Math.floor($el.currentTime); duration = Math.floor($el.duration); progress = ($el.currentTime / $el.duration) * 100"
                           @ended="isPlaying = false"></audio>
                    
                    <!-- Custom Player Controls -->
                    <div class="space-y-2">
                        <!-- Progress Bar -->
                        <div class="h-2 bg-[#0f380f] relative cursor-pointer" 
                             @click="seekAudio($event, $el.closest('.song-item').querySelector('audio'))">
                            <div class="h-full bg-[#306230] transition-all duration-300 progress-bar"
                                 :style="`width: ${progress}%`"></div>
                        </div>
                        
                        <!-- Time Display -->
                        <div class="flex justify-between text-[8px] font-mono">
                            <span x-text="formatTime(currentTime)">00:00</span>
                            <span x-text="formatTime(duration)">00:00</span>
                        </div>
                        
                        <!-- Control Buttons -->
                        <div class="flex justify-between items-center">
                            <button class="px-3 py-1 border-2 border-[#0f380f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[8px] focus:outline-none"
                                    @click="const audio = $el.closest('.song-item').querySelector('audio'); if(audio) { 
                                        if (currentSong !== song) {
                                            if (currentSong) {
                                                const oldAudio = document.querySelector(`audio[data-song='${currentSong}']`);
                                                if (oldAudio) {
                                                    oldAudio.pause();
                                                    oldAudio.currentTime = 0;
                                                }
                                            }
                                            currentSong = song;
                                            isPlaying = true;
                                            audio.play();
                                        } else {
                                            isPlaying = !isPlaying;
                                            isPlaying ? audio.play() : audio.pause();
                                        }
                                    }">
                                <span x-text="currentSong === song && isPlaying ? 'PAUSE' : 'PLAY'">PLAY</span>
                            </button>
                            
                            <!-- Volume Control -->
                            <div class="flex items-center space-x-1">
                                <span class="text-[8px]">VOL</span>
                                <input type="range" 
                                       class="w-16 h-1 gameboy-range" 
                                       min="0" 
                                       max="1" 
                                       step="0.1"
                                       x-model="volume"
                                       @input="$el.closest('.song-item').querySelector('audio').volume = volume">
                            </div>
                            
                            <button class="px-3 py-1 border-2 border-[#0f380f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[8px] focus:outline-none"
                                    @click="const audio = $el.closest('.song-item').querySelector('audio'); if(audio) { audio.pause(); audio.currentTime = 0; isPlaying = false; currentSong = null; }">
                                STOP
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Navigation Instructions -->
    <div class="text-[8px] text-center mt-2">
        <div>USE ↑↓ TO SCROLL</div>
        <div>PRESS A TO VIEW</div>
    </div>
</div>

<style>
    .gameboy-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #0f380f #9bbc0f;
    }
    .gameboy-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .gameboy-scrollbar::-webkit-scrollbar-track {
        background: #9bbc0f;
    }
    .gameboy-scrollbar::-webkit-scrollbar-thumb {
        background-color: #0f380f;
        border-radius: 3px;
    }

    .transition-opacity {
        transition: opacity 0.3s ease-in-out;
    }

    /* Custom video player styles */
    video::-webkit-media-controls-panel {
        background: #9bbc0f;
    }
    video::-webkit-media-controls-play-button,
    video::-webkit-media-controls-volume-slider,
    video::-webkit-media-controls-timeline {
        filter: invert(1);
    }

    /* Custom range input for volume */
    .gameboy-range {
        -webkit-appearance: none;
        background: #0f380f;
        border-radius: 2px;
        height: 4px;
    }

    .gameboy-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 8px;
        height: 8px;
        background: #0f380f;
        border: 2px solid #9bbc0f;
        border-radius: 2px;
        cursor: pointer;
    }

    .gameboy-range::-moz-range-thumb {
        width: 8px;
        height: 8px;
        background: #0f380f;
        border: 2px solid #9bbc0f;
        border-radius: 2px;
        cursor: pointer;
    }

    /* Progress bar animation */
    @keyframes progress-pulse {
        0% { background-color: #306230; }
        50% { background-color: #0f380f; }
        100% { background-color: #306230; }
    }

    .progress-bar {
        animation: progress-pulse 2s infinite;
    }
</style>
@endsection
