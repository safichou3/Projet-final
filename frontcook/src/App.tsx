import './App.scss'
import Header from "./components/Front/Header/Header";
import Footer from "./components/Front/Footer/Footer";
import Sidebar from "./components/Front/Sidebar/Sidebar";
import Articles from "./components/Front/Articles/Articles";
import Evenement from "./components/Front/Evenement/Evenement";
import Game from "./components/Front/Game/Game";
import MolkiInit from "./components/Molkky/Molkinit";
import {Outlet} from "react-router-dom";

function App() {

    return (
        <>
            <div>
                <Header/>
                {/*<Articles/>
                <Sidebar prenom="Safia" nom="Medjahed" age={24}/>
                <Sidebar prenom="Houda" nom="Ousbane" age={16} majeur={false}/>
                <Sidebar prenom="Antoine" nom="Brevet">
                    <button>Clique moi !</button>
                </Sidebar>
                <Evenement/>
                */}

                <Outlet/>

                <Footer/>
                {/*<Game/>*/}
                {/*<MolkiInit />*/}
            </div>
        </>
    )
}

export default App
