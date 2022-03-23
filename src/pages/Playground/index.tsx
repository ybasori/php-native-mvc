import React from "react";
import { Link, Outlet, useLocation } from "react-router-dom";

const Playground = () => {
  const { pathname } = useLocation();
  return (
    <section className="section">
      <div className="container">
        {pathname === "/playground" && (
          <>
            <h1 className="title">
              <Link to="/playground">Playground</Link>
            </h1>
            <div className="columns is-multiline is-desktop">
              <div className="column">
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  <Link to="/playground/movies">Movies</Link>
                </div>
              </div>
              <div className="column">
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  <Link to="/playground/tv-shows">TV Shows</Link>
                </div>
              </div>
              <div className="column">
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  <Link to="/playground/pokemon">Pokemon</Link>
                </div>
              </div>
              <div className="column">
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  <Link to="/playground/marvel">Marvel</Link>
                </div>
              </div>
              <div className="column">
                <div className="box is-justify-content-center is-flex-direction-column is-flex">
                  <Link to="/playground/spotify">Spotify</Link>
                </div>
              </div>
            </div>
          </>
        )}
        <Outlet />
      </div>
    </section>
  );
};

export default Playground;
