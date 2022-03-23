import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import Button from "../../../../../../components/Button";
import {
  getMarvelCharacter,
  getMarvelReset,
} from "../../../../../../redux/marvel";
import { Reducers } from "../../../../../../redux/types";

const Character = () => {
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
      dispatch(getMarvelCharacter({ ...page }));
    } else {
      dispatch(getMarvelReset("character"));
    }
  }, [dispatch, isError, page]);

  useEffect(() => {
    if (marvel.character.error) {
      setIsError(true);
    }
  }, [dispatch, marvel.character.error]);

  return (
    <>
      <h2 className="subtitle">Characters</h2>
      <div className="columns is-multiline is-desktop">
        {marvel.character.data &&
          marvel.character.data.map(
            (
              item: {
                thumbnail: { path: string; extension: string };
                name: string;
              },
              index: number
            ) => (
              <div
                className="column is-one-fifth"
                key={`marvel-character-${index + 1}`}
              >
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  {item.thumbnail && (
                    <img
                      src={`${item.thumbnail.path}/portrait_xlarge.${item.thumbnail.extension}`}
                    />
                  )}
                  <div className="is-capitalized has-text-centered">
                    {item.name}
                  </div>
                </div>
              </div>
            )
          )}
      </div>
      {marvel.character.isLoading && <p>Loading ...</p>}
      {!marvel.character.isLoading && (
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

export default Character;
