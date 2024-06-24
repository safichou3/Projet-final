import React, {Fragment} from 'react';
import Article from "../Article/Article";

const Articles = () => {
    const articles = [
        {title: 'titre 1', content: 'article 1', publish: true},
        {title: 'titre 2', content: 'article 2', publish: false},
        {title: 'titre 3', content: 'article 3', publish: true}
    ]
    return (
        <div>
            <h2>Mes articles</h2>
            {
                articles.filter(i => i.publish).map(a => (
                    /* <div key={a.title}>
                         <h2>{a.title}</h2>
                         <p>{a.content}</p>
                     </div>*/
                    <Article key={a.title} title={a.title} content={a.content} article={a}/>
                ))}

            {articles.filter(i => i.publish).map(a => (
                <Fragment key={a.title}><h2>{a.title}</h2></Fragment>
            ))}
        </div>
    );
};

export default Articles;