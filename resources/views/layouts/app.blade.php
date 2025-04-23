<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Gameboy</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    <script src="/js/memory-game.js"></script>
    <script src="/js/snake-game.js"></script>
    <script src="/js/puzzle-game.js"></script>
</head>
<body class="bg-gray-800 font-['Press_Start_2P'] text-[#0f380f]">
    <div class="max-w-md mx-auto min-h-screen p-8" x-data="{ 
        dispatchArrowKey(direction) {
            const event = new KeyboardEvent('keydown', {
                key: direction,
                bubbles: true
            });
            document.dispatchEvent(event);
        }
    }">
    <div x-data @keydown.window.a="$dispatch('next-tab')" @keydown.window.b="$dispatch('prev-tab')">
        <!-- Gameboy Device Frame -->
        <div class="bg-[#c0c0c0] rounded-[20px] p-6 shadow-xl relative">
            <!-- Power LED -->
            <div class="absolute top-4 left-8 flex items-center">
                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                <span class="text-[8px]">POWER</span>
            </div>
            
            <!-- Screen Frame -->
            <div class="bg-[#9ca3af] p-4 rounded-lg mb-6">
                <!-- Screen -->
                <div class="bg-[#9bbc0f] p-4 rounded min-h-[240px] shadow-inner">
                    @yield('content')
                </div>
            </div>
            
            <!-- D-Pad -->
            <div class="flex justify-between items-center mb-4">
                <div class="relative w-24 h-24">
                    <!-- Up button -->
                    <div class="absolute w-8 h-8 bg-[#303030] left-8 top-0 rounded-t cursor-pointer hover:bg-[#404040] active:bg-[#505050]"
                         @click="dispatchArrowKey('ArrowUp')"></div>
                    <!-- Down button -->
                    <div class="absolute w-8 h-8 bg-[#303030] left-8 bottom-0 rounded-b cursor-pointer hover:bg-[#404040] active:bg-[#505050]"
                         @click="dispatchArrowKey('ArrowDown')"></div>
                    <!-- Left button -->
                    <div class="absolute w-8 h-8 bg-[#303030] left-0 top-8 rounded-l cursor-pointer hover:bg-[#404040] active:bg-[#505050]"
                         @click="dispatchArrowKey('ArrowLeft')"></div>
                    <!-- Right button -->
                    <div class="absolute w-8 h-8 bg-[#303030] right-0 top-8 rounded-r cursor-pointer hover:bg-[#404040] active:bg-[#505050]"
                         @click="dispatchArrowKey('ArrowRight')"></div>
                    <!-- Center dot -->
                    <div class="absolute w-8 h-8 bg-[#303030] left-8 top-8 rounded-sm"></div>
                </div>
                
                <!-- A B Buttons -->
                <div class="flex justify-end space-x-4 mt-4">
                    <div class="flex items-center space-x-1">
                        <button @click="$dispatch('prev-tab')" class="w-12 h-12 bg-[#ab3c20] text-[#9bbc0f] rounded-full shadow-lg text-sm font-bold">A</button>
                        <button @click="$dispatch('next-tab')" class="w-12 h-12 bg-[#ab3c20] text-[#9bbc0f] rounded-full shadow-lg text-sm font-bold">B</button>
                    </div>
                </div>
            </div>
            
            <!-- Start Select Buttons -->
            <div class="flex justify-center gap-8">
                <button @click="dispatchArrowKey('Enter')" class="w-16 h-6 bg-[#303030] rounded-pill transform -rotate-12 flex items-center justify-center text-[10px] text-white hover:bg-[#404040] active:bg-[#505050]">SELECT</button>
                <a href="{{ route('home') }}" class="w-16 h-6 bg-[#303030] rounded-pill transform -rotate-12 flex items-center justify-center text-[10px] text-white hover:bg-[#404040] active:bg-[#505050]">START</a>
            </div>
            
            <!-- Nintendo Logo -->
            <div class="text-center mt-6">
                <span class="text-[#303030] text-sm">Nintendo</span>
            </div>
        </div>
    </div>
</body>
</html>
