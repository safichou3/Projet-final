import React, { useState, useEffect } from 'react'; // Assurez-vous que useState et useEffect sont importés
import { NavLink } from 'react-router-dom';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBarsStaggered, faXmark } from "@fortawesome/free-solid-svg-icons";
import styles from './Header.module.scss';

const Navbar = () => {
    const [windowWidth, setWindowWidth] = useState(window.innerWidth);
    const [toggleMenu, setToggleMenu] = useState(false);

    useEffect(() => {
        const handleResize = () => {
            setWindowWidth(window.innerWidth);
            setToggleMenu(windowWidth >= 768);
        };

        window.addEventListener("resize", handleResize);
        return () => {
            window.removeEventListener("resize", handleResize);
        };
    }, [windowWidth]);

    return (
        <nav className={styles.navbar}>
            <div className={`${styles.navLinks} ${toggleMenu ? styles.show : ''}`}>
                <NavLink to="/" className={styles.navLink}>
                    Accueil
                </NavLink>
                <NavLink to="/recipe" className={styles.navLink}>
                    Recettes
                </NavLink>
                <NavLink to="/category" className={styles.navLink}>
                    Catégories
                </NavLink>
            </div>
        </nav>
    );
};

export default Navbar;
