import React from 'react';
import Modal from 'react-bootstrap/Modal';
import ModalTitle from 'react-bootstrap/ModalTitle';
import ModalHeader from 'react-bootstrap/ModalHeader';
import ModalBody from 'react-bootstrap/ModalBody';

const CustomModal = props => {
    return (
        /*
        <div className="modal fade" id={props.modalId} tabIndex="-1" role="dialog" aria-labelledby={props.modalLabel} aria-hidden="true">
            <div className="modal-dialog" role="document">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title" id={props.modalLabel}>{props.modalTitle}</h5>
                        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="modal-body">
                        {props.children}
                    </div>
                </div>
            </div>
        </div>*/
        <Modal show={props.show} onHide={props.onHide}>
            <ModalHeader closeButton>
                <ModalTitle>{props.modalTitle}</ModalTitle>
            </ModalHeader>
            <ModalBody>
                {props.children}
            </ModalBody>
        </Modal>
    );
};

export default CustomModal;
