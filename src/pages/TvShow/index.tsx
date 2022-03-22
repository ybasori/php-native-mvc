import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import Button from "../../components/Button";
import { getPopularTVs, getPopularTVsReset } from "../../redux/moviedb";
import { Reducers } from "../../redux/types";

const TvShow = () => {
  const [page, setPage] = useState(1);
  const [isError, setIsError] = useState(false);
  const { moviedb } = useSelector((state: Reducers) => state);
  const dispatch = useDispatch();

  const onLoadMore = () => {
    setPage((prev) => prev + 1);
  };

  const onReload = () => {
    setIsError(false);
  };

  useEffect(() => {
    if (!isError) {
      dispatch(getPopularTVs({ page }));
    } else {
      dispatch(getPopularTVsReset());
    }
  }, [dispatch, isError, page]);

  useEffect(() => {
    if (moviedb.popularTVs.error) {
      setIsError(true);
    }
  }, [dispatch, moviedb.popularTVs.error]);

  return (
    <>
      <section className="section">
        <div className="container">
          <h1 className="title">
            <Link to="/tv-shows">TV Shows</Link>
          </h1>
          <div className="columns is-multiline is-desktop">
            {moviedb.popularTVs.data &&
              moviedb.popularTVs.data.map(
                (
                  item: {
                    poster_path: string;
                    name: string;
                    first_air_date: string;
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
                        {item.name} ({item.first_air_date})
                      </p>
                      <p className="has-text-centered">{item.vote_average}</p>
                    </div>
                  </div>
                )
              )}
          </div>
          {moviedb.popularTVs.isLoading && <p>Loading ...</p>}
          {!moviedb.popularTVs.isLoading && (
            <>
              {isError ? (
                <Button onClick={onReload}>Reload</Button>
              ) : (
                <Button onClick={onLoadMore}>See More</Button>
              )}
            </>
          )}
        </div>
      </section>
    </>
  );
};

export default TvShow;
