export interface Action {
  payload?: any;
  type: string;
}

export type Logger = (store: {
  getState: () => void;
}) => (next: (action: Action) => void) => (action: Action) => void;

export type Dispatch = (data: Action) => null;

export interface BlankState {
  blank: null;
}

export interface Reducers {
  blank: BlankState;
}
