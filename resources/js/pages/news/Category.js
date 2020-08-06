import React, {useState, useEffect} from 'react';
import {Link, useParams, useLocation} from 'react-router-dom';
import ArticleList from '../../components/Article/ArticleList';
import Subcategories from '../../components/ArticleCategory/Subcategories';
import RelatedArticleList from '../../components/Article/RelatedArticleList';
import Pagination from '../../components/Pagination/Pagination';

const useQueryParams = () => {
    return new URLSearchParams(useLocation().search);
};

const Category = props => {
    const {cid} = useParams();
    const queryParams = useQueryParams();
    let currPage = queryParams.get('page');
    currPage = currPage && currPage >= 1 ? currPage : 1;

    const [articles, setArticles] = useState([]);
    //const [currPage, setCurrPage] = useState(1);
    //setCurrPage(currPage);

    const [parentCategory, setParentCategory] = useState(null);
    const [otherCategories, setOtherCategories] = useState(null);
    //const [relatedArticles, setRelatedArticles] = useState([]);

    const [loading, setLoading] = useState(true);
    const [relatedLoading, setRelatedLoading] = useState(true);

    useEffect(() => {
        setLoading(true);
        if(cid){
            fetchArticles();
        }
    }, [cid, currPage]);

    useEffect(() => {
        setRelatedLoading(true);
        if(cid){
            fetchRelated();
        }
    }, [cid]);

    const fetchArticles = async () => {
        try{
            let response = await axios.get(`/api/v1/articlecategories/${cid}/articles?page=${currPage}`);
            console.log(response);
            if(response.status == 200){
                setArticles(response.data);
            }
        }catch(err){
            console.error(err);
        }finally{
            setLoading(false);
        }
    };

    const fetchRelated = async () => {
        try{
            let response = await axios.get(`/api/v1/articlecategories/${cid}/related`);
            console.log(response);
            if(response.status == 200){
                setParentCategory(response.data.parent);
                //setOtherCategories(response.data.categories);
                //setRelatedArticles(response.data.articles);
                categorizeArticles(response.data.categories, response.data.articles);
            }
        }catch(err){
            console.error(err);
        }finally{
            setRelatedLoading(false);
        }
    };

    const categorizeArticles = (categories, articles) => {
        let articleCategories = {};
        categories.forEach(cat => {
            articleCategories[cat.id] = {category: cat, articles: []};
        });
        articles.forEach(article => {
            articleCategories[article.article_category_id].articles.push(article);
        });
        setOtherCategories(articleCategories);
    };

    const fetchOnlyCategories = () => {
        let onlyCategories = [];
        for(const [key, val] of Object.entries(otherCategories)){
            onlyCategories.push(val.category);
        }
        return onlyCategories;
    };

    const otherCategoriesToArray = () => {
        let otherCategoriesArr = [];
        for(const [key, val] of Object.entries(otherCategories)){
            otherCategoriesArr.push(val);
        }
        return otherCategoriesArr;
    };

    return (
        <>
        {!relatedLoading && (
            <Subcategories parent={parentCategory} otherCategories={fetchOnlyCategories()} />
        )}
        <div className="wrapList clearfix">
            {!loading && (
                <div className="listLeft">
                    <ArticleList articles={articles.data} />
                    <Pagination currPage={Number(currPage)} numSides={1} lastPage={Number(articles.last_page)}
                        url={`/category/${cid}`} pageParamKey={'page'} />
                </div>
            )}
            <div className="listRight">
                {/*排行榜 start*/}
                {props.children}
                {/*排行榜 end*/}

                {/*图文横排 start*/}
                {!relatedLoading && <RelatedArticleList categories={otherCategoriesToArray()} />}
                {/*图文横排 end*/}

                {/*广告位 start*/}
                <div className="advertise">
                    <Link to="#"><img src="../hnjtt/image/list.jpg" /></Link>
                </div>
                {/*广告位 end*/}
            </div>
        </div>
        </>
    );
};

export default Category;
