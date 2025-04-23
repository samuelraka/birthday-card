@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <h1 class="text-xs text-center">GAME SELECT</h1>
    <div class="mt-4 space-y-2">
        <div class="border-2 border-[#0f380f] p-2 cursor-pointer hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors">
            <div class="text-[10px]">MEMORY MATCH</div>
            <div class="text-[8px] mt-1">HIGH SCORE: 000</div>
        </div>
        <div class="border-2 border-[#0f380f] p-2 cursor-pointer hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors">
            <div class="text-[10px]">SNAKE GAME</div>
            <div class="text-[8px] mt-1">HIGH SCORE: 000</div>
        </div>
        <div class="border-2 border-[#0f380f] p-2 cursor-pointer hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors">
            <div class="text-[10px]">PUZZLE GAME</div>
            <div class="text-[8px] mt-1">HIGH SCORE: 000</div>
        </div>
    </div>
    <div class="text-[8px] text-center mt-4 animate-pulse">SELECT GAME WITH A</div>
</div>
@endsection
