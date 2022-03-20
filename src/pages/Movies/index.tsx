import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import Button from "../../components/Button";
import { getPopularMovies } from "../../redux/moviedb";
import { Reducers } from "../../redux/types";

const Movies = () => {
  const [page, setPage] = useState(1);
  const { moviedb } = useSelector((state: Reducers) => state);
  const dispatch = useDispatch();

  const onLoadMore = () => {
    setPage((prev) => prev + 1);
  };

  useEffect(() => {
    dispatch(getPopularMovies({ page }));
  }, [dispatch, page]);

  return (
    <>
      <section className="section">
        <div className="container">
          <h1 className="title">
            <Link to="/movies">Movies</Link>
          </h1>
          <div className="columns is-multiline is-desktop">
            {moviedb.popularMovies.data &&
              moviedb.popularMovies.data.map(
                (
                  item: {
                    poster_path: string;
                    title: string;
                    release_date: string;
                    vote_average: number;
                  },
                  index: number
                ) => (
                  <div
                    className="column is-one-fifth"
                    key={`marvel-character-${index + 1}`}
                  >
                    <div className="box is-justify-content-center is-flex-direction-column is-flex">
                      {item.poster_path && (
                        <img
                          src={`https://image.tmdb.org/t/p/w500/${item.poster_path}`}
                        />
                      )}

                      <p className="has-text-centered">
                        {item.title} ({item.release_date})
                      </p>
                      <p
                        v-if="data.vote_average != 0"
                        className="has-text-centered"
                      >
                        {item.vote_average}
                      </p>
                    </div>
                  </div>
                )
              )}
          </div>
          {moviedb.popularMovies.isLoading && <p>Loading ...</p>}
          <Button onClick={onLoadMore}>See More</Button>
        </div>
      </section>
    </>
  );
};

export default Movies;
