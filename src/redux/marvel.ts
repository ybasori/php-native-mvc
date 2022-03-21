import api from "../configs/api";
import { Action, Dispatch, MarvelState, Reducers } from "./types";

const GET_MARVEL_CHARACTERS_LOADING = "GET_MARVEL_CHARACTERS_LOADING";
const GET_MARVEL_CHARACTERS_SUCCESS = "GET_MARVEL_CHARACTERS_SUCCESS";
const GET_MARVEL_CHARACTERS_ERROR = "GET_MARVEL_CHARACTERS_ERROR";

const GET_MARVEL_COMICS_LOADING = "GET_MARVEL_COMICS_LOADING";
const GET_MARVEL_COMICS_SUCCESS = "GET_MARVEL_COMICS_SUCCESS";
const GET_MARVEL_COMICS_ERROR = "GET_MARVEL_COMICS_ERROR";

const GET_MARVEL_CREATORS_LOADING = "GET_MARVEL_CREATORS_LOADING";
const GET_MARVEL_CREATORS_SUCCESS = "GET_MARVEL_CREATORS_SUCCESS";
const GET_MARVEL_CREATORS_ERROR = "GET_MARVEL_CREATORS_ERROR";

const GET_MARVEL_EVENTS_LOADING = "GET_MARVEL_EVENTS_LOADING";
const GET_MARVEL_EVENTS_SUCCESS = "GET_MARVEL_EVENTS_SUCCESS";
const GET_MARVEL_EVENTS_ERROR = "GET_MARVEL_EVENTS_ERROR";

const GET_MARVEL_SERIES_LOADING = "GET_MARVEL_SERIES_LOADING";
const GET_MARVEL_SERIES_SUCCESS = "GET_MARVEL_SERIES_SUCCESS";
const GET_MARVEL_SERIES_ERROR = "GET_MARVEL_SERIES_ERROR";

const GET_MARVEL_STORIES_LOADING = "GET_MARVEL_STORIES_LOADING";
const GET_MARVEL_STORIES_SUCCESS = "GET_MARVEL_STORIES_SUCCESS";
const GET_MARVEL_STORIES_ERROR = "GET_MARVEL_STORIES_ERROR";

const initState: MarvelState = {
  character: {
    isLoading: false,
    data: null,
    error: null,
  },
  comics: {
    isLoading: false,
    data: null,
    error: null,
  },
  creators: {
    isLoading: false,
    data: null,
    error: null,
  },
  events: {
    isLoading: false,
    data: null,
    error: null,
  },
  series: {
    isLoading: false,
    data: null,
    error: null,
  },
  stories: {
    isLoading: false,
    data: null,
    error: null,
  },
};

