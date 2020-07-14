import React, {useState} from 'react';
import CustomModal from '../CustomModal';
import RegisterForm from './RegisterForm';
import Button from 'react-bootstrap/Button';

const Register = props => {
    const [modalShow, setModalShow] = useState(false);

    const handleModalShow = () => setModalShow(true);
    const handleModalHide = () => setModalShow(false);
    const handleRegisterSuccess = () => {
        alert('Register success');
        handleModalHide();
    };
    return (
        <>
            <Button variant="primary" onClick={handleModalShow}>
                注册
            </Button>
            <CustomModal show={modalShow} onHide={handleModalHide} modalTitle={"注册"}>
                <RegisterForm successCallback={handleRegisterSuccess} />
            </CustomModal>
        </>
    );
};

export default Register;
