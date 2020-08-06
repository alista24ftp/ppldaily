import React, {useContext} from 'react';
import {Link} from 'react-router-dom';
import AuthContext from '../../AuthContext';

const Logout = props => {
    const {handleLogout} = useContext(AuthContext);

    const handleLogoutClick = async (e) => {
        try{
            let response = await axios.delete(`/api/v1/auth/current`);
            console.log(response);
            if(response.status == 204){
                handleLogout();
            }
        }catch(err){
            console.error(err);
        }
    }

    return <Link to="#" onClick={handleLogoutClick}>Logout</Link>;
};

export default Logout;
