import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import Button from "../../components/Button";
import { getAllPokemon, getPokemonAllReset } from "../../redux/pokemon";
import { Reducers } from "../../redux/types";
import PokemonItem from "./PokemonItem";

const Pokemon = () => {
  const [page, setPage] = useState({
    limit: 20,
    offset: 0,
  });
  const [isError, setIsError] = useState(false);
  const { pokemon } = useSelector((state: Reducers) => state);
  const dispatch = useDispatch();

  const onLoadMore = () => {
    setPage((prev) => ({ ...prev, offset: prev.offset + prev.limit }));
  };

  const onReload = () => {
    setIsError(false);
  };

  useEffect(() => {
    if (!isError) {
      dispatch(getAllPokemon({ ...page }));
    } else {
      dispatch(getPokemonAllReset());
    }
  }, [dispatch, isError, page]);

  useEffect(() => {
    if (pokemon.allPokemon.error) {
      setIsError(true);
    }
  }, [dispatch, pokemon.allPokemon.error]);
  return (
    <>
      <section className="section">
        <div className="container">
          <h1 className="title">
            <Link to="/pokemon">Pokemon</Link>
          </h1>
          <div className="columns is-multiline is-desktop">
            {pokemon.allPokemon.data &&
              pokemon.allPokemon.data.map(
                (
                  item: {
                    name: string;
                  },
                  index: number
                ) => (
                  <PokemonItem
                    data={item}
                    index={index}
                    key={`marvel-character-${index + 1}`}
                  />
                )
              )}
          </div>
          {pokemon.allPokemon.isLoading && <p>Loading ...</p>}
          {!pokemon.allPokemon.isLoading && (
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

export default Pokemon;
