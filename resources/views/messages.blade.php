@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <a href="{{ route('home') }}" class="text-[8px] block mb-2">← BACK</a>
    <h1 class="text-xs text-center">MESSAGES</h1>
    <div class="border-4 border-[#0f380f] p-2 mt-4 min-h-[160px] relative">
        <div class="text-[10px] space-y-2 typewriter">
            <p>DEAR PRINCESS...</p>
            <p class="mt-4">HAPPY BIRTHDAY!</p>
            <p>I HOPE THIS SPECIAL</p>
            <p>GAMEBOY BRINGS YOU</p>
            <p>JOY AND HAPPINESS</p>
            <p>ON YOUR SPECIAL DAY!</p>
        </div>
        <div class="text-[8px] absolute bottom-2 right-2 animate-pulse">▼</div>
    </div>
</div>

<style>
    @keyframes typing {
        0%, 100% { width: 0 }
        10%, 90% { width: 100% }
    }

    @keyframes blink {
        50% { border-color: transparent }
    }

    @keyframes fadeInOut {
        0%, 100% { opacity: 0 }
        5%, 95% { opacity: 1 }
    }
    
    .typewriter p {
        overflow: hidden;
        white-space: nowrap;
        width: 0;
        border-right: 2px solid #0f380f;
        animation: 
            typing 16s steps(40) infinite,
            blink 0.5s step-end infinite alternate,
            fadeInOut 16s infinite;
    }
    
    .typewriter p:nth-child(1) { animation-delay: 0s; }
    .typewriter p:nth-child(2) { animation-delay: 2s; }
    .typewriter p:nth-child(3) { animation-delay: 4s; }
    .typewriter p:nth-child(4) { animation-delay: 6s; }
    .typewriter p:nth-child(5) { animation-delay: 8s; }
    .typewriter p:nth-child(6) { animation-delay: 10s; }
</style>
@endsection
