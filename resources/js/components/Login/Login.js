import React, {useState, useContext} from 'react';
import AuthContext from '../../AuthContext';
import LoginForm from './LoginForm';
import CustomModal from '../CustomModal';
import Button from 'react-bootstrap/Button';

const Login = props => {
    const [modalShow, setModalShow] = useState(false);
    const {handleLogin} = useContext(AuthContext);

    const handleModalShow = () => setModalShow(true);
    const handleModalHide = () => setModalShow(false);
    const handleLoginSuccess = () => {
        handleModalHide();
        handleLogin();
    };
    return (
        <>
            <Button variant="primary" onClick={handleModalShow}>
                登录
            </Button>
            <CustomModal show={modalShow} onHide={handleModalHide} modalTitle={"登录"}>
                <LoginForm successCallback={handleLoginSuccess} />
            </CustomModal>
        </>
    );
};

export default Login;
