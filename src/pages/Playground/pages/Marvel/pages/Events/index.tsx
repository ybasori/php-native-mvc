import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import Button from "../../../../../../components/Button";
import {
  getMarvelEvents,
  getMarvelReset,
} from "../../../../../../redux/marvel";
import { Reducers } from "../../../../../../redux/types";

const Events = () => {
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
      dispatch(getMarvelEvents({ ...page }));
    } else {
      dispatch(getMarvelReset("events"));
    }
  }, [dispatch, isError, page]);

  useEffect(() => {
    if (marvel.events.error) {
      setIsError(true);
    }
  }, [dispatch, marvel.events.error]);

  return (
    <>
      <h2 className="subtitle">Events</h2>
      <div className="columns is-multiline is-desktop">
        {marvel.events.data &&
          marvel.events.data.map(
            (
              item: {
                thumbnail: { path: string; extension: string };
                title: string;
              },
              index: number
            ) => (
              <div
                className="column is-one-fifth"
                key={`marvel-events-${index + 1}`}
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
      {marvel.events.isLoading && <p>Loading ...</p>}
      {!marvel.events.isLoading && (
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

export default Events;
