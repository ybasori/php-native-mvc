import React, { useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import { getPokemon } from "../../../../../../redux/pokemon";

interface Props {
  data: {
    name: string;
    id?: number;
    sprites?: any;
  };
  index: number;
}

const PokemonItem: React.FC<Props> = ({ data }) => {
  const dispatch = useDispatch();
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    if (!data.id) {
      setIsLoading(true);
      dispatch(getPokemon({ id: data.name }));
    } else {
      setIsLoading(false);
    }
  }, [data.id, data.name, dispatch]);

  return (
    <>
      <div className="column is-one-fifth">
        <div className="box is-justify-content-center is-flex-direction-column is-flex">
          {data.sprites && (
            <img
              src={data.sprites.other["official-artwork"].front_default}
              style={{ height: "auto" }}
            />
          )}
          {isLoading ? (
            <div className="has-text-centered">Loading ...</div>
          ) : (
            <div className="is-capitalized has-text-centered">
              {data.id !== undefined ? `#${data.id}` : ""} {data.name}
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export default PokemonItem;
