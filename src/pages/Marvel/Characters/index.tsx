import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import Button from "../../../components/Button";
import { getMarvelCharacter } from "../../../redux/marvel";
import { Reducers } from "../../../redux/types";

const Character = () => {
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
    dispatch(getMarvelCharacter({ ...page }));
  }, [dispatch, page]);

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
                      v-if="data.thumbnail"
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
      <Button onClick={onLoadMore}>See More</Button>
    </>
  );
};

export default Character;
