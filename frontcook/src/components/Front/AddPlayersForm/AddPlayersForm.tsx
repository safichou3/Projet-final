import React, {useState} from 'react';

function AddPlayerForm({onAddPlayer}) {
    const [playerName, setPlayerName] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!playerName) return; // Don't add empty names
        onAddPlayer(playerName);
        setPlayerName(''); // Reset input after adding
    };

    return (
        <form onSubmit={handleSubmit}>
            <input
                type="text"
                value={playerName}
                onChange={(e) => setPlayerName(e.target.value)}
                placeholder="Enter player's name"
            />
            <button type="submit">Add Player</button>
        </form>
    );
}

export default AddPlayerForm;
