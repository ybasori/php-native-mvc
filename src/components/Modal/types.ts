import { ReactChild, MouseEventHandler } from "react";

export interface SetModalDataProps {
  title: string;
  body: ReactChild;
  onConfirm?: MouseEventHandler<HTMLButtonElement>;
  confirmText: string;
  onCancel?: MouseEventHandler<HTMLButtonElement | HTMLDivElement>;
  cancelText?: string;
}

export interface ModalDataProps extends SetModalDataProps {
  id: string;
}

export interface ModalContextProps {
  setModal: (data: SetModalDataProps) => void;
}
