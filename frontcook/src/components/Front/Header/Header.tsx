import styles from './Header.module.scss';
import Navbar from "./Navbar";
import {NavLink} from "react-router-dom";
import logo from "../../../assets/images/Logo.svg";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faCartShopping} from "@fortawesome/free-solid-svg-icons";

const Header = () => {


    return (
        <div className="wrap">
            <header>
                {/*<NavLink to="/" className={styles.logo}>
                    <img src={logo} alt="Logo"/>
                    Pizza de la mama
                </NavLink>*/}
                <Navbar/>

                <div className="cart">
                    <FontAwesomeIcon icon={faCartShopping}/>
                </div>


            </header>
        </div>
    );
};

export default Header;

