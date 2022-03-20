import React from "react";
import { Link, Outlet } from "react-router-dom";

const Marvel = () => {
  return (
    <div className="container">
      <div className="columns">
        <div className="column">
          <Link to="/marvel">Marvel</Link>
        </div>
      </div>
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
  );
};

export default Marvel;
