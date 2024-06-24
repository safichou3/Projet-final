import React from 'react';

function ScoreBoard({ scores, players }) {
    return (
        <div>
            <h2>Scores</h2>
            <ul>
                {scores.map((score, index) => (
                    <li key={index}>{players[index]}: {score}</li>
                ))}
            </ul>
        </div>
    );
}

export default ScoreBoard;
