const api = {
  getPopularMovies: ({ page }: { page: number }) => {
    return fetch(`/api/v1/moviedb/popular-movies?page=${page}`).then((res) =>
      res.json()
    );
  },
  getPopularTVs: ({ page }: { page: number }) => {
    return fetch(`/api/v1/moviedb/popular-tvs?page=${page}`).then((res) =>
      res.json()
    );
  },
  getMarvelCharacter: ({
    limit,
    offset,
  }: {
    limit: number;
    offset: number;
  }) => {
    return fetch(
      `/api/v1/marvel/characters?limit=${limit}&offset=${offset}`
    ).then((res) => res.json());
  },
  getMarvelComics: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/comics?limit=${limit}&offset=${offset}`).then(
      (res) => res.json()
    );
  },
  getMarvelCreators: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(
      `/api/v1/marvel/creators?limit=${limit}&offset=${offset}`
    ).then((res) => res.json());
  },
  getMarvelEvents: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/events?limit=${limit}&offset=${offset}`).then(
      (res) => res.json()
    );
  },
  getMarvelSeries: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/series?limit=${limit}&offset=${offset}`).then(
      (res) => res.json()
    );
  },
  getMarvelStories: ({ limit, offset }: { limit: number; offset: number }) => {
    return fetch(`/api/v1/marvel/stories?limit=${limit}&offset=${offset}`).then(
      (res) => res.json()
    );
  },
};

export default api;
