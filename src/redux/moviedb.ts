import api from "../configs/api";
import { Action, Dispatch, MovieDBState, Reducers } from "./types";

const GET_POPULAR_MOVIES_LOADING = "GET_POPULAR_MOVIES_LOADING";
const GET_POPULAR_MOVIES_SUCCESS = "GET_POPULAR_MOVIES_SUCCESS";
const GET_POPULAR_MOVIES_ERROR = "GET_POPULAR_MOVIES_ERROR";

const GET_POPULAR_TVS_LOADING = "GET_POPULAR_TVS_LOADING";
const GET_POPULAR_TVS_SUCCESS = "GET_POPULAR_TVS_SUCCESS";
const GET_POPULAR_TVS_ERROR = "GET_POPULAR_TVS_ERROR";

const initState: MovieDBState = {
  popularMovies: {
    isLoading: false,
    data: null,
    error: null,
  },
  popularTVs: {
    isLoading: false,
    data: null,
    error: null,
  },
};

const moviedb = (state = initState, action: Action) => {
  switch (action.type) {
    case GET_POPULAR_MOVIES_LOADING:
      return {
        ...state,
        popularMovies: {
          ...state.popularMovies,
          isLoading: true,
          error: null,
        },
      };
    case GET_POPULAR_MOVIES_SUCCESS:
      return {
        ...state,
        popularMovies: {
          ...state.popularMovies,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_POPULAR_MOVIES_ERROR:
      return {
        ...state,
        popularMovies: {
          ...state.popularMovies,
          isLoading: false,
          error: action.payload,
        },
      };
    case GET_POPULAR_TVS_LOADING:
      return {
        ...state,
        popularTVs: {
          ...state.popularTVs,
          isLoading: true,
          error: null,
        },
      };
    case GET_POPULAR_TVS_SUCCESS:
      return {
        ...state,
        popularTVs: {
          ...state.popularTVs,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_POPULAR_TVS_ERROR:
      return {
        ...state,
        popularTVs: {
          ...state.popularTVs,
          isLoading: false,
          error: action.payload,
        },
      };

    default:
      return { ...state };
  }
};

export default moviedb;

export const getPopularMovies =
  ({ page }: { page: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { moviedb } = getState();
      dispatch({ type: GET_POPULAR_MOVIES_LOADING });
      const result = await api.getPopularMovies({ page });
      const res = await result.json();

      dispatch({
        type: GET_POPULAR_MOVIES_SUCCESS,
        payload: [...(moviedb.popularMovies.data || []), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_POPULAR_MOVIES_ERROR,
        payload: err,
      });
    }
  };

export const getPopularTVs =
  ({ page }: { page: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { moviedb } = getState();
      dispatch({ type: GET_POPULAR_TVS_LOADING });
      const result = await api.getPopularTVs({ page });
      const res = await result.json();

      dispatch({
        type: GET_POPULAR_TVS_SUCCESS,
        payload: [...(moviedb.popularTVs.data || []), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_POPULAR_TVS_ERROR,
        payload: err,
      });
    }
  };
