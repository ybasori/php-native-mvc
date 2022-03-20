import React from "react";
import { Link, Outlet } from "react-router-dom";

const Marvel = () => {
  return (
    <section className="section">
      <div className="container">
        <h1 className="title">
          <Link to="/marvel">Marvel</Link>
        </h1>

        <div className="columns">
          <div className="column">
            <Link to="/marvel/characters">Character</Link>
            <Link to="/marvel/comics">Comics</Link>
            <Link to="/marvel/creators">Creators</Link>
            <Link to="/marvel/events">Events</Link>
            <Link to="/marvel/series">Series</Link>
            <Link to="/marvel/stories">Stories</Link>
          </div>
        </div>

        <Outlet />
      </div>
    </section>
  );
};

export default Marvel;
