import { useContext } from "react";
import {
  SpotifyPlayerProvider,
  SpotifyPlayerContext,
} from "./context/SpotifyPlayerProvider";

const useSpotify = () => {
  const { setMusic } = useContext(SpotifyPlayerContext);

  return { setMusic };
};

export { SpotifyPlayerProvider, useSpotify };
