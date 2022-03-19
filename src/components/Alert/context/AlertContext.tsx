import { createContext } from "react";
import { AlertContextProps } from "../types";

const modalContext: AlertContextProps = {
  setAlert: () => null,
};

const ModalContext = createContext(modalContext);

export default ModalContext;
