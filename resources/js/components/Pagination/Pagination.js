import React from 'react';
import {Link} from 'react-router-dom';

const Pagination = props => {
    const {currPage, numSides, lastPage, url, pageParamKey} = props;
    const generatePageLinks = () => {
        const windowSize = numSides * 2 + 1;
        let pageLinks = [];
        if(lastPage <= (windowSize * 2) || ((currPage - numSides) <= (windowSize + 1)
            && (currPage + numSides) >= (lastPage - windowSize)))
        {
            // eg. lastPage=5, windowSize=3 => left=[1,2,3], right=[3,4,5] => result=[1,2,3,4,5]
            // or left=[1,2,3], right=[6,7,8], mid=[4,5,6] => result=[1,2,3,4,5,6,7,8]
            // or left=[1,2,3], right=[6,7,8], mid=[3,4,5] => result=[1,2,3,4,5,6,7,8]
            for(let page=1; page<=lastPage; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
        }else if((currPage + numSides) <= windowSize
            || (currPage - numSides) >= (lastPage - windowSize + 1))
        {
            // eg. left=[1,2,3], right=[8,9,10] and mid=[1,2,3] or [8,9,10] => result=[1,2,3],[...],[8,9,10]
            for(let page=1; page<=windowSize; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
            pageLinks.push((
                <Link key="#" to="#" onClick={e => e.preventDefault()}>...</Link>
            ));
            for(let page=(lastPage-windowSize+1); page<=lastPage; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
        }else if((currPage - numSides) > (windowSize + 1)
            && (currPage + numSides) < (lastPage - windowSize))
        {
            // eg. left=[1,2,3], right=[9,10,11], mid=[5,6,7] => result=[1,2,3],[...],[5,6,7],[...],[9,10,11]
            for(let page=1; page<=windowSize; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
            pageLinks.push((
                <Link key="#" to="#" onClick={e => e.preventDefault()}>...</Link>
            ));
            for(let page=(currPage-numSides); page<=(currPage+numSides); page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
            pageLinks.push((
                <Link key="#" to="#" onClick={e => e.preventDefault()}>...</Link>
            ));
            for(let page=(lastPage-windowSize+1); page<=lastPage; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
        }else if((currPage - numSides) <= (windowSize + 1)){
            // eg. left=[1,2,3], right=[8,9,10], mid=[3,4,5] => result=[1,2,3,4,5],[...],[8,9,10]
            for(let page=1; page<=(currPage+numSides); page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
            pageLinks.push((
                <Link key="#" to="#" onClick={e => e.preventDefault()}>...</Link>
            ));
            for(let page=(lastPage-windowSize+1); page<=lastPage; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
        }else{ // implicit: if((currPage + numSides) >= (lastPage - windowSize))
            // eg. left=[1,2,3], right=[8,9,10], mid=[6,7,8] => result=[1,2,3],[...],[6,7,8,9,10]
            for(let page=1; page<=windowSize; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
            pageLinks.push((
                <Link key="#" to="#" onClick={e => e.preventDefault()}>...</Link>
            ));
            for(let page=(currPage-numSides); page<=lastPage; page++){
                pageLinks.push((
                    <Link key={page} to={page == currPage ? '#' : `${url}?${pageParamKey}=${page}`}
                        className={page == currPage ? 'pageOn' : undefined}
                        onClick={checkSamePage(page, currPage)}>
                        {page}
                    </Link>
                ));
            }
        }
        return pageLinks;
    };

    const checkSamePage = (page, currPage) => (e) => {
        if(page == currPage){
            e.preventDefault();
        }
    };

    return (
        <div className="page">
            <p>
                {currPage-1 >= 1 && (
                    <Link to={`${url}?${pageParamKey}=${currPage-1}`}>
                        上一页
                    </Link>
                )}
                {generatePageLinks()}
                {currPage+1 <= lastPage && (
                    <Link to={`${url}?${pageParamKey}=${currPage+1}`}>
                        下一页
                    </Link>
                )}
            </p>
        </div>
    );
};

export default Pagination;
