const api = {
  getAllPokemon: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/pokemon?limit=${limit}&offset=${offset}`);
  },
  getPokemon: ({ id }: { id: string }) => {
    return fetch(`/api/v1/pokemon/${id}`);
  },
  getPopularMovies: ({ page }: { page: number }) => {
    return fetch(`/api/v1/moviedb/popular-movies?page=${page}`);
  },
  getPopularTVs: ({ page }: { page: number }) => {
    return fetch(`/api/v1/moviedb/popular-tvs?page=${page}`);
  },
  getMarvelCharacter: ({
    limit,
    offset,
  }: {
    limit: number;
    offset: number;
  }) => {
    return fetch(`/api/v1/marvel/characters?limit=${limit}&offset=${offset}`);
  },
  getMarvelComics: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/comics?limit=${limit}&offset=${offset}`);
  },
  getMarvelCreators: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/creators?limit=${limit}&offset=${offset}`);
  },
  getMarvelEvents: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/events?limit=${limit}&offset=${offset}`);
  },
  getMarvelSeries: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/series?limit=${limit}&offset=${offset}`);
  },
  getMarvelStories: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/stories?limit=${limit}&offset=${offset}`);
  },
  postSpotifyLogin: ({ code }: { code: string }) => {
    const form = new FormData();
    form.append("code", code);
    return fetch(`/api/v1/spotify/login`, {
      method: "POST",
      body: form,
    });
  },
  postSpotifyRefresh: ({ refresh_token }: { refresh_token: string }) => {
    const form = new FormData();
    form.append("refresh_token", refresh_token);
    return fetch(`/api/v1/spotify/refresh`, {
      method: "POST",
      body: form,
    });
  },
  getSpotifySearch: ({
    q,
    type,
    headers,
  }: {
    q: string;
    type: string;
    headers: { Authorization: string };
  }) => {
    return fetch(`/api/v1/spotify/search?q=${q}&type=${type}`, {
      headers,
    });
  },
};

export default api;
