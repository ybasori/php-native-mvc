import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import Button from "../../../components/Button";
import { getMarvelCreators } from "../../../redux/marvel";
import { Reducers } from "../../../redux/types";

const Creators = () => {
  const [page, setPage] = useState({
    limit: 20,
    offset: 0,
  });
  const { marvel } = useSelector((state: Reducers) => state);
  const dispatch = useDispatch();

  const onLoadMore = () => {
    setPage((prev) => ({ ...prev, offset: prev.offset + prev.limit }));
  };

  useEffect(() => {
    dispatch(getMarvelCreators({ ...page }));
  }, [dispatch, page]);

  return (
    <>
      <h2 className="subtitle">Creators</h2>
      <div className="columns is-multiline is-desktop">
        {marvel.creators.data &&
          marvel.creators.data.map(
            (
              item: {
                thumbnail: { path: string; extension: string };
                fullName: string;
              },
              index: number
            ) => (
              <div
                className="column is-one-fifth"
                key={`marvel-creators-${index + 1}`}
              >
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  {item.thumbnail && (
                    <img
                      v-if="data.thumbnail"
                      src={`${item.thumbnail.path}/portrait_xlarge.${item.thumbnail.extension}`}
                    />
                  )}
                  <div className="is-capitalized has-text-centered">
                    {item.fullName}
                  </div>
                </div>
              </div>
            )
          )}
      </div>
      {marvel.creators.isLoading && <p>Loading ...</p>}
      <Button onClick={onLoadMore}>See More</Button>
    </>
  );
};

export default Creators;
