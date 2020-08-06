import React, {useState} from 'react';
import {Link} from 'react-router-dom';
import CustomModal from '../CustomModal';
import RegisterForm from './RegisterForm';
import Button from 'react-bootstrap/Button';

const Register = props => {
    const [modalShow, setModalShow] = useState(false);

    const handleModalShow = (e) => {
        e.preventDefault();
        setModalShow(true);
    }
    const handleModalHide = () => setModalShow(false);
    const handleRegisterSuccess = () => {
        alert('Register success');
        handleModalHide();
    };
    return (
        <>
            <Link to="#" onClick={handleModalShow}>
                注册
            </Link>
            <CustomModal show={modalShow} onHide={handleModalHide} modalTitle={"注册"}>
                <RegisterForm successCallback={handleRegisterSuccess} />
            </CustomModal>
        </>
    );
};

export default Register;
