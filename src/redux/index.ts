import { combineReducers, createStore, applyMiddleware } from "redux";
import thunk from "redux-thunk";

import { Logger } from "./types";
import blank from "./blank";

const reducer = combineReducers({ blank });

const logger: Logger =
  ({ getState }) =>
  (next) =>
  (action) => {
    console.group(action.type);
    console.info("dispatching", action);
    const result = next(action);
    console.log("next state", getState());
    console.groupEnd();
    return result;
  };

const store = createStore(
  reducer,
  applyMiddleware(
    ...[...(process.env.NODE_ENV === "development" ? [logger] : []), thunk]
  )
);

export { store };
