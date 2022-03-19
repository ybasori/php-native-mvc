import { Action, Dispatch, BlankState } from "./types";

const BLANK = "BLANK";

const initState: BlankState = {
  blank: null,
};

const blank = (state = initState, action: Action) => {
  switch (action.type) {
    case BLANK:
      return {
        ...state,
        blank: null,
      };
    default:
      return { ...state };
  }
};

export default blank;

export const getBlogIndex = () => async (dispatch: Dispatch) => {
  dispatch({ type: BLANK });
};
