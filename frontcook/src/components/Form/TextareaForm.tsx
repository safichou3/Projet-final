import React from "react";
import LabelForm from "./LabelForm";
import ErrorForm from "./ErrorForm";

const TextareaForm = ({ label, name, id, value, placeholder = '', handleChange, errorMessage = '' }) => {
    return (
        <div>
            {label && <LabelForm label={label} id={id} />}
            <textarea
                name={name}
                id={id}
                value={value}
                placeholder={placeholder}
                onChange={handleChange}
            />
            <ErrorForm message={errorMessage} />
        </div>
    );
};

export default TextareaForm;
