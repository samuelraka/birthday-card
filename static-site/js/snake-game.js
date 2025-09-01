// Snake Game
function createSnakeGame(updateHighScore, onGameOver) {
    return {
        grid: [],
        snake: [],
        food: null,
        direction: 'right',
        score: 0,
        gameOver: false,
        gameLoop: null,
        gridSize: 15,

        init() {
            this.resetGame();
        },

        startNewGame() {
            this.initializeGrid();
            this.initializeSnake();
            this.placeFood();
            this.setupControls();
            this.startGame();
        },

        initializeGrid() {
            this.grid = Array(this.gridSize).fill().map(() => 
                Array(this.gridSize).fill('empty')
            );
        },

        initializeSnake() {
            this.snake = [
                {x: 7, y: 7},
                {x: 6, y: 7},
                {x: 5, y: 7}
            ];
            this.direction = 'right';
            this.updateGrid();
        },

        updateGrid() {
            // Clear grid
            this.grid = this.grid.map(row => row.map(() => 'empty'));
            
            // Place snake
            this.snake.forEach((segment, index) => {
                if (segment.x >= 0 && segment.x < this.gridSize && 
                    segment.y >= 0 && segment.y < this.gridSize) {
                    this.grid[segment.y][segment.x] = index === 0 ? 'head' : 'body';
                }
            });

            // Place food
            if (this.food) {
                this.grid[this.food.y][this.food.x] = 'food';
            }
        },

        placeFood() {
            while (true) {
                const x = Math.floor(Math.random() * this.gridSize);
                const y = Math.floor(Math.random() * this.gridSize);
                
                if (this.grid[y][x] === 'empty') {
                    this.food = {x, y};
                    this.updateGrid();
                    break;
                }
            }
        },

        move() {
            if (this.gameOver) return;

            const head = {...this.snake[0]};
            
            // Grid coordinates start from top-left (0,0)
            // We invert Y movement to match natural direction
            switch(this.direction) {
                case 'up': head.y++; break;    // Move up by increasing Y
                case 'down': head.y--; break;  // Move down by decreasing Y
                case 'left': head.x--; break;  // Move left by decreasing X
                case 'right': head.x++; break; // Move right by increasing X
            }

            // Check for collision with walls or self
            if (this.checkCollision(head)) {
                this.endGame();
                return;
            }

            // Add new head
            this.snake.unshift(head);

            // Check if food was eaten
            if (head.x === this.food.x && head.y === this.food.y) {
                this.score += 100;
                this.placeFood();
            } else {
                this.snake.pop();
            }

            this.updateGrid();
        },

        checkCollision(head) {
            // Wall collision
            if (head.x < 0 || head.x >= this.gridSize || 
                head.y < 0 || head.y >= this.gridSize) {
                return true;
            }

            // Self collision
            return this.snake.some(segment => 
                segment.x === head.x && segment.y === head.y
            );
        },

        setupControls() {
            document.addEventListener('keydown', (e) => {
                const newDirection = {
                    'ArrowUp': 'up',      // Up arrow moves snake up (decrease Y)
                    'ArrowDown': 'down',  // Down arrow moves snake down (increase Y)
                    'ArrowLeft': 'left',  // Left arrow moves snake left (decrease X)
                    'ArrowRight': 'right',// Right arrow moves snake right (increase X)
                    'w': 'up',           // W key same as up arrow
                    's': 'down',         // S key same as down arrow
                    'a': 'left',         // A key same as left arrow
                    'd': 'right'         // D key same as right arrow
                }[e.key];

                if (newDirection) {
                    // Prevent 180-degree turns
                    const opposites = {
                        'up': 'down',
                        'down': 'up',
                        'left': 'right',
                        'right': 'left'
                    };

                    if (opposites[newDirection] !== this.direction) {
                        this.direction = newDirection;
                    }
                }
            });
        },

        startGame() {
            this.gameLoop = setInterval(() => {
                this.move();
            }, 200);
        },

        endGame() {
            if (this.gameOver) return; // Prevent multiple calls
            this.gameOver = true;
            clearInterval(this.gameLoop);
            if (typeof updateHighScore === 'function') {
                updateHighScore(this.score);
            }
            if (typeof onGameOver === 'function') {
                onGameOver(this.score);
            }
        },

        resetGame() {
            clearInterval(this.gameLoop);
            this.grid = [];
            this.snake = [];
            this.food = null;
            this.direction = 'right';
            this.score = 0;
            this.gameOver = false;
            this.startNewGame();
        }
    };
}
