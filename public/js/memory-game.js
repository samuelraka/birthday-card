// Memory Match Game
function createMemoryGame(updateHighScore, onGameOver) {
    return {
        cards: [],
        flippedCards: [],
        matchedPairs: 0,
        score: 0,
        moves: 0,
        gameStarted: false,
        gameOver: false,
        photos: ['photo1.jpg', 'photo2.jpg', 'photo3.jpg', 'photo4.jpg'],
        maxMoves: 6,

        init() {
            this.initializeCards();
        },

        initializeCards() {
            // Create pairs of cards
            let cardPairs = [...this.photos, ...this.photos];
            // Shuffle cards
            cardPairs = cardPairs.sort(() => Math.random() - 0.5);
            // Create card objects
            this.cards = cardPairs.map((photo, index) => ({
                id: index,
                photo: photo,
                isFlipped: false,
                isMatched: false
            }));
        },

        flipCard(card) {
            if (!this.gameStarted) {
                this.gameStarted = true;
            }

            if (this.flippedCards.length === 2 || card.isFlipped || card.isMatched) {
                return;
            }

            card.isFlipped = true;
            this.flippedCards.push(card);

            if (this.flippedCards.length === 2) {
                this.moves++;
                
                // Check if exceeded max moves
                if (this.moves > this.maxMoves) {
                    this.gameOver = true;
                    if (typeof onGameOver === 'function') {
                        onGameOver(this.score);
                    }
                    return;
                }
                this.checkMatch();
            }
        },

        checkMatch() {
            const [card1, card2] = this.flippedCards;
            
            if (card1.photo === card2.photo) {
                card1.isMatched = true;
                card2.isMatched = true;
                this.matchedPairs++;
                this.score += 100;
                this.flippedCards = [];

                if (this.matchedPairs === this.photos.length) {
                    this.gameOver = true;
                    // Calculate bonus points based on moves
                    const bonus = Math.max(0, 1000 - (this.moves * 10));
                    this.score += bonus;
                    // Update high score
                    if (typeof updateHighScore === 'function') {
                        updateHighScore(this.score);
                    }
                }
            } else {
                // Flip cards back after a delay
                setTimeout(() => {
                    card1.isFlipped = false;
                    card2.isFlipped = false;
                    this.flippedCards = [];
                }, 1000);
            }
        },

        resetGame() {
            this.cards = [];
            this.flippedCards = [];
            this.matchedPairs = 0;
            this.score = 0;
            this.moves = 0;
            this.gameStarted = false;
            this.gameOver = false;
            this.initializeCards();
        }
    };
}
