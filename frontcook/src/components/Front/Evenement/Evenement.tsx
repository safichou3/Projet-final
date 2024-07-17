import React, {useState} from 'react';

const Evenement = () => {

    const [count, setCount] = useState(0)
    const [showCount, setShowCount] = useState(true)

    // let count = 0;

    function handleClick() {
        console.log('Click button 2')
        // count++;
        setCount(prev => prev + 1);
        setCount(prev => prev + 1);
        setCount(prev => prev + 1);
    }

    function handleInput(e) {
        console.log('Input')
        console.log(e)
        console.log(e.target.value)
    }

    function handleBlur() {
        console.log('Defocus')
    }

    function handleLink(evt) {
        evt.preventDefault()
        console.log('Clique sur le lien sans le suivre')
    }

    function handleParameter(e, param) {
        console.log(param)
    }

    return (
        <div>
            <h1>Events</h1>
            {showCount && <p>{count}</p>}
            <button onClick={() => setShowCount(!showCount)}>Show Count or not</button>


            <button onClick={() => {
                console.log('Click button 1')
            }}>Click here !
            </button>

            <button onClick={handleClick}>Click here !
            </button>

            <input type="text" onInput={handleInput} onBlur={handleBlur}/>

            <a onClick={handleLink} href="https://google.fr">Click me !</a>

            <ul>
                <li>
                    <button onClick={(e) => handleParameter(e, 12)}>Los parametros</button>
                    <button onClick={(e) => handleParameter(e, "Safia")}>Los parametros</button>
                    <button onClick={(e) => handleParameter(e, true)}>Los parametros</button>
                </li>
            </ul>
        </div>
    );
};

export default Evenement;