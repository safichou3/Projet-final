import {useState} from "react";

function StarterMolkky({nomEquipe,startParty,changeNom})
{
    const [nomEquipe1, setNomEquipe1] = useState(nomEquipe[0])
    const [nomEquipe2, setNomEquipe2] = useState(nomEquipe[1])

    function handleNomEquipe1(e) {
        changeNom(0,e.target.value)
        setNomEquipe1(e.target.value);
    }
    function handleNomEquipe2(e) {
        changeNom(1,e.target.value)
        setNomEquipe2(e.target.value);
    }
    return (
        <div>
            <button onClick={startParty}>Commencer</button>
            <input onChange={handleNomEquipe1} type="text" value={nomEquipe1}/>
            <input onChange={handleNomEquipe2} type="text" value={nomEquipe2}/>
        </div>
    )
}
export default StarterMolkky;