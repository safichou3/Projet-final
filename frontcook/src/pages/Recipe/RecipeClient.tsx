import { useEffect, useState } from "react";
import Loader from "../../components/Loader/Loader";
import Pagination from "../../components/Pagination/Pagination";
import ListRecipesClient from "./ListRecipesClient";
import { getAllRecipes } from "../../api/apiRecipe";
import style from './RecipeClient.module.scss';

const RecipeClient = () => {
    const [link, setLink] = useState('');
    const [loading, setLoading] = useState(true);
    const [recipes, setRecipes] = useState([]);
    const [searchQuery, setSearchQuery] = useState('');
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
                console.log("Fetched recipes:", data);
                if (!shouldCancel) {
                    setRecipes(data); // Directly set the data if it is already an array of recipes
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

        getRecipes(link);

        return () => {
            shouldCancel = true;
        }
    }, [link]);

    const filteredRecipes = recipes.filter(recipe =>
        recipe.title.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <div className={style.container}>
            <h1>Nos plats</h1>
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
                    {/* If pagination is not required, comment out the next line */}
                    <Pagination pagination={pagination} changePagination={handlePagination} />
                    <ListRecipesClient recipes={filteredRecipes} />
                </>
            )}
        </div>
    );
};

export default RecipeClient;
