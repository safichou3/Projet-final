import styles from './Pagination.module.scss'

const Pagination = ({pagination,changePagination}) => {
    return pagination !== null && pagination !== undefined && pagination.links !== undefined && pagination.links.length ? (
        <>
            <ul className={styles.pagination}>
                {pagination?.links.map((link,index) => {
                        return (
                            <li
                                className={pagination.current_page === link.num ? styles.current: ''}
                                onClick={() => changePagination(link)}
                                key={link.num === '...' ? '...'+index : link.num }
                            >{link.num}</li>
                        )
                    }
                )}
            </ul>
        </>
    ) : '';
};

export default Pagination;
