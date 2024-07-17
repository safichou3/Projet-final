import React from 'react';

// const Sidebar = (props) => {
const Sidebar = ({prenom, nom, age = 18, majeur = true, children}) => {

    // const {prenom, nom, age} = props

    return (
        <div>
            <p>Sidebar</p>
            {majeur ? (
                <p>Je suis majeur !</p>
            ) : (
                <p>Je suis mineur !</p>
            )}
            <p>Prénom : {prenom}, Nom : {nom}, Age : {age}</p>
            {children}
            {/*<p>Prénom : {props.prenom}, Nom : {props.nom}, Age : {props.age}</p>*/}
        </div>
    );
};

export default Sidebar;