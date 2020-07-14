import jwtDecode from 'jwt-decode';

export default {
    getLoginPayload: () => {
        let cookies = document.cookie.split(';').map(cookie => cookie.trim()).map(cookie => cookie.split('='));
        return cookies.find(cookie => cookie[0] === 'token_payload');
    },
    fetchUserInfo: async (payload) => {
        let payloadInfo = jwtDecode(`${payload}.1234567890`);
        let userId = payloadInfo.sub;
        try{
            let response = await axios.get(`/api/v1/users/${userId}/userinfo`);
            console.log(response);
            if(response.status == 200){
                let userInfo = response.data;
                return userInfo;
            }
            return null;
        }catch(err){
            console.error(err);
            return null;
        }
    },
    getUserInfo: () => {
        return JSON.parse(localStorage.getItem('userInfo'));
    },
    setUserInfo: (userInfo) => {
        localStorage.setItem('userInfo', JSON.stringify(userInfo));
    },
    removeUserInfo: () => {
        localStorage.removeItem('userInfo');
    }
};
