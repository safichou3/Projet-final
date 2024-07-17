const ErrorForm = ({message = ''}) => {
    return (
        <p className="error">{message}</p>
    );
};

export default ErrorForm;