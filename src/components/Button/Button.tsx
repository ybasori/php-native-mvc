import React from "react";

type ColorProps =
  | "default"
  | "white"
  | "light"
  | "black"
  | "dark"
  | "text"
  | "ghost"
  | "primary"
  | "link"
  | "info"
  | "success"
  | "warning"
  | "danger"
  | "lightPrimary"
  | "lightLink"
  | "lightInfo"
  | "lightSuccess"
  | "lightWarning"
  | "lightDanger";

type SizeProps = "small" | "default" | "normal" | "medium" | "large";

interface Props {
  onClick: React.MouseEventHandler<HTMLButtonElement>;
  color?: ColorProps;
  size?: SizeProps;
  responsive?: boolean;
  full?: boolean;
}

const Button: React.FC<Props> = ({
  children,
  color = "default",
  size = "default",
  responsive = false,
  full = false,
  onClick,
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

  const sizeOpts: { [dt in SizeProps]: string } = {
    small: "is-small",
    default: "",
    normal: "is-normal",
    medium: "is-medium",
    large: "is-large",
  };

  return (
    <button
      className={`button ${colorOpts[color]} ${sizeOpts[size]} ${
        responsive && "is-responsive"
      } ${full && "is-fullwidth"}`}
      onClick={onClick}
    >
      {children}
    </button>
  );
};

export default Button;
