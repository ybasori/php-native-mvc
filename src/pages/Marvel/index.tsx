import React from "react";
import { Link, Outlet, useLocation } from "react-router-dom";

const Marvel = () => {
  const { pathname } = useLocation();
  return (
    <section className="section">
      <div className="container">
        <h1 className="title">
          <Link to="/marvel">Marvel</Link>
        </h1>

        {pathname === "/marvel" && (
          <div className="columns">
            <div className="column ">
              <div className="box is-justify-content-center is-flex-direction-column is-flex">
                <Link to="/marvel/characters">Character</Link>
              </div>
            </div>
            <div className="column ">
              <div className="box is-justify-content-center is-flex-direction-column is-flex">
                <Link to="/marvel/comics">Comics</Link>
              </div>
            </div>
            <div className="column ">
              <div className="box is-justify-content-center is-flex-direction-column is-flex">
                <Link to="/marvel/creators">Creators</Link>
              </div>
            </div>
            <div className="column ">
              <div className="box is-justify-content-center is-flex-direction-column is-flex">
                <Link to="/marvel/events">Events</Link>
              </div>
            </div>
            <div className="column ">
              <div className="box is-justify-content-center is-flex-direction-column is-flex">
                <Link to="/marvel/series">Series</Link>
              </div>
            </div>
            <div className="column ">
              <div className="box is-justify-content-center is-flex-direction-column is-flex">
                <Link to="/marvel/stories">Stories</Link>
              </div>
            </div>
          </div>
        )}

        <Outlet />
      </div>
    </section>
  );
};

export default Marvel;
