import React from 'react';

const AuthContext = React.createContext({
    handleLogin: () => {},
    handleLogout: () => {}
});

export default AuthContext;
