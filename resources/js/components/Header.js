import React, {useState, useEffect} from 'react';
import {Link} from 'react-router-dom';
import Navbar from './Navbar/Navbar';

const Header = props => {
    const [articleCategories, setArticleCategories] = useState([]);
    useEffect(() => {
        async function fetchArticleCategories(){
            try{
                let response = await axios.get('/api/v1/articlecategories');
                if(response.status == 200){
                    setArticleCategories(response.data);
                }
            }catch(err){
                console.error(err);
            }
        }
        fetchArticleCategories();
    }, []);

    return (
        <header>
            <Navbar articleCategories={articleCategories} />

            <div className="advertise7">
                <Link to="#">
                    <img src="/uploads/images/consultation-logo.jpg" alt="" />
                </Link>
            </div>

            <div className="header_middle clearfix">
                <div className="logo">
                    <Link to="#">
                        <img src="/uploads/images/logo.png" />
                    </Link>
                </div>
                <div className="header_middleRight">
                    <form className="clearfix formSelect">
                        <div className="select_box"  id="select">
                            <div className="select" >
                                <div className="showText">新闻</div>
                                <i className="iconfont icon-xiala"></i>
                            </div>
                            <div className="select_down">
                                <p>新闻</p>
                                <p>图片</p>
                                <p>博客</p>
                                <p>视频</p>
                            </div>
                        </div>
                        <div className="form_input">
                            <input type="text" />
                        </div>
                        <div className="form_search">
                            <i className="iconfont icon-sousuo"></i>
                        </div>
                    </form>
                </div>
            </div>
            {/*
            <div className="header_bottom">
                <div className="nav">
                    <ul className="clearfix">
                        <li><Link to="#">时政要闻</Link><Link to="#">今日头条</Link><Link to="#">微直播</Link><Link to="#">新闻眼</Link></li>
                        <li><Link to="#">东北粮仓</Link><Link to="#">红头文件</Link><Link to="#">村长代言</Link><Link to="#">科普讲堂</Link></li>
                        <li><Link to="#">东北制造</Link><Link to="#">新品发布</Link><Link to="#">老字号</Link><Link to="#">大工匠</Link></li>
                        <li><Link to="#">健康医养</Link><Link to="#">大健康</Link><Link to="#">医疗卫生</Link><Link to="#">养生养老</Link></li>
                        <li><Link to="#">环保交通</Link><Link to="#">通缉真相</Link><Link to="#">爱护蓝天</Link><Link to="#">陆海空畅</Link></li>
                        <li><Link to="#">旅游时光</Link><Link to="#">吃好好玩</Link><Link to="#">驴妈妈</Link><Link to="#">出谋划策</Link></li>
                        <li><Link to="#">教育科技</Link><Link to="#">机器人产业</Link><Link to="#">育儿心经</Link><Link to="#">发明魔方</Link></li>
                        <li><Link to="#">体育娱乐</Link><Link to="#">赛事演艺</Link><Link to="#">大家名人</Link><Link to="#">国之瑰宝</Link></li>
                        <li><Link to="#">一带一路</Link><Link to="#">文化长廊</Link><Link to="#">奇闻要事</Link><Link to="#">优品汇</Link></li>
                        <li><Link to="#">专题报道</Link><Link to="#">主题宣传</Link><Link to="#">聚焦热点</Link><Link to="#">人物访谈</Link></li>
                    </ul>
                </div>
            </div>*/}
        </header>
    );
}

export default Header;
