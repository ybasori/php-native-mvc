import React, { ReactChild, useEffect, useState, useCallback } from "react";
import Alert from "../Alert";
import { AlertDataProps, ColorProps, MessageProps } from "../types";
import AlertContext from "./AlertContext";
import "../style.scss";

interface AlertProviderProps {
  children: ReactChild;
}

const AlertProvider: React.FC<AlertProviderProps> = ({
  children,
  ...props
}) => {
  const [counter, setCounter] = useState(0);
  const [counterSecond, setCounterSecond] = useState(0);
  const [alertData, setAlertData] = useState<AlertDataProps[]>([]);

  const setAlert = (
    data: MessageProps,
    color: ColorProps = "default",
    time = 1500
  ) => {
    const dt = new Date();
    const newData = {
      id: `alert-${dt.getTime()}-${alertData.length}`,
      data,
      color,
      active: true,
      time: time + dt.getTime(),
    };

    setAlertData([newData as AlertDataProps, ...alertData]);
  };

  const removeModal = useCallback(
    (id: string) => {
      const filtered = alertData.filter(
        (item: AlertDataProps) => item.id != id
      );
      setAlertData([...filtered]);
    },
    [alertData]
  );

  useEffect(() => {
    const st = setTimeout(() => {
      setCounter((dt) => dt + 100);
      const ndt = new Date();

      setAlertData((alertDt) => {
        // eslint-disable-next-line prefer-const
        let newAlertDt = alertDt;
        for (let i = 0; i < newAlertDt.length; i++) {
          if (ndt.getTime() > newAlertDt[i].time) {
            newAlertDt[i].active = false;
          }
        }

        return newAlertDt;
      });
    }, 100);

    return () => clearTimeout(st);
  }, [counter]);

  useEffect(() => {
    const st = setTimeout(() => {
      setCounterSecond((dt) => dt + 1500);

      setAlertData((alertDt) => [...alertDt.filter((item) => item.active)]);
    }, 1500);

    return () => clearTimeout(st);
  }, [counterSecond]);

  return (
    <AlertContext.Provider value={{ setAlert }} {...props}>
      {children}

      {alertData.length > 0 && (
        <div className="alert">
          {alertData.map((item: AlertDataProps, index: number) => (
            <Alert
              key={`alert-${index + 1}`}
              onClose={() => {
                removeModal(item.id);
              }}
              color={item.color}
              active={item.active}
            >
              {item.data}
            </Alert>
          ))}
        </div>
      )}
    </AlertContext.Provider>
  );
};

export { AlertContext, AlertProvider };
