import React, { useState } from "react";
import { Link, NavLink } from "react-router-dom";
import "./style.scss";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string[]>([]);

  const onDropdown = (name: string) => {
    if (activeDropdown.findIndex((item) => item === name) < 0) {
      setActiveDropdown([name]);
    } else {
      setActiveDropdown([...activeDropdown.filter((item) => item !== name)]);
    }
  };

  const checkDropdown = (name: string) =>
    activeDropdown.findIndex((item) => item === name) >= 0;

  return (
    <>
      <nav
        className="navbar is-link"
        role="navigation"
        aria-label="main navigation"
      >
        <div className="navbar-brand">
          <Link className="navbar-item" to="/">
            <img
              src="https://bulma.io/images/bulma-logo-white.png"
              width="112"
              height="28"
            />
          </Link>
          <a
            role="button"
            className={`navbar-burger ${isOpen && "is-active"}`}
            aria-label="menu"
            aria-expanded="false"
            data-target="navbarBasicExample"
            onClick={() => setIsOpen(!isOpen)}
          >
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </a>
        </div>

        <div
          id="navbarBasicExample"
          className={`navbar-menu ${isOpen && "is-active"}`}
        >
          <div className="navbar-start">
            <NavLink
              className={({ isActive }) =>
                `navbar-item ${isActive && "is-active"}`
              }
              to="/"
            >
              Home
            </NavLink>
            <NavLink
              className={({ isActive }) =>
                `navbar-item ${isActive && "is-active"}`
              }
              to="/about"
            >
              About
            </NavLink>

            <div
              className={`navbar-item has-dropdown ${
                checkDropdown("more") && "is-active"
              }`}
            >
              <a className="navbar-link" onClick={() => onDropdown("more")}>
                More
              </a>

              <div className="navbar-dropdown">
                <a className="navbar-item">About</a>
                <a className="navbar-item">Jobs</a>
                <a className="navbar-item">Contact</a>
                <hr className="navbar-divider" />
                <a className="navbar-item">Report an issue</a>
              </div>
            </div>
          </div>

          <div className="navbar-end">
            <div className="navbar-item">
              <div className="buttons">
                <a className="button is-primary">
                  <strong>Sign up</strong>
                </a>
                <a className="button is-light">Log in</a>
              </div>
            </div>
          </div>
        </div>
        {activeDropdown.length > 0 && (
          <div
            className="navbar-bg"
            onClick={() => setActiveDropdown([...[]])}
          ></div>
        )}
        {isOpen && (
          <div className="navbar-bg" onClick={() => setIsOpen(false)}></div>
        )}
      </nav>
    </>
  );
};

export default Navbar;
