@extends('layouts.app')

@section('content')
<div class="space-y-4">
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
        from { width: 0 }
        to { width: 100% }
    }
    
    .typewriter p {
        overflow: hidden;
        white-space: nowrap;
        animation: typing 2s steps(40, end);
    }
    
    .typewriter p:nth-child(2) { animation-delay: 2s; }
    .typewriter p:nth-child(3) { animation-delay: 4s; }
    .typewriter p:nth-child(4) { animation-delay: 6s; }
    .typewriter p:nth-child(5) { animation-delay: 8s; }
    .typewriter p:nth-child(6) { animation-delay: 10s; }
</style>
@endsection
