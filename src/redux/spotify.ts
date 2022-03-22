import api from "../configs/api";
import { Action, Dispatch, Reducers, SpotifyState } from "./types";

const POST_SPOTIFY_LOGIN_LOADING = "POST_SPOTIFY_LOGIN_LOADING";
const POST_SPOTIFY_LOGIN_SUCCESS = "POST_SPOTIFY_LOGIN_SUCCESS";
const POST_SPOTIFY_LOGIN_ERROR = "POST_SPOTIFY_LOGIN_ERROR";
const POST_SPOTIFY_LOGIN_RESET = "POST_SPOTIFY_LOGIN_RESET";

const GET_SPOTIFY_SEARCH_LOADING = "GET_SPOTIFY_SEARCH_LOADING";
const GET_SPOTIFY_SEARCH_SUCCESS = "GET_SPOTIFY_SEARCH_SUCCESS";
const GET_SPOTIFY_SEARCH_ERROR = "GET_SPOTIFY_SEARCH_ERROR";

const initState: SpotifyState = {
  auth: {
    isLoading: false,
    data: null,
    error: null,
    expiresAt: 0,
  },
  search: {
    time: 0,
    isLoading: false,
    data: null,
    error: null,
  },
};

const spotify = (state = initState, action: Action) => {
  switch (action.type) {
    case POST_SPOTIFY_LOGIN_LOADING:
      return {
        ...state,
        auth: {
          ...state.auth,
          isLoading: true,
          error: null,
          expiresAt: 0,
        },
      };
    case POST_SPOTIFY_LOGIN_SUCCESS:
      return {
        ...state,
        auth: {
          ...state.auth,
          isLoading: false,
          data: action.payload.data,
          error: null,
          expiresAt: action.payload.expiresAt,
        },
      };
    case POST_SPOTIFY_LOGIN_ERROR:
      return {
        ...state,
        auth: {
          ...state.auth,
          isLoading: false,
          error: action.payload,
          data: null,
          expiresAt: 0,
        },
      };
    case POST_SPOTIFY_LOGIN_RESET:
      return {
        ...state,
        auth: {
          ...state.auth,
          isLoading: false,
          data: null,
          error: null,
          expiresAt: 0,
        },
      };

    case GET_SPOTIFY_SEARCH_LOADING:
      return {
        ...state,
        search: {
          ...state.search,
          isLoading: true,
          error: null,
        },
      };

    case GET_SPOTIFY_SEARCH_SUCCESS:
      return {
        ...state,
        search: {
          ...state.search,
          isLoading: false,
          data: action.payload.data,
          error: null,
        },
      };

    case GET_SPOTIFY_SEARCH_ERROR:
      return {
        ...state,
        search: {
          ...state.search,
          isLoading: false,
          data: null,
          error: action.payload,
        },
      };

    default:
      return { ...state };
  }
};

export default spotify;

export const postSpotifyLogin =
  ({ code }: { code: string }) =>
  async (dispatch: Dispatch) => {
    try {
      dispatch({ type: POST_SPOTIFY_LOGIN_LOADING });
      const result = await api.postSpotifyLogin({ code });
      const res = await result.json();
      if (result.status < 400) {
        const expiresAt = new Date().getTime() + res.data.expires_in * 1000;
        dispatch({
          type: POST_SPOTIFY_LOGIN_SUCCESS,
          payload: { data: res.data, expiresAt },
        });
      } else {
        dispatch({
          type: POST_SPOTIFY_LOGIN_ERROR,
          payload: res.error,
        });
      }
    } catch (err) {
      dispatch({
        type: POST_SPOTIFY_LOGIN_ERROR,
        payload: err,
      });
    }
  };

export const postSpotifyRefresh =
  () => async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { spotify } = getState();
      dispatch({ type: POST_SPOTIFY_LOGIN_LOADING });
      const result = await api.postSpotifyRefresh({
        refresh_token: spotify.auth.data.refresh_token,
      });
      const res = await result.json();
      if (result.status < 400) {
        const expiresAt = new Date().getTime() + res.data.expires_in * 1000;
        dispatch({
          type: POST_SPOTIFY_LOGIN_SUCCESS,
          payload: {
            data: { ...spotify.auth.data, ...res.data },
            expiresAt,
          },
        });
      } else {
        dispatch({
          type: POST_SPOTIFY_LOGIN_ERROR,
          payload: res.error,
        });
      }
    } catch (err) {
      dispatch({
        type: POST_SPOTIFY_LOGIN_ERROR,
        payload: err,
      });
    }
  };

export const resetPostSpotifyLogin = () => (dispatch: Dispatch) => {
  dispatch({ type: POST_SPOTIFY_LOGIN_RESET });
};

export const getSpotifySearch =
  ({ q }: { q: string }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { spotify } = getState();
      const time = new Date().getTime();
      dispatch({ type: GET_SPOTIFY_SEARCH_LOADING });
      const result = await api.getSpotifySearch({
        q,
        type: "track",
        headers: {
          Authorization: `${spotify.auth.data.token_type} ${spotify.auth.data.access_token}`,
        },
      });
      const res = await result.json();
      if (result.status < 400) {
        if (time > spotify.search.time) {
          dispatch({
            type: GET_SPOTIFY_SEARCH_SUCCESS,
            payload: { data: res.data, time },
          });
        }
      } else {
        dispatch({
          type: GET_SPOTIFY_SEARCH_ERROR,
          payload: res.error,
        });
      }
    } catch (err) {
      dispatch({
        type: GET_SPOTIFY_SEARCH_ERROR,
        payload: err,
      });
    }
  };
