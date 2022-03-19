import { createContext } from "react";
import { ModalContextProps } from "../types";

const modalContext: ModalContextProps = {
  setModal: () => null,
};

const ModalContext = createContext(modalContext);

export default ModalContext;
