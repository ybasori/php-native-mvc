import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import Button from "../../../../../../components/Button";
import {
  getMarvelReset,
  getMarvelSeries,
} from "../../../../../../redux/marvel";
import { Reducers } from "../../../../../../redux/types";

const Series = () => {
  const [page, setPage] = useState({
    limit: 20,
    offset: 0,
  });
  const [isError, setIsError] = useState(false);
  const { marvel } = useSelector((state: Reducers) => state);
  const dispatch = useDispatch();

  const onLoadMore = () => {
    setPage((prev) => ({ ...prev, offset: prev.offset + prev.limit }));
  };

  const onReload = () => {
    setIsError(false);
  };

  useEffect(() => {
    if (!isError) {
      dispatch(getMarvelSeries({ ...page }));
    } else {
      dispatch(getMarvelReset("series"));
    }
  }, [dispatch, isError, page]);

  useEffect(() => {
    if (marvel.series.error) {
      setIsError(true);
    }
  }, [dispatch, marvel.series.error]);

  return (
    <>
      <h2 className="subtitle">Series</h2>
      <div className="columns is-multiline is-desktop">
        {marvel.series.data &&
          marvel.series.data.map(
            (
              item: {
                thumbnail: { path: string; extension: string };
                title: string;
              },
              index: number
            ) => (
              <div
                className="column is-one-fifth"
                key={`marvel-series-${index + 1}`}
              >
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  {item.thumbnail && (
                    <img
                      src={`${item.thumbnail.path}/portrait_xlarge.${item.thumbnail.extension}`}
                    />
                  )}
                  <div className="is-capitalized has-text-centered">
                    {item.title}
                  </div>
                </div>
              </div>
            )
          )}
      </div>
      {marvel.series.isLoading && <p>Loading ...</p>}
      {!marvel.series.isLoading && (
        <>
          {isError ? (
            <Button onClick={onReload}>Reload</Button>
          ) : (
            <Button onClick={onLoadMore}>See More</Button>
          )}
        </>
      )}
    </>
  );
};

export default Series;
