import React, { MouseEventHandler } from "react";
import { ModalDataProps } from "./types";

interface ModalProps extends ModalDataProps {
  onClose: () => void;
}

const Modal: React.FC<ModalProps> = ({
  onClose,
  title,
  body,
  onConfirm,
  confirmText,
  onCancel,
  cancelText,
}) => {
  const onCancelDup: MouseEventHandler<HTMLButtonElement | HTMLDivElement> = (
    e
  ) => {
    if (onCancel) {
      onCancel(e);
    }
    onClose();
  };
  const onConfirmDup: MouseEventHandler<HTMLButtonElement> = (e) => {
    if (onConfirm) {
      onConfirm(e);
    }
  };

  return (
    <div className="modal is-active">
      <div className="modal-background" onClick={onCancelDup}></div>
      <div className="modal-card">
        <header className="modal-card-head">
          <p className="modal-card-title">{title}</p>
          <button
            className="delete"
            aria-label="close"
            onClick={onCancelDup}
          ></button>
        </header>
        {body && <section className="modal-card-body">{body}</section>}
        <footer className="modal-card-foot">
          {confirmText && (
            <button className="button is-success" onClick={onConfirmDup}>
              {confirmText}
            </button>
          )}

          <button className="button" onClick={onCancelDup}>
            {cancelText ? cancelText : "Cancel"}
          </button>
        </footer>
      </div>
    </div>
  );
};

export default Modal;
