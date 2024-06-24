import React, {useState, useEffect} from 'react';
import {NavLink} from 'react-router-dom';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faBarsStaggered, faCartShopping, faXmark} from "@fortawesome/free-solid-svg-icons";
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
        // <div className={styles.container}>
            <nav className={styles.navbar}>

                <div className={`${styles.navLinks} ${toggleMenu ? styles.show : ''}`}>
                    <NavLink to="/" className={styles.navLink}>
                        Home
                    </NavLink>
                    <NavLink to="/services" className={styles.navLink}>
                        Services
                    </NavLink>
                    <NavLink to="/about" className={styles.navLink}>
                        About
                    </NavLink>
                    <NavLink to="/contact" className={styles.navLink}>
                        Contact
                    </NavLink>
                    <NavLink to="/admin/category" className={styles.navLink}>
                        Categories
                    </NavLink>
                </div>
                <div className={styles.menuToggle} onClick={() => setToggleMenu(!toggleMenu)}>
                    {toggleMenu ? (<FontAwesomeIcon icon={faXmark}/>) : (<FontAwesomeIcon icon={faBarsStaggered} rotation={180} />)}
                </div>

            </nav>
        // </div>
    );
};

export default Navbar;

