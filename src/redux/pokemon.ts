import api from "../configs/api";
import { Action, Dispatch, PokemonState, Reducers } from "./types";

const GET_ALL_POKEMON_LOADING = "GET_ALL_POKEMON_LOADING";
const GET_ALL_POKEMON_SUCCESS = "GET_ALL_POKEMON_SUCCESS";
const GET_ALL_POKEMON_ERROR = "GET_ALL_POKEMON_ERROR";

const initState: PokemonState = {
  allPokemon: {
    isLoading: false,
    data: null,
    error: null,
    page: {
      limit: 20,
      offset: 0,
    },
  },
};

const pokemon = (state = initState, action: Action) => {
  switch (action.type) {
    case GET_ALL_POKEMON_LOADING:
      return {
        ...state,
        allPokemon: {
          ...state.allPokemon,
          isLoading: true,
          error: null,
        },
      };
    case GET_ALL_POKEMON_SUCCESS:
      return {
        ...state,
        allPokemon: {
          ...state.allPokemon,
          isLoading: false,
          data: action.payload,
          error: null,
        },
      };
    case GET_ALL_POKEMON_ERROR:
      return {
        ...state,
        allPokemon: {
          ...state.allPokemon,
          isLoading: false,
          error: action.payload,
        },
      };

    default:
      return { ...state };
  }
};

export default pokemon;

export const getAllPokemon =
  ({ limit, offset }: { limit: number; offset: number }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { pokemon } = getState();
      dispatch({ type: GET_ALL_POKEMON_LOADING });
      const result = await api.getAllPokemon({ limit, offset });
      const res = await result.json();

      const allPokemon = pokemon.allPokemon.data || [];

      dispatch({
        type: GET_ALL_POKEMON_SUCCESS,
        payload: [...(offset == 0 ? [] : allPokemon), ...res.data.results],
      });
    } catch (err) {
      dispatch({
        type: GET_ALL_POKEMON_ERROR,
        payload: err,
      });
    }
  };

export const getPokemon =
  ({ id }: { id: string }) =>
  async (dispatch: Dispatch, getState: () => Reducers) => {
    try {
      const { pokemon } = getState();
      dispatch({ type: GET_ALL_POKEMON_LOADING });
      const result = await api.getPokemon({ id });
      const res = await result.json();

      // eslint-disable-next-line prefer-const
      let allPokemon = pokemon.allPokemon.data || [];
      const index = allPokemon.findIndex(
        (item: { name: string }) => item.name === id
      );

      allPokemon[index] = res.data;

      dispatch({
        type: GET_ALL_POKEMON_SUCCESS,
        payload: [...allPokemon],
      });
    } catch (err) {
      dispatch({
        type: GET_ALL_POKEMON_ERROR,
        payload: err,
      });
    }
  };