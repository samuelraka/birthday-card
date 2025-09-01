// Sliding Puzzle Game
function createPuzzleGame(updateHighScore) {
    const samplePhoto = 'photo1.jpg';
    return {
        grid: [],
        emptyCell: { x: 3, y: 3 },
        moves: 0,
        score: 0,
        gameStarted: false,
        gameOver: false,
        photo: samplePhoto,
        gridSize: 4,

        init() {
            this.initializeGrid();
            this.shuffleGrid();
        },

        initializeGrid() {
            this.grid = [];
            let count = 1;
            
            for (let y = 0; y < this.gridSize; y++) {
                const row = [];
                for (let x = 0; x < this.gridSize; x++) {
                    if (y === this.gridSize - 1 && x === this.gridSize - 1) {
                        row.push(null); // Empty cell
                    } else {
                        row.push(count++);
                    }
                }
                this.grid.push(row);
            }
        },

        shuffleGrid() {
            // Make random valid moves to shuffle the puzzle
            for (let i = 0; i < 1000; i++) {
                const possibleMoves = this.getPossibleMoves();
                const randomMove = possibleMoves[Math.floor(Math.random() * possibleMoves.length)];
                this.moveCell(randomMove.x, randomMove.y, false);
            }
            
            this.moves = 0;
            this.score = 1000;
            this.gameStarted = false;
        },

        getPossibleMoves() {
            const moves = [];
            const directions = [
                {x: -1, y: 0}, // Left
                {x: 1, y: 0},  // Right
                {x: 0, y: -1}, // Up
                {x: 0, y: 1}   // Down
            ];

            directions.forEach(dir => {
                const newX = this.emptyCell.x + dir.x;
                const newY = this.emptyCell.y + dir.y;

                if (newX >= 0 && newX < this.gridSize && 
                    newY >= 0 && newY < this.gridSize) {
                    moves.push({x: newX, y: newY});
                }
            });

            return moves;
        },

        moveCell(x, y, countMove = true) {
            if (!this.canMove(x, y)) return;

            if (!this.gameStarted && countMove) {
                this.gameStarted = true;
            }

            // Swap cells
            const temp = this.grid[y][x];
            this.grid[y][x] = null;
            this.grid[this.emptyCell.y][this.emptyCell.x] = temp;
            
            // Update empty cell position
            this.emptyCell = {x, y};

            if (countMove) {
                this.moves++;
                this.score = Math.max(0, 1000 - (this.moves * 10));
                
                if (this.checkWin()) {
                    this.gameOver = true;
                    if (typeof updateHighScore === 'function') {
                        updateHighScore(this.score);
                    }
                }
            }
        },

        canMove(x, y) {
            return Math.abs(x - this.emptyCell.x) + Math.abs(y - this.emptyCell.y) === 1;
        },

        checkWin() {
            let count = 1;
            
            for (let y = 0; y < this.gridSize; y++) {
                for (let x = 0; x < this.gridSize; x++) {
                    if (y === this.gridSize - 1 && x === this.gridSize - 1) {
                        if (this.grid[y][x] !== null) return false;
                    } else {
                        if (this.grid[y][x] !== count++) return false;
                    }
                }
            }
            
            return true;
        },

        getCellStyle(value) {
            if (value === null) return '';

            const size = 100 / this.gridSize;
            const x = ((value - 1) % this.gridSize) * size;
            const y = Math.floor((value - 1) / this.gridSize) * size;

            return `background-position: ${x}% ${y}%;`;
        },

        resetGame() {
            this.grid = [];
            this.emptyCell = { x: 3, y: 3 };
            this.moves = 0;
            this.score = 0;
            this.gameStarted = false;
            this.gameOver = false;
            this.init();
        }
    };
}
