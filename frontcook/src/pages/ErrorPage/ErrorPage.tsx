import {useRouteError} from "react-router-dom"

interface ErrorInterface {
    message?: string;
    statusText: string;
    status: number;
    data: object;
}

function ErrorPage() {

    const error = useRouteError() as ErrorInterface;

    return (
        <>
            <p>pages/ErrorPage/ErrorPage.js</p>
            <p> - {error.message || `${error.statusText} - ${error.status} - ${error.data}`} </p>
        </>
    )
}


export default ErrorPage;