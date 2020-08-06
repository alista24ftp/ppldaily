import React, {useState, useContext} from 'react';
import {Link} from 'react-router-dom';
import AuthContext from '../../AuthContext';
import LoginForm from './LoginForm';
import CustomModal from '../CustomModal';
import Button from 'react-bootstrap/Button';

const Login = props => {
    const [modalShow, setModalShow] = useState(false);
    const {handleLogin} = useContext(AuthContext);

    const handleModalShow = (e) => {
        e.preventDefault();
        setModalShow(true);
    }
    const handleModalHide = () => setModalShow(false);
    const handleLoginSuccess = () => {
        handleModalHide();
        handleLogin();
    };
    return (
        <>
            <Link to="#" onClick={handleModalShow}>
                登录
            </Link>
            <CustomModal show={modalShow} onHide={handleModalHide} modalTitle={"登录"}>
                <LoginForm successCallback={handleLoginSuccess} />
            </CustomModal>
        </>
    );
};

export default Login;
