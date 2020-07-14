import React from 'react';
import {Switch, Route} from 'react-router-dom';
import NotFound from '../pages/error/NotFound';
import Home from '../pages/news/Home';

const Router = props => (
    <Switch>
        <Route exact path="/" component={Home} />
        <Route component={NotFound} />
    </Switch>
);

export default Router;
