import React from 'react';
import style from './Footer.module.scss';
import Logo from '../../../assets/images/Logo.svg';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {
    faFacebook,
    faInstagram,
    faSquareFacebook,
    faSquareTwitter,
    faTwitter
} from "@fortawesome/free-brands-svg-icons";


const Footer = () => {
    return (
        <footer className={style.footer}>
            <div className="wrap">
                <div className={style.footerflex}>
                    <div className={style.socials}>
                        {/*<div className={style.logo}><img src={Logo} alt=""/></div>*/}
                        <FontAwesomeIcon icon={faSquareTwitter}/>
                        <FontAwesomeIcon icon={faInstagram}/>
                        <FontAwesomeIcon icon={faSquareFacebook}/>
                    </div>
                    <div className={style.copyright}>
                        Copyrights 2024 - - Tous droits réservés
                    </div>
                </div>
            </div>
        </footer>
    );
};

export default Footer;