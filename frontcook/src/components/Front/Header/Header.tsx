import React, { useState } from 'react'; // Assurez-vous que useState est importé
import { NavLink } from 'react-router-dom';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCartShopping, faUser } from "@fortawesome/free-solid-svg-icons";
import { useAuth } from "../../../context/AuthContext";
import styles from './Header.module.scss';
import Navbar from "./Navbar";

const Header = () => {
    const { auth, logout } = useAuth();

    return (
        <div className="wrap">
            <header>
                <NavLink to="/">
                    ChefChezToi
                </NavLink>
                <Navbar/>
                <div className={styles.iconMenu}>
                    <div className="profile">
                        {auth ? (
                            <>
                                <NavLink to="/profile">
                                    <FontAwesomeIcon icon={faUser} />
                                </NavLink>
                                <button onClick={logout}>Logout</button>
                            </>
                        ) : (
                            <NavLink to="/login">
                                <FontAwesomeIcon icon={faUser} />
                            </NavLink>
                        )}
                    </div>
                    <NavLink to="/cart" className="cart">
                        <FontAwesomeIcon icon={faCartShopping} />
                    </NavLink>
                </div>
            </header>
        </div>
    );
};

export default Header;
