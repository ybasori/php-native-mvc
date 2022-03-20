import { useContext } from "react";
import { AlertProvider, AlertContext } from "./context/AlertProvider";

const useAlert = () => {
  const { setAlert } = useContext(AlertContext);

  return { setAlert };
};

export { AlertProvider, useAlert };
