export interface Action {
  payload?: any;
  type: string;
}

export type Logger = (store: {
  getState: () => void;
}) => (next: (action: Action) => void) => (action: Action) => void;

export type Dispatch = (data: Action) => null;

export interface MarvelState {
  character: {
    data: any;
    isLoading: boolean;
    error: any;
  };
  comics: {
    data: any;
    isLoading: boolean;
    error: any;
  };
  creators: {
    data: any;
    isLoading: boolean;
    error: any;
  };
  events: {
    data: any;
    isLoading: boolean;
    error: any;
  };
  series: {
    data: any;
    isLoading: boolean;
    error: any;
  };
  stories: {
    data: any;
    isLoading: boolean;
    error: any;
  };
}
export interface MovieDBState {
  popularMovies: {
    data: any;
    isLoading: boolean;
    error: any;
  };
  popularTVs: {
    data: any;
    isLoading: boolean;
    error: any;
  };
}

export interface PokemonState {
  allPokemon: {
    data: any;
    isLoading: boolean;
    error: any;
    page: {
      limit: number;
      offset: number;
    };
  };
}

export interface SpotifyState {
  auth: {
    isLoading: boolean;
    data: any;
    error: any;
    expiresAt: number;
  };
  search: {
    time: number;
    isLoading: boolean;
    data: any;
    error: any;
  };
}

export interface Reducers {
  marvel: MarvelState;
  moviedb: MovieDBState;
  pokemon: PokemonState;
  spotify: SpotifyState;
}
