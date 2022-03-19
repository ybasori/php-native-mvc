import React from "react";
import { ColorProps } from "./types";

interface Props {
  color?: ColorProps;
  onClose: () => void;
  active: boolean;
}

const Alert: React.FC<Props> = ({
  children,
  color = "default",
  onClose,
  active,
}) => {
  const colorOpts: { [dt in ColorProps]: string } = {
    default: "",
    white: "is-white",
    light: "is-light",
    black: "is-black",
    dark: "is-dark",
    text: "is-text",
    ghost: "is-ghost",
    primary: "is-primary",
    link: "is-link",
    info: "is-info",
    success: "is-success",
    warning: "is-warning",
    danger: "is-danger",
    lightPrimary: "is-primary is-light",
    lightLink: "is-link is-light",
    lightInfo: "is-info is-light",
    lightSuccess: "is-success is-light",
    lightWarning: "is-warning is-light",
    lightDanger: "is-danger is-light",
  };

  return (
    <div className={`notification ${colorOpts[color]} ${active && "active"}`}>
      <button className="delete" onClick={onClose}></button>
      {children}
    </div>
  );
};

export default Alert;
