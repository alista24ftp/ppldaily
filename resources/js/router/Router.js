import React from 'react';
import {Switch, Route} from 'react-router-dom';
import NotFound from '../pages/error/NotFound';
import Home from '../pages/news/Home';
import Category from '../pages/news/Category';
import Article from '../pages/news/Article';
import TrendingArticles from '../components/Article/TrendingArticles';

const Router = props => (
    <Switch>
        <Route exact path="/" component={Home} />
        <Route path="/category/:cid">
            <Category>
                <TrendingArticles />
            </Category>
        </Route>
        <Route path="/articles/:aid">
            <Article>
                <TrendingArticles />
            </Article>
        </Route>
        <Route component={NotFound} />
    </Switch>
);

export default Router;
