import React, {useState, useEffect} from 'react';
import {Link, useParams} from 'react-router-dom';
import Subcategories from '../../components/ArticleCategory/Subcategories';
import RelatedArticleList from '../../components/Article/RelatedArticleList';

const Article = props => {
    const {aid} = useParams();
    const [article, setArticle] = useState(null);
    const [parentCategory, setParentCategory] = useState(null);
    const [otherCategories, setOtherCategories] = useState(null);
    //const [relatedArticles, setRelatedArticles] = useState([]);
    const [loading, setLoading] = useState(true);
    const [relatedLoading, setRelatedLoading] = useState(true);

    const [_title, setTitle] = useState(null);
    const [_source, setSource] = useState(null);
    const [_sourceUrl, setSourceUrl] = useState(null);
    const [_pic, setPic] = useState('http://www.newsucai.cn/static/home/images/jpg/__header.jpg');
    const [_showcount, setShowCount] = useState(null);
    const [_desc, setDesc] = useState(null);
    const [_summary, setSummary] = useState(null);
    const [_site, setSite] = useState(null);
    const [_url, setUrl] = useState('http://www.newsucai.cn');
    const [textFontSize, setTextFontSize] = useState(16);
    const [textFontSizeUnit, setTextFontSizeUnit] = useState('px');

    useEffect(() => {
        setLoading(true);
        if(aid){
            fetchArticle();
        }
    }, [aid]);

    const fetchRelated = async (categoryId) => {
        try{
            let response = await axios.get(`/api/v1/articlecategories/${categoryId}/related`);
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

    const fetchArticle = async () => {
        try{
            const response = await axios.get(`/api/v1/articles/${aid}`);
            console.log(response);
            if(response.status == 200){
                setArticle(response.data);
                fetchRelated(response.data.article_category_id);
            }
        }catch(err){
            console.error(err);
            setArticle(null);
        }finally{
            setLoading(false);
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

    const zoomIn = event => {
        event.preventDefault();
        setTextFontSize(textFontSize + 2);
    };

    const zoomOut = event => {
        event.preventDefault();
        setTextFontSize(textFontSize - 2);
    };

    //分享至微信 css控制二维码隐藏和出现
    //分享到新浪微博
    const shareToSinaWB = event => {
        event.preventDefault();
        let _shareUrl = 'http://v.t.sina.com.cn/share/share.php?title="123"';     //真实的appkey，必选参数
        _shareUrl += '&url='+ encodeURIComponent(_url||document.location);     //参数url设置分享的内容链接|默认当前页location，可选参数
        _shareUrl += '&title=' + encodeURIComponent(_title||document.title);    //参数title设置分享的标题|默认当前页标题，可选参数
        _shareUrl += '&source=' + encodeURIComponent(_source||'');
        _shareUrl += '&sourceUrl=' + encodeURIComponent(_sourceUrl||'');
        _shareUrl += '&content=' + 'utf-8';   //参数content设置页面编码gb2312|utf-8，可选参数
        _shareUrl += '&pic=' + encodeURIComponent(_pic||'');  //参数pic设置图片链接|默认为空，可选参数
        window.open(_shareUrl,'_blank');
    };

    //分享到QQ空间
    const shareToQzone = event => {
        event.preventDefault();
        let _shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
        _shareUrl += 'url=' + encodeURIComponent(_url||document.location);   //参数url设置分享的内容链接|默认当前页location
        _shareUrl += '&showcount=' + _showcount||0;      //参数showcount是否显示分享总数,显示：'1'，不显示：'0'，默认不显示
        _shareUrl += '&desc=' + encodeURIComponent(_desc||'分享的描述');    //参数desc设置分享的描述，可选参数
        _shareUrl += '&summary=' + encodeURIComponent(_summary||'分享摘要');    //参数summary设置分享摘要，可选参数
        _shareUrl += '&title=' + encodeURIComponent(_title||document.title);    //参数title设置分享标题，可选参数
        _shareUrl += '&site=' + encodeURIComponent(_site||'');   //参数site设置分享来源，可选参数
        _shareUrl += '&pics=' + encodeURIComponent(_pic||'');   //参数pics设置分享图片的路径，多张图片以＂|＂隔开，可选参数
        window.open(_shareUrl,'_blank');
    };

    //分享到qq
    const shareToqq = event => {
        event.preventDefault();
        let _shareUrl = 'https://connect.qq.com/widget/shareqq/iframe_index.html?';
        _shareUrl += 'url=' + encodeURIComponent(_url||location.href);   //分享的链接
        _shareUrl += '&title=' + encodeURIComponent(_title||document.title);     //分享的标题
        window.open(_shareUrl,'_blank');
    };

    return loading ? (<h1>Loading...</h1>) : (
        <>
        {!relatedLoading && <Subcategories parent={parentCategory} otherCategories={fetchOnlyCategories()} />}
        <div className="wrapList clearfix">
            <div className="consultation-title">
                {/*<h1>神秘女子在上海狂领170亿发票 背后还有人物</h1>*/}
                <h1>{article.article_title}</h1>
                <div className="consultation-wrap">
                    <div className="consultation-wrap-left">
                        <span>{article.created_at}</span>
                        {/*<Link to="javascript:;">澎湃新闻</Link>*/}
                        <a href={article.article_source_link || '#'}>{article.article_source}</a>
                    </div>
                    <div className="consultation-wrap-right">
                        <ul>
                            <li><Link to="#"  className="smaller" onClick={zoomOut}></Link></li>
                            <li><Link to="#" className="bigger" onClick={zoomIn}></Link></li>
                            {/*<li className="share_xlwb" onClick={shareToSinaWB}>
                                <Link to="#" title="分享到微博"></Link>
                            </li>
                            <li className="bds_weixin">
                                <Link to="#" className="bds_weixin"  title="分享到微信"></Link>
                                <div className="wechat-share">
                                    <img src="../hnjtt/image/o_wechart_share.png" />
                                    <p>微信扫一扫</p>
                                </div>
                            </li>
                            <li onClick={shareToqq}>
                                <Link to="#"  title="分享到QQ"></Link>
                            </li>*/}
                        </ul>
                    </div>
                </div>
            </div>
            <div className="list-main">
                <div className="listLeft">
                    {/*广告图*/}
                    <div className="listLeft-logo">
                        <img src="../hnjtt/image/consultation-logo.jpg" alt=""/>
                    </div>
                    {/*正文开始*/}
                    <div className="newDetail_txt" dangerouslySetInnerHTML={{__html: article.article_content}} style={{fontSize: `${textFontSize}${textFontSizeUnit}`}}>
                        {/*article.article_content*/}
                        {/*<p>原标题：神秘女子在上海狂领170亿发票，背后还有人物</p>
                        <p>澎湃新闻记者 杨帆</p>
                        <p>近日，上海宝山警方成功破获一起特大虚开增值税专用发票案，循线查明虚开单位260余家，涉案总金额高达170余亿元。警方抓获犯罪团伙成员詹某、陈某、张某等共13人，这是近年来上海侦破的涉税领域金额特别巨大的虚开案件之一。</p>
                        <div className="listLeft-txt-img">
                            <img src="../hnjtt/image/Urs7-hmhafir8464026.jpg" alt=""/>
                            <p>犯罪团伙网购的空壳公司相关材料。本文图片 澎湃新闻记者 杨帆</p>
                        </div>
                        <p><strong>神秘女子背后还有人物</strong></p>
                        <p>今年以来，多名男子向各区税务机关申领82家公司的增值税专用发票、22家公司的普通发票，引起了警方与税务部门的注意。</p>
                        <p>警方发现，这些公司受票、开票的关联企业法定代表人、股东、财务人员信息均不真实，也就是所谓的“空壳”公司。侦查员判断，这可能是一个购票团伙，涉嫌虚开增值税发票。</p>
                        <p>通过初步调查，一名姓陈的女子进入视线。侦查员进一步串并案情发现，这名神秘的女子陈某不但自己代领发票，还雇佣了多名男子，以公司名义至各区的税务机关申领一定数量的发票，由于每次具体购票人均不同，所以前期很难被察觉。</p>
                        <p>侦查员循线追踪，发现了神秘女子背后另有一个人物——男子“张小陈”。陈某一直受“张小陈”雇佣前去购领发票，专案组立刻将工作重点转移到“张小陈”身上。</p>
                        <div className="listLeft-txt-img">
                            <img src="../hnjtt/image/6LR3-hmhafir8464083.jpg" alt=""/>
                            <p>犯罪团伙非法所得的公民身份证。</p>
                        </div>
                        <p><strong>网络收购空壳公司，大量使用他人信息开户</strong></p>
                        <p>侦查员发现，“张小陈”系化名，其实姓詹。为了不打草惊蛇，专案组决定先摸清整个团伙架构。</p>
                        <p>专案组发现，詹某除了雇佣陈某为其购票，还专门从网络购入大量的公司，基本都是经营状况异常，也就是所谓的“空壳”公司。詹某还曾非法购得大量个人信息，用于银行卡开户，很可能是用于接受不同公司的开票费。</p>
                        <p>据以上线索，侦查员判断詹某掌控着一个完整的购票、开票团伙，负责购票和负责开票的团伙之间的并无交织，很可能均与詹某单线联系。而每家“空壳”公司一般领购两次发票后即停用，导致侦查员的追踪难上加难。但专案组通过2个多月大量、细致的信息比对，摸清了负责为詹某开票的另一伙人。</p>
                        <div className="listLeft-txt-img">
                            <img src="../hnjtt/image/rzwv-hmhafir8464116.jpg" alt=""/>
                            <p>空壳公司所需的虚假手机号码及手机。</p>
                        </div>
                        <p><strong>虚开单位超260家，已开额度170余亿</strong></p>
                        <p>摸清了团伙架构以后，侦查员全力投入对陈某手下购票人的守候伏击，经过多次实时跟踪，侦查员发现了该团伙的开票窝点很可能在浦东新区东方路某小区，在对小区进行实地排摸后掌握了确切地址。税务部门对已掌握的开票公司进行数据分析，断定该团伙尚有大量发票没有开出。为减少更多损失，专案组决定立即收网。</p>
                        <p>最终，专案组在税务部门的协助下，兵分多路同步出击，在浦东、宝山、嘉定等地分别抓获涉嫌虚开增值税专用发票及普通发票案的犯罪嫌疑人詹某、陈某（女）、张某等13人，并捣毁浦东新区东方路某小区内两个开票窝点，现场查获作案用的大量的公司营业执照、开票机、税控盘以及空白增值税专用发票、普通发票等。</p>
                        <p>经查，2017年7月至今，犯罪嫌疑人詹某等人，通过网络购入用于虚开发票的空壳公司，并雇佣他人从税务机关大量领购发票后，以收取一定开票费的方式对外虚开增值税专用发票及普通发票，经循线查明虚开单位260余家，开票额高达170余亿元。</p>
                        <p>目前，8名犯罪嫌疑人已被依法执行逮捕，另有5名犯罪嫌疑人被取保候审。</p>
                        <p style=" text-align: right;">责任编辑：吴金明 </p>*/}
                    </div>
                    {/*正文结束*/}

                    {/* 上一篇 下一篇  功能已有样式要改改*/}
                    {/*<div className="met-shownews-footer">*/}
                    {/*<ul className="pager pager-round" style="display:flex;">*/}
                        {/*<li className="previous disabled" style="border:1px solid red;">*/}
                            {/*{if condition="!empty($prev)"}*/}
                            {/*<Link to="{:url('home/News/index',array('id'=>$prev['n_id']))}" title="{$prev['news_title']}">*/}
                                {/*上一篇*/}
                                {/*<span aria-hidden="true" className='hidden-xs'>：{$prev['news_title']}</span>*/}
                            {/*</Link>*/}
                            {/*{else}*/}
                            {/*<Link to="javascript:;" title="没有了">*/}
                                {/*上一篇*/}
                                {/*<span aria-hidden="true" className='hidden-xs'>：没有了</span>*/}
                            {/*</Link>*/}
                            {/*{/if}*/}
                        {/*</li>*/}
                        {/*<li className="next " style="border:1px solid red;">*/}
                            {/*{if condition="!empty($next)"}*/}
                            {/*<Link to="{:url('home/News/index',array('id'=>$next['n_id']))}" title="{$next['news_title']}">*/}
                                {/*下一篇*/}
                                {/*<span aria-hidden="true" className='hidden-xs'>：{$next['news_title']}</span>*/}
                            {/*</Link>*/}
                            {/*{else}*/}
                            {/*<Link to="javascript:;" title="没有了">*/}
                                {/*下一篇*/}
                                {/*<span aria-hidden="true" className='hidden-xs'>：没有了</span>*/}
                            {/*</Link>*/}
                            {/*{/if}*/}
                        {/*</li>*/}
                    {/*</ul>*/}
                    {/*</div>*/}
                    {/* 上一篇 下一篇*/}
                </div>
                <div className="listRight">
                    {/*排行榜 start*/}
                    {/*<div className="Rankings">
                        <h2 className="moduleTitle">热点排行</h2>
                        <ul>
                            <li className="clearfix"><span className="spanColor">01</span><span><Link to="#">警方通报妻子带儿女为夫殉情事件:丈夫为骗保假死</Link></span></li>
                            <li className="clearfix"><span className="spanColor">02</span><span><Link to="#">警方通报妻子带儿女为夫殉情事件:丈夫为骗保假死</Link></span></li>
                            <li className="clearfix"><span className="spanColor">03</span><span><Link to="#">00后准备好上大学了吗?他们行李箱的装的都是这些</Link></span></li>
                            <li className="clearfix"><span>04</span><span><Link to="#">儿子欠下大笔债务跑路 母亲拿警察丈夫抚恤金还债</Link></span></li>
                            <li className="clearfix"><span>05</span><span><Link to="#">学生牛奶疑存安全隐患 教体局要求班主任先试喝</Link></span></li>
                            <li className="clearfix"><span>06</span><span><Link to="#">男子频遭家暴欲离婚被妻子在法院刺伤臀部</Link></span></li>
                            <li className="clearfix"><span>07</span><span><Link to="#">男子频遭家暴欲离婚被妻子在法院刺伤臀部</Link></span></li>
                            <li className="clearfix"><span>08</span><span><Link to="#">女童玩游戏12天花掉13万 “土豪排行榜”上排第一</Link></span></li>
                            <li className="clearfix"><span>09</span><span><Link to="#">美国女孩患病致面部变形试图自杀 遇爱情重获新生</Link></span></li>
                            <li className="clearfix"><span>10</span><span><Link to="#">河南一名高二学生参与群殴重伤 未脱离生命危险</Link></span></li>
                        </ul>
                    </div>*/}
                    {props.children}
                    {/*排行榜 end*/}

                    {/*图文横排 start*/}
                    {!relatedLoading && <RelatedArticleList categories={otherCategoriesToArray()} />}
                    {/*<div className="Confounding">
                        <h2 className="moduleTitle">独家策划</h2>
                        <ul>
                            <li className="clearfix"><Link to="#"><img src="../hnjtt/image/list.jpg"/></Link><p><Link to="#">警方通报妻子带儿女为夫殉情事件:丈夫为骗保假死</Link><span>2018-08-29</span></p></li>
                            <li className="clearfix"><Link to="#"><img src="../hnjtt/image/list.jpg"/></Link><p><Link to="#">警方通报妻子带儿女为夫殉情事件:丈夫为骗保假死</Link><span>2018-08-29</span></p></li>
                            <li className="clearfix"><Link to="#"><img src="../hnjtt/image/list.jpg"/></Link><p><Link to="#">00后准备好上大学了吗?他们行李箱的装的都是这些</Link><span>2018-08-29</span></p></li>
                            <li className="clearfix"><Link to="#"><img src="../hnjtt/image/list.jpg"/></Link><p><Link to="#">儿子欠下大笔债务跑路 母亲拿警察丈夫抚恤金还债</Link><span>2018-08-29</span></p></li>
                            <li className="clearfix"><Link to="#"><img src="../hnjtt/image/list.jpg"/></Link><p><Link to="#">学生牛奶疑存安全隐患 教体局要求班主任先试喝</Link><span>2018-08-29</span></p></li>
                        </ul>
                    </div>*/}
                    {/*图文横排 end*/}

                    {/*广告位 start*/}
                    <div className="advertise">
                        <Link to="#"><img src="../hnjtt/image/list.jpg" /></Link>
                    </div>
                    {/*广告位 end*/}
                </div>
            </div>
        </div>
        </>
    );
};

export default Article;