const marvel = (state = initState, action: Action) => {
  switch (action.type) {
    case GET_MARVEL_CHARACTERS_LOADING:
      return {
        ...state,
        character: {
          ...state.character,
          isLoading: true,
          error: null,
        },
      };
    case GET_MARVEL_CHARACTERS_SUCCESS:
      return {
        ...state,
        character: {
          ...state.character,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_MARVEL_CHARACTERS_ERROR:
      return {
        ...state,
        character: {
          ...state.character,
          isLoading: false,
          error: action.payload,
        },
      };
    case GET_MARVEL_COMICS_LOADING:
      return {
        ...state,
        comics: {
          ...state.comics,
          isLoading: true,
          error: null,
        },
      };
    case GET_MARVEL_COMICS_SUCCESS:
      return {
        ...state,
        comics: {
          ...state.comics,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_MARVEL_COMICS_ERROR:
      return {
        ...state,
        comics: {
          ...state.comics,
          isLoading: false,
          error: action.payload,
        },
      };
    case GET_MARVEL_CREATORS_LOADING:
      return {
        ...state,
        creators: {
          ...state.creators,
          isLoading: true,
          error: null,
        },
      };
    case GET_MARVEL_CREATORS_SUCCESS:
      return {
        ...state,
        creators: {
          ...state.creators,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_MARVEL_CREATORS_ERROR:
      return {
        ...state,
        creators: {
          ...state.creators,
          isLoading: false,
          error: action.payload,
        },
      };
    case GET_MARVEL_EVENTS_LOADING:
      return {
        ...state,
        events: {
          ...state.events,
          isLoading: true,
          error: null,
        },
      };
    case GET_MARVEL_EVENTS_SUCCESS:
      return {
        ...state,
        events: {
          ...state.events,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_MARVEL_EVENTS_ERROR:
      return {
        ...state,
        events: {
          ...state.events,
          isLoading: false,
          error: action.payload,
        },
      };
    case GET_MARVEL_SERIES_LOADING:
      return {
        ...state,
        series: {
          ...state.series,
          isLoading: true,
          error: null,
        },
      };
    case GET_MARVEL_SERIES_SUCCESS:
      return {
        ...state,
        series: {
          ...state.series,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_MARVEL_SERIES_ERROR:
      return {
        ...state,
        series: {
          ...state.series,
          isLoading: false,
          error: action.payload,
        },
      };
    case GET_MARVEL_STORIES_LOADING:
      return {
        ...state,
        stories: {
          ...state.stories,
          isLoading: true,
          error: null,
        },
      };
    case GET_MARVEL_STORIES_SUCCESS:
      return {
        ...state,
        stories: {
          ...state.stories,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_MARVEL_STORIES_ERROR:
      return {
        ...state,
        stories: {
          ...state.stories,
          isLoading: false,
          error: action.payload,
        },
      };
    default:
      return { ...state };
  }
};

export default marvel;

export const getMarvelCharacter =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { marvel } = getState();
      dispatch({ type: GET_MARVEL_CHARACTERS_LOADING });
      const result = await api.getMarvelCharacter({ limit, offset });
      const res = await result.json();

      const dt = marvel.comics.data || [];

      dispatch({
        type: GET_MARVEL_CHARACTERS_SUCCESS,
        payload: [...(offset == 0 ? [] : dt), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_MARVEL_CHARACTERS_ERROR,
        payload: err,
      });
    }
  };

export const getMarvelComics =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { marvel } = getState();
      dispatch({ type: GET_MARVEL_COMICS_LOADING });
      const result = await api.getMarvelComics({ limit, offset });
      const res = await result.json();

      const dt = marvel.comics.data || [];

      dispatch({
        type: GET_MARVEL_COMICS_SUCCESS,
        payload: [...(offset == 0 ? [] : dt), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_MARVEL_COMICS_ERROR,
        payload: err,
      });
    }
  };

export const getMarvelCreators =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { marvel } = getState();
      dispatch({ type: GET_MARVEL_CREATORS_LOADING });
      const result = await api.getMarvelCreators({ limit, offset });
      const res = await result.json();

      const dt = marvel.creators.data || [];

      dispatch({
        type: GET_MARVEL_CREATORS_SUCCESS,
        payload: [...(offset == 0 ? [] : dt), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_MARVEL_CREATORS_ERROR,
        payload: err,
      });
    }
  };

export const getMarvelEvents =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { marvel } = getState();
      dispatch({ type: GET_MARVEL_EVENTS_LOADING });
      const result = await api.getMarvelEvents({ limit, offset });
      const res = await result.json();

      const dt = marvel.events.data || [];

      dispatch({
        type: GET_MARVEL_EVENTS_SUCCESS,
        payload: [...(offset == 0 ? [] : dt), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_MARVEL_EVENTS_ERROR,
        payload: err,
      });
    }
  };

export const getMarvelSeries =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { marvel } = getState();
      dispatch({ type: GET_MARVEL_SERIES_LOADING });
      const result = await api.getMarvelSeries({ limit, offset });
      const res = await result.json();

      const dt = marvel.series.data || [];

      dispatch({
        type: GET_MARVEL_SERIES_SUCCESS,
        payload: [...(offset == 0 ? [] : dt), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_MARVEL_SERIES_ERROR,
        payload: err,
      });
    }
  };

export const getMarvelStories =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { marvel } = getState();
      dispatch({ type: GET_MARVEL_STORIES_LOADING });
      const result = await api.getMarvelStories({ limit, offset });
      const res = await result.json();

      const dt = marvel.stories.data || [];

      dispatch({
        type: GET_MARVEL_STORIES_SUCCESS,
        payload: [...(offset == 0 ? [] : dt), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_MARVEL_STORIES_ERROR,
        payload: err,
      });
    }
  };
