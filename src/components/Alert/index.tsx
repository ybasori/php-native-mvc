import { useContext } from "react";
import { AlertProvider, AlertContext } from "./context/AlertProvider";

import "./style.scss";

const useAlert = () => {
  const { setAlert } = useContext(AlertContext);

  return { setAlert };
};

export { AlertProvider, useAlert };
