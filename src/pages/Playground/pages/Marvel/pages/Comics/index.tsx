import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import Button from "../../../../../../components/Button";
import {
  getMarvelComics,
  getMarvelReset,
} from "../../../../../../redux/marvel";
import { Reducers } from "../../../../../../redux/types";

const Comics = () => {
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
      dispatch(getMarvelComics({ ...page }));
    } else {
      dispatch(getMarvelReset("comics"));
    }
  }, [dispatch, isError, page]);

  useEffect(() => {
    if (marvel.comics.error) {
      setIsError(true);
    }
  }, [dispatch, marvel.comics.error]);

  return (
    <>
      <h2 className="subtitle">Comics</h2>
      <div className="columns is-multiline is-desktop">
        {marvel.comics.data &&
          marvel.comics.data.map(
            (
              item: {
                thumbnail: { path: string; extension: string };
                title: string;
              },
              index: number
            ) => (
              <div
                className="column is-one-fifth"
                key={`marvel-comics-${index + 1}`}
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
      {marvel.comics.isLoading && <p>Loading ...</p>}
      {!marvel.comics.isLoading && (
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

export default Comics;
