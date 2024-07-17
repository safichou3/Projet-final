import React from "react";
import LabelForm from "./LabelForm";
import ErrorForm from "./ErrorForm";

const InputForm = ({ type = 'text', label, name, id, value, placeholder = '', handleChange, errorMessage = '', step, inputMode, pattern }) => {
    return (
        <div>
            {label && <LabelForm label={label} id={id} />}
            <input type={type}
                   name={name}
                   id={id}
                   value={value}
                   placeholder={placeholder}
                   onChange={handleChange}
                   step={step}
                   inputMode={inputMode}
                   pattern={pattern}
            />
            <ErrorForm message={errorMessage} />
        </div>
    );
};

export default InputForm;
