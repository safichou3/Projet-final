import styles from './GameMolkky.module.scss'
import StarterMolkky from "./StarterMolkky";
import {useState} from "react";

function GameMolkky({isStart,startParty,successParty})
{
    const [indexEquipe, setIndexEquipe] = useState(0)
    const [score, setScore] = useState([0,0]);
    const [nomEquipe, setNomEquipe] = useState(['Equipe 1','Equipe 2']);

    const arrayNbre = [1,2,3,4,5,6,7,8,9,10,11,12];

    function handleAddScore(nbre)
    {
        if(score[indexEquipe] + nbre === 50) {
            successParty()
        } else {
            if(score[indexEquipe] + nbre  > 50) {
                setScore(indexEquipe === 0 ? [25,score[1] ]:[score[0],25])
            } else {
                setScore(indexEquipe === 0 ? [score[0] + parseInt(nbre),score[1]] : [score[0] ,score[1] + parseInt(nbre)]);
            }
        }
        setIndexEquipe(indexEquipe === 0 ? 1 : 0);
    }

    function changeNom(i, value){
        setNomEquipe(i === 0 ? [value,nomEquipe[1]] : [nomEquipe[0], value])
    }
    return (
        <div>
            {isStart ? (
                (
                    <>
                        <div className="d-flex justify-content-center align-items-center">
                            <p>INDEX : { indexEquipe } ---- </p>
                            <p className={indexEquipe === 0 ? styles.actif : ''}>{nomEquipe[0]} ({ score[0] }) ----  </p>
                            <p className={indexEquipe === 1 ? styles.actif : ''}>{nomEquipe[1]} ({ score[1] })</p>
                        </div>
                        <div className={styles.allbtn}>
                            {arrayNbre.map((e) => {
                                return <button
                                    key={e}
                                    className={styles.btn}
                                    onClick={ () => handleAddScore(e) }
                                > {e} </button>
                            })}
                        </div>
                    </>
                )
            ):(
                <StarterMolkky nomEquipe={nomEquipe} changeNom={changeNom} startParty={startParty}/>
            )}
        </div>
    )
}

export default GameMolkky;