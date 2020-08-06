import React from 'react';
import {Link} from 'react-router-dom';

const ArticleList = props => {
    return (
        <ul>
            {props.articles.map(article => (
                <li key={article.id}>
                    <div className="listBox clearfix">
                        <div className="listTitle">
                            <h2>
                                <Link to={`/articles/${article.id}`}>
                                    {article.article_title}
                                </Link>
                            </h2>
                        </div>
                        <div className="listCon clearfix">
                            <div className="listImg">
                                <Link to={`/articles/${article.id}`}>
                                    <img src={article.article_thumb} />
                                </Link>
                            </div>
                            <p>
                                <Link to={`/articles/${article.id}`}>
                                    {article.article_description}
                                </Link>
                            </p>
                        </div>
                    </div>
                    <div className="listTime clearfix">
                        <div>{article.created_at}</div>
                        <div className="listTags">
                            {/*<a href="#">破案记录</a>
                            <a href="#">投毒</a>
                            <a href="#">吴春红</a>*/}
                        </div>
                        <div className="listComment">
                            <Link to="#">评论</Link>
                        </div>
                    </div>
                </li>
            ))}
        </ul>
    );
};

export default ArticleList;
