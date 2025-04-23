@extends('layouts.app')

@section('content')
<div class="text-center space-y-4">
    <div class="text-sm animate-blink mb-4">
        GAME BOY
    </div>
    <h1 class="text-xs mb-6">HAPPY BIRTHDAY!</h1>
    
    <!-- Menu Items -->
    <div class="space-y-2 mt-4" x-data="{ selectedIndex: 0 }" x-init="
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') {
                selectedIndex = Math.max(0, selectedIndex - 1);
            } else if (e.key === 'ArrowDown') {
                selectedIndex = Math.min(2, selectedIndex + 1);
            } else if (e.key === 'Enter') {
                const links = ['gallery', 'games', 'messages'];
                window.location.href = '/' + links[selectedIndex];
            }
        });
    ">
        <a href="{{ route('gallery') }}" 
           class="block text-[10px] p-2 transition-colors" 
           :class="{ 'bg-[#0f380f] text-[#9bbc0f]': selectedIndex === 0 }">
            ► GALLERY
        </a>
        <a href="{{ route('games') }}" 
           class="block text-[10px] p-2 transition-colors" 
           :class="{ 'bg-[#0f380f] text-[#9bbc0f]': selectedIndex === 1 }">
            ► GAMES
        </a>
        <a href="{{ route('messages') }}" 
           class="block text-[10px] p-2 transition-colors" 
           :class="{ 'bg-[#0f380f] text-[#9bbc0f]': selectedIndex === 2 }">
            ► MESSAGES
        </a>
    </div>
    
    <div class="text-[8px] mt-6">
        USE ↑↓ TO MOVE
        <br>
        PRESS SELECT TO CHOOSE
    </div>
</div>

<style>
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    .animate-blink {
        animation: blink 1s infinite;
    }
</style>
@endsection
