import {FormEvent, useState} from "react";
import {apiAddCategory} from "../../../api/apiCategory";
import FormCategory from "./components/FormCategory.tsx";

const AddCategory = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [isSuccess, setIsSuccess] = useState<boolean>(false);

    const [dataForm, setDataForm] = useState<object>({title: '', description: ''})
    const [dataError, setDataError] = useState<object>({title: '', description: ''})

    async function handleSubmit(evt: FormEvent<HTMLFormElement>) {
        evt.preventDefault();
        setIsLoading(true);
        try {
            const data = await apiAddCategory(dataForm);
            console.log(data);
            if(data.status === 400 && !data.success) {
                // error
                setDataError(data.violations)
            } else {
                // success
                setIsSuccess(true);
            }
        } catch (err) {
            console.log(err)
        } finally {
            setIsLoading(false);
        }
    }

    function handleChange(evt) {
        const {name, value} = evt.target;
        setDataForm({...dataForm, [name]: value});
    }

    return (
        <div>
            { isSuccess ? (
                <p>Merci pour votre catégorie.</p>
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

export default AddCategory;
