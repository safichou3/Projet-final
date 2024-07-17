import { useEffect, useState } from "react";
import { getAllCategories } from "../../../api/apiCategory.tsx";
import ListCategories from "./components/ListCategories.tsx";
import Loader from "../../../components/Loader/Loader";
import Pagination from "../../../components/Pagination/Pagination";
import { Link } from "react-router-dom";

const Category = () => {
    const [link, setLink] = useState('');
    const [loading, setLoading] = useState(true);
    const [categories, setCategories] = useState([]);
    const [pagination, setPagination] = useState(null);

    function handlePagination(l) {
        setLink(l.url);
    }

    useEffect(() => {
        let shouldCancel = false;
        setLoading(true);

        async function getCategories(link) {
            try {
                const data = await getAllCategories(link);
                console.log(data);
                if (!shouldCancel) {
                    setCategories(data);  // Directly set the categories
                    // If the API does return pagination info, uncomment the next line
                    // setPagination(data.pagination);
                }
            } catch (e) {
                console.log(e);
            } finally {
                if (!shouldCancel) {
                    setLoading(false);
                }
            }
        }

        getCategories(link);

        return () => {
            shouldCancel = true;
        }
    }, [link]);

    return (
        <div>
            <h1>Mes categories - Admin</h1>
            <Link to="/admin/category/add">Ajouter catégorie</Link>
            {loading ? (
                <Loader />
            ) : (
                <>
                    {pagination && (
                        <Pagination pagination={pagination} changePagination={handlePagination} />
                    )}
                    <ListCategories categories={categories} />
                </>
            )}
        </div>
    );
};

export default Category;
