import React, {useContext} from 'react';
import {Link} from 'react-router-dom';
import NavList from './NavList';
import AuthContext from '../../AuthContext';
import Login from '../Login/Login';
import Logout from '../Login/Logout';
import Register from '../Register/Register';
import NavMore from './NavMore';

const Navbar = props => {
    const {isLoggedIn} = useContext(AuthContext);
    return (
        <div className="header_top">
            <div className="header_topNav clearfix">
                <div className="logo">
                    <Link to="#"><img src="/uploads/images/logo.png" /></Link>
                </div>
                <NavList items={props.articleCategories.length > 0 ? props.articleCategories.slice(0, 5) : []}
                    moreItems={props.articleCategories.length > 5 ? props.articleCategories.slice(5) : []} />
                <ul className="clearfix rightNav">
                    {!isLoggedIn ? (<><li><Login /></li><li><Register /></li></>) : (<li><Logout /></li>)}
                </ul>
                <div className="slideDown">
                    <i className="iconfont icon-daohang"></i>
                </div>
                <div className="mobileNav">
                    <ul className="clearfix">
                        <li><Link to="/">网站首页</Link></li>
                        {props.articleCategories.map(category => (
                            <li key={category.item.id}>
                                <Link to={`/category/${category.item.id}`}>{category.item.category_name}</Link>
                            </li>
                        ))}
                        {/*<li><Link to="#">网站首页</Link></li>
                        <li>
                            <Link to="#">
                            时政要闻
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            东北粮仓
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            东北制造
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            健康医养
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            环保交通
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            旅游时光
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            教育科技
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            体育娱乐
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            一带 一路
                            </Link>
                        </li>
                        <li>
                            <Link to="#">
                            专题报道
                            </Link>
                        </li>
                        <li>
                            <Link to="#">汽车</Link></li><li>
                            <Link to="#">教育</Link></li><li>
                            <Link to="#">时尚</Link></li><li>
                            <Link to="#">女性</Link></li><li>
                            <Link to="#">星座</Link></li><li>
                            <Link to="#">健康</Link>
                        </li>
                        <li>
                            <Link to="#">育儿</Link></li><li>
                            <Link to="#">读书</Link></li><li>
                            <Link to="#">房产</Link></li><li>
                            <Link to="#">历史</Link></li><li>
                            <Link to="#">视频</Link></li><li>
                            <Link to="#">收藏</Link>
                        </li>
                        <li>
                            <Link to="#">佛学</Link></li><li>
                            <Link to="#">游戏</Link></li><li>
                            <Link to="#">旅游</Link></li><li>
                            <Link to="#">邮箱</Link></li><li>
                            <Link to="#">导航</Link>
                        </li>*/}
                    </ul>
                </div>
            </div>
        </div>
    );
};

export default Navbar;
