@extends('layouts.app')

@section('content')
<div class="space-y-4" x-data="{ 
    activeTab: 'photos',
    activeIndex: 0,
    photos: ['photo1.jpg', 'photo2.jpg', 'photo3.jpg', 'photo4.jpg'],
    videos: ['video1.mp4', 'video2.mp4'],
    songs: ['song1.mp3', 'song2.mp3'],
    showContent: true,
    started: false,
    startGallery() {
        this.started = true;
        this.showContent = true;
    }
}">
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
        <button @click="activeTab = 'photos'" :class="{ 'text-[#0f380f]': activeTab === 'photos', 'opacity-50': activeTab !== 'photos' }">PHOTOS</button>
        <button @click="activeTab = 'videos'" :class="{ 'text-[#0f380f]': activeTab === 'videos', 'opacity-50': activeTab !== 'videos' }">VIDEOS</button>
        <button @click="activeTab = 'songs'" :class="{ 'text-[#0f380f]': activeTab === 'songs', 'opacity-50': activeTab !== 'songs' }">SONGS</button>
    </div>

    <!-- Content Area -->
    <div class="border-4 border-[#0f380f] p-2 mt-4 overflow-y-auto max-h-[240px] gameboy-scrollbar">
        <!-- Photos Tab -->
        <div x-show="activeTab === 'photos'" class="space-y-4">
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
        <div x-show="activeTab === 'videos'" class="space-y-4" x-data="{
            currentVideo: null,
            isPlaying: false,
            currentTime: 0,
            duration: 0,
            progress: 0,
            volume: 0.5,
            initVideo(el) {
                el.volume = this.volume;
                el.addEventListener('timeupdate', () => {
                    this.currentTime = Math.floor(el.currentTime);
                    this.duration = Math.floor(el.duration);
                    this.progress = (el.currentTime / el.duration) * 100;
                });
                el.addEventListener('ended', () => {
                    this.isPlaying = false;
                });
            },
            formatTime(seconds) {
                return new Date(seconds * 1000).toISOString().substr(14, 5);
            },
            toggleVideo(video, videoEl) {
                if (this.currentVideo !== video) {
                    if (this.currentVideo) {
                        const oldVideo = document.querySelector(`video[data-video="${this.currentVideo}"]`);
                        oldVideo.pause();
                        oldVideo.currentTime = 0;
                    }
                    this.currentVideo = video;
                    this.isPlaying = true;
                    videoEl.play();
                } else {
                    this.isPlaying = !this.isPlaying;
                    this.isPlaying ? videoEl.play() : videoEl.pause();
                }
            }
        }">
            <template x-for="(video, index) in videos" :key="index">
                <div class="border-2 border-[#0f380f] p-2 transition-opacity duration-500 bg-[#9bbc0f]" 
                     :class="{ 'opacity-100': showContent, 'opacity-0': !showContent }">
                    <div class="text-[10px] mb-2 font-bold" x-text="video"></div>
                    
                    <div class="relative aspect-video bg-[#0f380f] overflow-hidden mb-2">
                        <video class="w-full h-full" 
                               :src="'/videos/' + video" 
                               :data-video="video"
                               @loadedmetadata="initVideo($el)" 
                               preload="metadata">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Custom Video Controls -->
                    <div class="space-y-2">
                        <!-- Progress Bar -->
                        <div class="h-2 bg-[#0f380f] relative cursor-pointer" 
                             @click="$event.target.previousElementSibling.querySelector('video').currentTime = ($event.offsetX / $event.target.offsetWidth) * duration">
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
                                    @click="toggleVideo(video, $el.closest('div').parentElement.querySelector('video'))">
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
                                       @input="$el.closest('div').parentElement.parentElement.querySelector('video').volume = volume">
                            </div>
                            
                            <button class="px-3 py-1 border-2 border-[#0f380f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[8px] focus:outline-none"
                                    @click="const video = $el.closest('div').parentElement.parentElement.querySelector('video'); video.pause(); video.currentTime = 0; isPlaying = false;">
                                STOP
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Songs Tab -->
        <div x-show="activeTab === 'songs'" class="space-y-4" x-data="{
            currentSong: null,
            isPlaying: false,
            currentTime: 0,
            duration: 0,
            progress: 0,
            volume: 0.5,
            initAudio(el) {
                el.volume = this.volume;
                el.addEventListener('timeupdate', () => {
                    this.currentTime = Math.floor(el.currentTime);
                    this.duration = Math.floor(el.duration);
                    this.progress = (el.currentTime / el.duration) * 100;
                });
                el.addEventListener('ended', () => {
                    this.isPlaying = false;
                });
            },
            formatTime(seconds) {
                return new Date(seconds * 1000).toISOString().substr(14, 5);
            },
            togglePlay(song, audioEl) {
                if (this.currentSong !== song) {
                    if (this.currentSong) {
                        const oldAudio = document.querySelector(`audio[data-song="${this.currentSong}"]`);
                        oldAudio.pause();
                        oldAudio.currentTime = 0;
                    }
                    this.currentSong = song;
                    this.isPlaying = true;
                    audioEl.play();
                } else {
                    this.isPlaying = !this.isPlaying;
                    this.isPlaying ? audioEl.play() : audioEl.pause();
                }
            }
        }">
            <template x-for="(song, index) in songs" :key="index">
                <div class="border-2 border-[#0f380f] p-2 transition-opacity duration-500 bg-[#9bbc0f]" 
                     :class="{ 'opacity-100': showContent, 'opacity-0': !showContent }">
                    <div class="text-[10px] mb-2 font-bold" x-text="song"></div>
                    
                    <!-- Hidden Audio Element -->
                    <audio :src="'/songs/' + song" :data-song="song" @loadedmetadata="initAudio($el)" preload="metadata" class="hidden"></audio>
                    
                    <!-- Start Button -->
                    <div class="flex justify-center mb-4" x-show="!isPlaying || currentSong !== song">
                        <button @click="togglePlay(song, $el.closest('div').parentElement.querySelector('audio'))"
                                class="relative px-8 py-4 border-2 border-[#0f380f] bg-[#9bbc0f] hover:bg-[#0f380f] hover:text-[#9bbc0f] focus:outline-none group overflow-hidden">
                            <div class="text-[12px] font-bold animate-pulse">START</div>
                            <div class="absolute inset-0 border-2 border-[#0f380f] opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300"></div>
                        </button>
                    </div>
                    
                    <!-- Custom Player Controls -->
                    <div class="space-y-2" x-show="isPlaying && currentSong === song"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        <!-- Progress Bar -->
                        <div class="h-2 bg-[#0f380f] relative cursor-pointer" 
                             @click="$event.target.previousElementSibling.previousElementSibling.currentTime = ($event.offsetX / $event.target.offsetWidth) * duration">
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
                                    @click="togglePlay(song, $el.closest('div').parentElement.parentElement.querySelector('audio'))">
                                PAUSE
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
                                       @input="$el.closest('div').parentElement.parentElement.parentElement.querySelector('audio').volume = volume">
                            </div>
                            
                            <button class="px-3 py-1 border-2 border-[#0f380f] hover:bg-[#0f380f] hover:text-[#9bbc0f] text-[8px] focus:outline-none"
                                    @click="const audio = $el.closest('div').parentElement.parentElement.parentElement.querySelector('audio'); audio.pause(); audio.currentTime = 0; isPlaying = false;">
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
