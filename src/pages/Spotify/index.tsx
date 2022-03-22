import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import Button from "../../components/Button";
import { resetPostSpotifyLogin } from "../../redux/spotify";
import { Reducers } from "../../redux/types";
import Dashboard from "./components/Dashboard";
import Login from "./components/Login";

const Spotify = () => {
  const dispatch = useDispatch();
  const { spotify } = useSelector((state: Reducers) => state);
  return (
    <>
      <section className="section">
        <div className="container">
          <h1 className="title">
            <Link to="/spotify">Spotify </Link>
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
        </div>
      </section>
    </>
  );
};

export default Spotify;
