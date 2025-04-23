@extends('layouts.app')

@section('content')
<div class="space-y-4" x-data="{ 
    showAlert: false,
    alertMessage: '',
    alertType: 'win',
    showGameAlert(message, type = 'win') {
        this.alertMessage = message;
        this.alertType = type;
        this.showAlert = true;
        setTimeout(() => this.showAlert = false, 3000);
    },
    activeGame: null,
    scores: {
        memory: parseInt(localStorage.getItem('memory_high_score')) || 0,
        snake: parseInt(localStorage.getItem('snake_high_score')) || 0,
        puzzle: parseInt(localStorage.getItem('puzzle_high_score')) || 0
    },
    updateHighScore(game, score) {
        if (score > this.scores[game]) {
            this.scores[game] = score;
            localStorage.setItem(`${game}_high_score`, score);
        }
    }
}" x-init="$watch('scores', value => {
    localStorage.setItem('memory_high_score', value.memory);
    localStorage.setItem('snake_high_score', value.snake);
    localStorage.setItem('puzzle_high_score', value.puzzle);
})">

    <!-- Retro Alert -->
    <div x-show="showAlert"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="bg-[#9bbc0f] border-4 border-[#0f380f] p-4 shadow-lg max-w-[200px] text-center">
            <div class="text-[10px] font-bold mb-2" x-text="alertType === 'win' ? 'CONGRATULATIONS!' : 'GAME OVER'"></div>
            <div class="text-[8px]" x-text="alertMessage"></div>
        </div>
    </div>

    <a href="{{ route('home') }}" class="text-[8px] block mb-2">← BACK</a>

    <!-- Game Select Screen -->
    <div x-show="!activeGame" class="space-y-4">
        <h1 class="text-xs text-center font-bold text-[#0f380f]">GAME SELECT</h1>
        <div class="text-[8px] text-center text-[#306230]">Choose your favorite mini-game!</div>
        
        <div class="mt-4 space-y-2">
            <button @click="activeGame = 'memory'" 
                    class="w-full border-2 border-[#0f380f] p-2 cursor-pointer hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors focus:outline-none">
                <div class="text-[10px] font-bold">MEMORY MATCH</div>
                <div class="text-[8px] mt-1">HIGH SCORE: <span x-text="scores.memory.toString().padStart(3, '0')">000</span></div>
            </button>
            
            <button @click="activeGame = 'snake'"
                    class="w-full border-2 border-[#0f380f] p-2 cursor-pointer hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors focus:outline-none">
                <div class="text-[10px] font-bold">SNAKE GAME</div>
                <div class="text-[8px] mt-1">HIGH SCORE: <span x-text="scores.snake.toString().padStart(3, '0')">000</span></div>
            </button>
            
            <button @click="activeGame = 'puzzle'"
                    class="w-full border-2 border-[#0f380f] p-2 cursor-pointer hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors focus:outline-none">
                <div class="text-[10px] font-bold">PUZZLE GAME</div>
                <div class="text-[8px] mt-1">HIGH SCORE: <span x-text="scores.puzzle.toString().padStart(3, '0')">000</span></div>
            </button>
        </div>
        <div class="text-[8px] text-center mt-4 animate-pulse">PRESS A TO SELECT GAME</div>
    </div>

    <!-- Memory Match Game -->
    <div x-show="activeGame === 'memory'" class="space-y-4">
        <div class="flex justify-between items-center">
            <button @click="activeGame = null" class="text-[8px] border-2 border-[#0f380f] px-2 py-1 hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors focus:outline-none">BACK</button>
            <div class="text-[10px] font-bold">MEMORY MATCH</div>
            <div class="text-[8px]">SCORE: <span x-text="scores.memory.toString().padStart(3, '0')">000</span></div>
        </div>
        <div x-data="createMemoryGame(
                // Win callback
                score => { 
                    $data.updateHighScore('memory', score);
                    $data.showGameAlert('You matched all pairs, just like how we are a perfect match! ♥');
                },
                // Game over callback
                score => {
                    $data.showGameAlert('Out of moves! But like my love for you, this game has no limits. Try again! ♥', 'lose');
                }
            )" class="space-y-4">
            <div class="grid grid-cols-4 gap-2">
                <template x-for="card in cards" :key="card.id">
                    <div @click="flipCard(card)" 
                         class="aspect-square border-2 border-[#0f380f] cursor-pointer transition-transform duration-300"
                         :class="{'bg-[#9bbc0f]': !card.isFlipped && !card.isMatched}">
                        <img :src="'/photos/' + card.photo" 
                             class="w-full h-full object-cover transition-opacity duration-300"
                             :class="{'opacity-100': card.isFlipped || card.isMatched, 'opacity-0': !card.isFlipped && !card.isMatched}">
                    </div>
                </template>
            </div>

            <div class="flex justify-between items-center text-[8px]">
                <div>MOVES: <span x-text="moves">0</span></div>
                <div>PAIRS: <span x-text="matchedPairs">0</span> / <span x-text="photos.length">0</span></div>
            </div>

            <template x-if="gameOver">
                <div class="text-center space-y-2">
                    <div class="text-[10px] font-bold text-[#0f380f]" x-text="matchedPairs === 4 ? 'CONGRATULATIONS!' : 'GAME OVER'"></div>
                    <div class="text-[8px]">FINAL SCORE: <span x-text="score">0</span></div>
                    <button @click="resetGame()" 
                            class="text-[8px] border-2 border-[#0f380f] px-4 py-1 hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors">
                        PLAY AGAIN
                    </button>
                </div>
            </template>

            <template x-if="!gameStarted && !gameOver">
                <div class="text-[8px] text-center animate-pulse">PRESS A TO START</div>
            </template>
        </div>
    </div>

    <!-- Snake Game -->
    <div x-show="activeGame === 'snake'" class="space-y-4">
        <div class="flex justify-between items-center">
            <button @click="activeGame = null" class="text-[8px] border-2 border-[#0f380f] px-2 py-1 hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors focus:outline-none">BACK</button>
            <div class="text-[10px] font-bold">SNAKE GAME</div>
            <div class="text-[8px]">SCORE: <span x-text="scores.snake.toString().padStart(3, '0')">000</span></div>
        </div>
        <div x-data="createSnakeGame(
                // Score update callback
                score => $data.updateHighScore('snake', score),
                // Game over callback
                score => {
                    if (score > 0) { // Only show alert if game was actually played
                        $data.showGameAlert('Game Over! Your snake got tangled, but my heart is still wrapped around you! ♥', 'lose');
                    }
                }
            )" class="space-y-4">
            <div class="aspect-square border-2 border-[#0f380f] p-1 bg-[#9bbc0f]">
                <div class="grid grid-cols-15 gap-px h-full">
                    <template x-for="(row, y) in grid" :key="y">
                        <template x-for="(cell, x) in row" :key="`${x}-${y}`">
                            <div :class="{
                                'bg-[#0f380f]': cell === 'head',
                                'bg-[#306230]': cell === 'body',
                                'bg-[#0f380f] rounded-full': cell === 'food'
                            }" class="aspect-square"></div>
                        </template>
                    </template>
                </div>
            </div>

            <div class="text-center text-[8px] space-y-2">
                <div>USE ARROW KEYS OR WASD TO MOVE</div>
                <template x-if="gameOver">
                    <div class="space-y-2">
                        <div class="text-[10px] font-bold text-[#0f380f]">GAME OVER!</div>
                        <div>FINAL SCORE: <span x-text="score">0</span></div>
                        <button @click="resetGame()" 
                                class="text-[8px] border-2 border-[#0f380f] px-4 py-1 hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors">
                            PLAY AGAIN
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Puzzle Game -->
    <div x-show="activeGame === 'puzzle'" class="space-y-4">
        <div class="flex justify-between items-center">
            <button @click="activeGame = null" class="text-[8px] border-2 border-[#0f380f] px-2 py-1 hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors focus:outline-none">BACK</button>
            <div class="text-[10px] font-bold">PUZZLE GAME</div>
            <div class="text-[8px]">SCORE: <span x-text="scores.puzzle.toString().padStart(3, '0')">000</span></div>
        </div>
        <div x-data="createPuzzleGame(score => { 
                $data.updateHighScore('puzzle', score);
                $data.showGameAlert('You solved the puzzle, just like how you solved the puzzle of my heart! ♥');
            })" class="space-y-4 relative">
            <!-- Sample Photo -->
            <div class="absolute -right-32 top-0 w-24 h-24 border-2 border-[#0f380f] bg-[#9bbc0f] p-1">
                <img src="/photos/photo1.jpg" alt="Sample" class="w-full h-full object-cover">
                <div class="text-[8px] text-center mt-1">SAMPLE</div>
            </div>
            <div class="aspect-square border-2 border-[#0f380f] p-1 bg-[#9bbc0f]">
                <div class="grid grid-cols-4 gap-px h-full">
                    <template x-for="(row, y) in grid" :key="y">
                        <div class="contents">
                            <template x-for="(cell, x) in row" :key="`${x}-${y}`">
                                <div @click="moveCell(x, y)" 
                                     class="aspect-square cursor-pointer overflow-hidden"
                                     :class="{'invisible': cell === null}">
                                    <div class="w-full h-full bg-cover bg-no-repeat" 
                                         :style="{
                                            'background-image': `url('/photos/${photo}')`,
                                            'background-size': '400%',
                                            'background-position': `${((cell-1)%4)*33.33}% ${Math.floor((cell-1)/4)*33.33}%`
                                         }"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-between items-center text-[8px]">
                <div>MOVES: <span x-text="moves">0</span></div>
                <div>SCORE: <span x-text="score">0</span></div>
            </div>

            <template x-if="gameOver">
                <div class="text-center space-y-2">
                    <div class="text-[10px] font-bold text-[#0f380f]">PUZZLE SOLVED!</div>
                    <div class="text-[8px]">FINAL SCORE: <span x-text="score">0</span></div>
                    <button @click="resetGame()" 
                            class="text-[8px] border-2 border-[#0f380f] px-4 py-1 hover:bg-[#0f380f] hover:text-[#9bbc0f] transition-colors">
                        PLAY AGAIN
                    </button>
                </div>
            </template>

            <template x-if="!gameStarted && !gameOver">
                <div class="text-[8px] text-center animate-pulse">PRESS A TO START</div>
            </template>
        </div>
    </div>
</div>
@endsection
