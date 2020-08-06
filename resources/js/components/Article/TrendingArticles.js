import React, {useState, useEffect} from 'react';
import {Link} from 'react-router-dom';

const TrendingArticles = props => {
    const [articles, setArticles] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(true);
        fetchTrendingArticles();
    }, []);

    const fetchTrendingArticles = async () => {
        try{
            const response = await axios.get(`/api/v1/articles/trending`);
            console.log(response);
            if(response.status == 200){
                setArticles(response.data);
            }
        }catch(err){
            console.error(err);
            setArticles([]);
        }finally{
            setLoading(false);
        }
    };

    return (!loading &&
        <div className="Rankings">
            <h2 className="moduleTitle">热点排行</h2>
            <ul>
                {articles.map((article, index) => (
                    <li key={article.id} className="clearfix">
                        <span className={index < 3 ? "spanColor" : ""}>{index + 1}</span>
                        <span>
                            <Link to={`/articles/${article.id}`}>{article.article_title}</Link>
                        </span>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default TrendingArticles;
