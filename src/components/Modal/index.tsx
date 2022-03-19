import { useContext } from "react";
import { ModalProvider, ModalContext } from "./context/ModalProvider";

const useModal = () => {
  const { setModal } = useContext(ModalContext);

  return { setModal };
};

export { ModalProvider, useModal };
