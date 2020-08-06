import React, {useState, useEffect} from 'react';
import {Link} from 'react-router-dom';

const Subcategories = props => {
    return (
        <div className="header_bottom">
            <div className="nav">
                <ul className="clearfix">
                    <ul className="clearfix">
                        {props.parent && <li><Link to={`/category/${props.parent.id}`}>首页</Link></li>}
                        {props.otherCategories.map(category => (
                            <li key={category.id}>
                                <Link to={`/category/${category.id}`}>{category.category_name}</Link>
                            </li>
                        ))}
                    </ul>
                </ul>
            </div>
        </div>
    );
};

export default Subcategories;
