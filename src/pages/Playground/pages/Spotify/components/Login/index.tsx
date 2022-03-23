import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useSearchParams } from "react-router-dom";
import { postSpotifyLogin } from "../../../../../../redux/spotify";
import { Reducers } from "../../../../../../redux/types";

const AUTH_URL = `https://accounts.spotify.com/authorize?client_id=${process.env.SPOTIFY_CLIENT_ID}&response_type=code&redirect_uri=${process.env.SPOTIFY_REDIRECT_URI}&scope=streaming%20user-read-email%20user-read-private%20user-library-read%20user-library-modify%20user-read-playback-state%20user-modify-playback-state`;

const Login = () => {
  const dispatch = useDispatch();
  const { spotify } = useSelector((state: Reducers) => state);
  const [searchParams, setSearchParams] = useSearchParams();
  const code = searchParams.get("code");

  useEffect(() => {
    if (code) {
      if (
        !spotify.auth.isLoading &&
        !spotify.auth.data &&
        !spotify.auth.error
      ) {
        dispatch(postSpotifyLogin({ code }));
      }
      if (!spotify.auth.isLoading && spotify.auth.error) {
        setSearchParams({});
      }
    }
  }, [
    code,
    dispatch,
    setSearchParams,
    spotify.auth.data,
    spotify.auth.error,
    spotify.auth.isLoading,
  ]);

  return (
    <>
      <a href={AUTH_URL} className="button is-primary">
        Login with Spotify
      </a>
    </>
  );
};

export default Login;
