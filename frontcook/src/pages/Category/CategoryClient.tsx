import { useEffect, useState } from "react";
import ListCategoriesClient from "./ListCategoriesClient";
import Loader from "../../components/Loader/Loader";
import Pagination from "../../components/Pagination/Pagination";
import { getAllCategories } from "../../api/apiCategory";
import style from './CategoryClient.module.scss';

const CategoryClient = () => {
    const [link, setLink] = useState('');
    const [loading, setLoading] = useState(true);
    const [categories, setCategories] = useState([]);
    const [searchQuery, setSearchQuery] = useState('');
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
                    setCategories(data);
                    // If pagination is not required, comment out the next line
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

    const filteredCategories = categories.filter(category =>
        category.title.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <div className={style.container}>
            <h1>Mes catégories - Client</h1>
            <input
                className={style.searchbar}
                type="search"
                placeholder="Chercher"
                value={searchQuery}
                onChange={e => setSearchQuery(e.target.value)}
            />
            {loading ? (
                <Loader />
            ) : (
                <>
                    {pagination && (
                        <Pagination pagination={pagination} changePagination={handlePagination} />
                    )}
                    <ListCategoriesClient categories={filteredCategories} />
                </>
            )}
        </div>
    );
};

export default CategoryClient;
