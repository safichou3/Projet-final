import React from 'react';
import articleModel from "../../../models/articleModel";

const Article = ({article}: { article: articleModel }) => {
    return (
        <div>
            <h2>{article.title}</h2>
            <p>{article.content}</p>
        </div>
    );
};

export default Article;