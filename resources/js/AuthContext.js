import React from 'react';

const AuthContext = React.createContext({
    isLoggedIn: false,
    handleLogin: () => {},
    handleLogout: () => {}
});

export default AuthContext;
