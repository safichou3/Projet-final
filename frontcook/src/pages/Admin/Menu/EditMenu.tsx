import { FormEvent, useEffect, useState } from 'react';
import { useParams, useNavigate } from "react-router-dom";
import FormMenu from "./components/FormMenu";
import Loader from "../../../components/Loader/Loader";
import { apiEditMenu, getOneMenu } from "../../../api/apiMenu";

const EditMenu = () => {
    const { id } = useParams();
    const [isOk, setIsOk] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [dataForm, setDataForm] = useState({ title: '', description: '', price: '', availability: true });
    const [dataError, setDataError] = useState({ title: '', description: '', price: '', availability: '' });

    const navigateTo = useNavigate();

    async function handleSubmit(evt: FormEvent<HTMLFormElement>) {
        evt.preventDefault();
        setIsLoading(true);
        try {
            const data = await apiEditMenu(id, dataForm);
            if (data.status === 400 && !data.success) {
                setDataError(data.violations);
            } else {
                navigateTo('/admin/menu');
            }
        } catch (e) {
            console.log(e);
        } finally {
            setIsLoading(false);
        }
    }

    function handleChange(evt) {
        const { name, value } = evt.target;
        setDataForm({ ...dataForm, [name]: value });
    }

    useEffect(() => {
        async function getTheMenu(id) {
            try {
                const data = await getOneMenu(id);
                if (data.status === 404) {
                    navigateTo('/404', { replace: false });
                } else {
                    setDataForm(data);
                    setIsOk(true);
                }
            } catch (e) {
                console.log(e);
            }
        }
        getTheMenu(id);
    }, [id]);

    return (
        <div>
            <p>Edit menu </p>
            {!isOk ? (
                <Loader />
            ) : (
                <FormMenu
                    handleSubmit={handleSubmit}
                    handleChange={handleChange}
                    isLoading={isLoading}
                    dataError={dataError}
                    dataForm={dataForm}
                />
            )}
        </div>
    );
};

export default EditMenu;
