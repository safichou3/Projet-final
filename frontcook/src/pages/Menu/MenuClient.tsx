import {useEffect, useState} from "react";
import {getAllMenus} from "../../api/apiMenu";
import Loader from "../../components/Loader/Loader";
import Pagination from "../../components/Pagination/Pagination";
import ListMenusClient from "./ListMenusClient";

const MenuClient = () => {
    const [link, setLink] = useState('');
    const [loading, setLoading] = useState(true);
    const [menus, setMenus] = useState([]);
    const [pagination, setPagination] = useState(null);

    function handlePagination(l) {
        // console.log(l)
        setLink(l.url)
    }

    useEffect(() => {
        let shouldCancel = false;
        setLoading(true);

        async function getMenus(link) {
            try {
                const data = await getAllMenus(link);
                console.log(data);
                if (!shouldCancel) {
                    setMenus(data.menus);
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

        getMenus(link);

        return () => {
            shouldCancel = true;
        }
    }, [link]);

    return (
        <div>
            <h1>Menus (Client)</h1>
            {loading ? (
                <Loader/>
            ) : (
                <>
                    <Pagination pagination={pagination} changePagination={handlePagination}/>
                    <ListMenusClient menus={menus}/>
                </>
            )}

        </div>
    );
};

export default MenuClient;
