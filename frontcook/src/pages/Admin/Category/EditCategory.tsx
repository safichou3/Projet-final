import {FormEvent, useEffect, useState} from 'react';
import {useParams} from "react-router";
import FormCategory from "./components/FormCategory.tsx";
import {useNavigate} from "react-router-dom";
import Loader from "../../../components/Loader/Loader.tsx";
import {apiEditCategory, getOneCategory} from "../../../api/apiCategory";
const EditCategory = () => {
    const {id} = useParams();
    const [isOk, setIsOk] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const [dataForm, setDataForm] = useState<object>({title: '',content: ''})
    const [dataError, setDataError] = useState<object>({title: '',content: ''})

    async function handleSubmit(evt: FormEvent<HTMLFormElement>) {
        evt.preventDefault();
        setIsLoading(true)
        try {
            const data = await apiEditCategory(id,dataForm);
            if(data.status === 400 && !data.success) {
                setDataError(data.violations);
            } else {
                navigateTo('/admin/category');
            }
        } catch (e) {
            console.log(e)
        } finally {
            setIsLoading(false)
        }
    }
    function handleChange(evt) {
        const {name, value} = evt.target;
        setDataForm({...dataForm, [name]: value});
    }

    const navigateTo = useNavigate()
    useEffect(() => {
        async function getTheCat(id) {
            try {
                const data = await getOneCategory(id);
                console.log(data);
                if(data.status === 404) {
                    console.log('error 404')
                    navigateTo('/404', { replace: false})
                } else {
                    setDataForm(data);
                    setIsOk(true);
                }

            } catch (e) {
                console.log(e);

            }
        }
        getTheCat(id)
    }, [id]);
    return (
        <div>
            <p>Edit category </p>
            { !isOk ? (
                <Loader />
            ): (
                <FormCategory
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

export default EditCategory;
