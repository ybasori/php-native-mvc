import React, { ReactChild, useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { postSpotifyRefresh } from "../../../redux/spotify";
import { Reducers } from "../../../redux/types";
import Player from "../Player";
import SpotifyPlayerContext from "./SpotifyPlayerContext";

interface SpotifyPlayerProviderProps {
  children: ReactChild;
}

const SpotifyPlayerProvider: React.FC<SpotifyPlayerProviderProps> = ({
  children,
  ...props
}) => {
  const dispatch = useDispatch();
  const { spotify } = useSelector((state: Reducers) => state);

  const dt = new Date();
  const [dtime, setDtime] = useState(dt.getTime());
  const [isRefreshing, setIsRefreshing] = useState(false);
  const [uriString, setUriString] = useState<string | null>(null);

  const setMusic = (data: string) => {
    setUriString(data);
  };

  const removeMusic = () => {
    setUriString(null);
  };

  useEffect(() => {
    const st = setTimeout(() => {
      setDtime((prev) => prev + 1000);
    }, 1000);

    return () => clearTimeout(st);
  }, [dtime]);

  useEffect(() => {
    if (
      spotify.auth.expiresAt !== 0 &&
      spotify.auth.expiresAt - dtime <= 15 * 60000 &&
      !isRefreshing
    ) {
      setIsRefreshing(true);
    }
  }, [isRefreshing, dtime, spotify.auth.expiresAt]);

  useEffect(() => {
    if (isRefreshing && !spotify.auth.isLoading) {
      setIsRefreshing(false);
      dispatch(postSpotifyRefresh());
    }
  }, [dispatch, isRefreshing, spotify.auth.isLoading]);

  return (
    <SpotifyPlayerContext.Provider value={{ setMusic }} {...props}>
      {children}
      {spotify.auth.data && <Player remove={removeMusic} uri={uriString} />}
    </SpotifyPlayerContext.Provider>
  );
};

export { SpotifyPlayerContext, SpotifyPlayerProvider };
