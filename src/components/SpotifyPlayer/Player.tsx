import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import SpotifyPlayer from "react-spotify-web-playback";
import { Reducers } from "../../redux/types";

interface PlayerProps {
  remove: () => void;
  uri: string | null;
}

const Player: React.FC<PlayerProps> = ({ uri }) => {
  const { spotify } = useSelector((state: Reducers) => state);
  const [play, setPlay] = useState(false);

  useEffect(() => setPlay(true), [uri]);

  return (
    <div style={{ position: "fixed", bottom: 0, width: "100vw" }}>
      <SpotifyPlayer
        token={spotify.auth.data.access_token}
        callback={(state) => (!state.isPlaying ? setPlay(false) : null)}
        play={play}
        uris={uri ? [uri] : []}
      />
    </div>
  );
};

export default Player;
