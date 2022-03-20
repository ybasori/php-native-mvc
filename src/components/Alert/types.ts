import { ReactChild } from "react";

export type ColorProps =
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

export type MessageProps = ReactChild | string;

export interface SetAlertDataProps {
  data: MessageProps;
  color: ColorProps;
  active: boolean;
  time: number;
}

export interface AlertDataProps extends SetAlertDataProps {
  id: string;
}

export type SetAlertProps = (
  data: MessageProps,
  config?: {
    color?: ColorProps;
    time?: number;
  }
) => void;

export interface AlertContextProps {
  setAlert: SetAlertProps;
}
