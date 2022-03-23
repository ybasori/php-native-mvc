import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import Button from "../../../../components/Button";
import { resetPostSpotifyLogin } from "../../../../redux/spotify";
import { Reducers } from "../../../../redux/types";
import Dashboard from "./components/Dashboard";
import Login from "./components/Login";

const Spotify = () => {
  const dispatch = useDispatch();
  const { spotify } = useSelector((state: Reducers) => state);
  return (
    <>
      <h1 className="title">
        <Link to="/playground">Playground</Link> &raquo;{" "}
        <Link to="/playground/spotify">Spotify </Link>
        {spotify.auth.data && (
          <Button
            size="normal"
            onClick={() => dispatch(resetPostSpotifyLogin())}
          >
            Logout
          </Button>
        )}
      </h1>
      {spotify.auth.data ? <Dashboard /> : <Login />}
    </>
  );
};

export default Spotify;
