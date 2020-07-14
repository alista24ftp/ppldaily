import React, {useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter} from 'react-router-dom';
import Router from './router/Router';
import Auth from './auth';
import AuthContext from './AuthContext';
import Header from './components/Header';
import Footer from './components/Footer';

const Index = props => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    useEffect(() => {
        async function loginStatus(){
            let loginPayload = Auth.getLoginPayload();
            if(loginPayload){
                setIsLoggedIn(true);
                let userInfo = Auth.getUserInfo();
                if(!userInfo){
                    userInfo = await Auth.fetchUserInfo(loginPayload);
                    console.log(userInfo);
                    Auth.setUserInfo(userInfo);
                }
            }else{
                logout();
            }
        }
        loginStatus();
    });

    const logout = () => {
        setIsLoggedIn(false);
        Auth.removeUserInfo();
    };

    return (
        <AuthContext.Provider value={{handleLogin: () => {setIsLoggedIn(true)}, handleLogout: logout}}>
            <BrowserRouter>
                <Header loggedIn={isLoggedIn} />
                <Router />
                <Footer />
            </BrowserRouter>
        </AuthContext.Provider>
    );
}

export default Index;

ReactDOM.render(<Index/>, document.getElementById('app'));
