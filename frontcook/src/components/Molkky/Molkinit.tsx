import {useState} from "react";
import SuccessMolki from "./SuccessMolkky";
import GameMolki from "./GameMolkky";

function MolkiInit() {
    const [success,setSuccess] = useState(false);
    const [isStart,setIsStart] = useState(false);

    function startParty() {
        setIsStart(true);
    }
    function successParty() {
        setSuccess(true);
    }
    function restart() {
        setIsStart(false);
        setSuccess(false);
    }

    return (
        <div className="d-flex p-20">
            <div className="card container p-20">
                <h1 className="mb-20">Jeux du molki</h1>
                { success ? (
                    <SuccessMolki restart={restart}/>
                ) : (
                    <GameMolki
                        isStart={isStart}
                        startParty={startParty}
                        successParty={successParty}
                    />
                )}
            </div>
        </div>
    )
}
export default MolkiInit;