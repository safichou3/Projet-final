import styles from './Loader.module.scss';

import LoaderLogo from './loader.svg';

function Loader()
{
    return (
        <div className={styles.loader}>
            <img src={LoaderLogo} alt=""/>
        </div>
    )
}
export default Loader;
