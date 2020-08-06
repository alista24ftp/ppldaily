import React from 'react';
import {Link} from 'react-router-dom';

const RelatedArticleList = props => {
    const renderLists = () => {
        return [].concat(props.categories)
            .sort((a, b) => b.category.category_priority - a.category.category_priority)
            .map(cat => (
                <div key={cat.category.id} className="Confounding">
                    <h2 className="moduleTitle">
                        <Link to={`/category/${cat.category.id}`}>
                            {cat.category.category_name}
                        </Link>
                    </h2>
                    <ul>
                        {cat.articles.map(article => (
                            <li key={article.id} className="clearfix">
                                <Link to={`/articles/${article.id}`}>
                                    <img src={article.article_thumb} />
                                </Link>
                                <p>
                                    <Link to={`/articles/${article.id}`}>
                                        {article.article_title.substring(0, 26)}
                                    </Link>
                                    <span>{article.created_at}</span>
                                </p>
                            </li>
                        ))}
                    </ul>
                </div>
            ));
    };
    return (
        <>
        {renderLists()}
        </>
    );
};

export default RelatedArticleList;
