import { createContext } from "react";
import { SpotifyPlayerContextProps } from "../types";

const spotifyPlayerContext: SpotifyPlayerContextProps = {
  setMusic: () => null,
};

const SpotifyPlayerContext = createContext(spotifyPlayerContext);

export default SpotifyPlayerContext;
