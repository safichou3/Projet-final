const LabelForm = ({label = '', id = ''}) => {
    return (
        <div>
            <label htmlFor={id}>
                {label}
            </label>
        </div>
    );
};

export default LabelForm;