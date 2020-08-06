import React from 'react';
import {Link} from 'react-router-dom';

const NavMore = props => {
    return props.items && props.items.length > 0 && (
        <li className="lastClass">
            <Link className="more" to="#">更多<i className="iconfont icon-xiala"></i></Link>
            <ul className="clearfix downClass">
                {props.items.map(item => (
                    <li key={item.item.id}>
                        <Link to={`/category/${item.item.id}`}>{item.item.category_name}</Link>
                    </li>
                ))/*
                <li>
                    <Link to="#">汽车</Link>
                    <Link to="#">教育</Link>
                    <Link to="#">时尚</Link>
                    <Link to="#">女性</Link>
                    <Link to="#">星座</Link>
                    <Link to="#">健康</Link>
                </li>
                <li>
                    <Link to="#">育儿</Link>
                    <Link to="#">读书</Link>
                    <Link to="#">房产</Link>
                    <Link to="#">历史</Link>
                    <Link to="#">视频</Link>
                    <Link to="#">收藏</Link>
                </li>
                <li>
                    <Link to="#">佛学</Link>
                    <Link to="#">游戏</Link>
                    <Link to="#">旅游</Link>
                    <Link to="#">邮箱</Link>
                    <Link to="#">导航</Link>
                </li>
                */}
            </ul>
        </li>
    );
};

export default NavMore;
