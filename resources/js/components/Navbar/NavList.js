import React, {useContext} from 'react';
import {Link} from 'react-router-dom';
import NavMore from './NavMore';
import AuthContext from '../../AuthContext';
import Login from '../Login/Login';
import Logout from '../Login/Logout';
import Register from '../Register/Register';

const NavList = props => {
    const {isLoggedIn} = useContext(AuthContext);
    return (
        <ul className="clearfix firstNav">
            <li><Link to="/">网站首页</Link></li>
            {props.items && props.items.length > 0 && props.items.map(item => (
                <li key={item.item.id}>
                    <Link to={`/category/${item.item.id}`}>{item.item.category_name}</Link>
                </li>
            ))}
            <NavMore items={props.moreItems} />
        </ul>
    );
};

export default NavList;
