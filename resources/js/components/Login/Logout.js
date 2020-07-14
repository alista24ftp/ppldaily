import React, {useContext} from 'react';
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

    return <button className="btn btn-danger" onClick={handleLogoutClick}>Logout</button>;
};

export default Logout;
