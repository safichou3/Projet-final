import React, {useState} from 'react';
import ScoreBoard from '../Scoreboard/Scoreboard';
import AddPlayerForm from '../AddPlayersForm/AddPlayersForm';

function Game() {
    const [players, setPlayers] = useState([]);
    const [scores, setScores] = useState([]);
    const [currentPlayer, setCurrentPlayer] = useState(0);
    const [gameOver, setGameOver] = useState(false);
    const [winner, setWinner] = useState('');

    const addPlayer = (name) => {
        setPlayers([...players, name]);
        setScores([...scores, 0]);
    };

    const handleThrow = () => {
        if (gameOver) return;

        const pinsDown = Math.floor(Math.random() * 13);
        const score = pinsDown === 1 ? Math.floor(Math.random() * 12) + 1 : pinsDown;
        const newScores = [...scores];
        newScores[currentPlayer] += score;

        if (newScores[currentPlayer] === 50) {
            setGameOver(true);
            setWinner(players[currentPlayer]);
        } else if (newScores[currentPlayer] > 50) {
            newScores[currentPlayer] = 25;
        }

        setScores(newScores);
        setCurrentPlayer((currentPlayer + 1) % players.length);
    };

    const resetGame = () => {
        setScores(new Array(players.length).fill(0));
        setCurrentPlayer(0);
        setGameOver(false);
        setWinner('');
    };

    return (
        <div>
            <h1>Mölkky Game</h1>
            <AddPlayerForm onAddPlayer={addPlayer}/>
            <ScoreBoard scores={scores} players={players}/>
            {!gameOver ? (
                <div>
                    <h2>{players[currentPlayer]}'s Turn</h2>
                    <button onClick={handleThrow}>Lancer</button>
                </div>
            ) : (
                <div>
                    <h2>Game Over! {winner} wins!</h2>
                    <button onClick={resetGame}>Restart Game</button>
                </div>
            )}
            {gameOver && (
                <button onClick={resetGame}>Restart Game</button>
            )}
        </div>
    );
}

export default Game;