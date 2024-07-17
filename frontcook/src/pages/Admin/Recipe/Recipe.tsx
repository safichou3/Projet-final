import { useEffect, useState } from "react";
import { getAllRecipes } from "../../../api/apiRecipe";
import ListRecipes from "./components/ListRecipes";
import Loader from "../../../components/Loader/Loader";
import Pagination from "../../../components/Pagination/Pagination";
import { Link } from "react-router-dom";

const Recipe = () => {
    const [link, setLink] = useState('');
    const [loading, setLoading] = useState(true);
    const [recipes, setRecipes] = useState([]);
    const [pagination, setPagination] = useState(null);

    function handlePagination(l) {
        setLink(l.url);
    }

    useEffect(() => {
        let shouldCancel = false;
        setLoading(true);

        async function getRecipes(link) {
            try {
                const data = await getAllRecipes(link);
                if (!shouldCancel) {
                    setRecipes(data);
                    setPagination(data.pagination);
                }
            } catch (e) {
                console.log(e);
            } finally {
                if (!shouldCancel) {
                    setLoading(false);
                }
            }
        }

        getRecipes(link);

        return () => {
            shouldCancel = true;
        };
    }, [link]);

    return (
        <div>
            <h1>My Recipes - Admin</h1>
            <Link to="/admin/recipe/add">Add Recipe</Link>
            {loading ? (
                <Loader />
            ) : (
                <>
                    <Pagination pagination={pagination} changePagination={handlePagination} />
                    <ListRecipes recipes={recipes} />
                </>
            )}
        </div>
    );
};

export default Recipe;
