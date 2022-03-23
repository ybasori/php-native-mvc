import React from "react";
import { Link, Outlet, useLocation } from "react-router-dom";

const Marvel = () => {
  const { pathname } = useLocation();
  return (
    <>
      <h1 className="title">
        <Link to="/playground">Playground</Link> &raquo;{" "}
        <Link to="/playground/marvel">Marvel</Link>
      </h1>

      {pathname === "/playground/marvel" && (
        <div className="columns">
          <div className="column ">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/playground/marvel/characters">Character</Link>
            </div>
          </div>
          <div className="column ">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/playground/marvel/comics">Comics</Link>
            </div>
          </div>
          <div className="column ">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/playground/marvel/creators">Creators</Link>
            </div>
          </div>
          <div className="column ">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/playground/marvel/events">Events</Link>
            </div>
          </div>
          <div className="column ">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/playground/marvel/series">Series</Link>
            </div>
          </div>
          <div className="column ">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/playground/marvel/stories">Stories</Link>
            </div>
          </div>
        </div>
      )}

      <Outlet />
    </>
  );
};

export default Marvel;
