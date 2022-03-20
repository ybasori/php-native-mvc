import React, { Suspense, lazy } from "react";
import { Routes, Route } from "react-router-dom";
import { Provider } from "react-redux";
import "@fortawesome/fontawesome-free/css/all.css";
import "bulma/css/bulma.min.css";

import "./global.scss";
import { store } from "./redux";
import Navbar from "./components/Navbar";
import { ModalProvider } from "./components/Modal";
import { AlertProvider } from "./components/Alert";

const Home = lazy(() => import("./pages/Home"));
const About = lazy(() => import("./pages/About"));
const Movies = lazy(() => import("./pages/Movies"));
const TVs = lazy(() => import("./pages/TVs"));
const Marvel = lazy(() => import("./pages/Marvel"));
const MarvelCharacters = lazy(() => import("./pages/Marvel/Characters"));
const MarvelComics = lazy(() => import("./pages/Marvel/Comics"));
const MarvelCreators = lazy(() => import("./pages/Marvel/Creators"));
const MarvelEvents = lazy(() => import("./pages/Marvel/Events"));
const MarvelSeries = lazy(() => import("./pages/Marvel/Series"));
const MarvelStories = lazy(() => import("./pages/Marvel/Stories"));

export default function App() {
  return (
    <>
      <Provider store={store}>
        <AlertProvider>
          <ModalProvider>
            <>
              <Navbar />
              <Suspense fallback={<>loading</>}>
                <Routes>
                  <Route index element={<Home />} />
                  <Route path="about" element={<About />} />
                  <Route path="movies" element={<Movies />} />
                  <Route path="tvs" element={<TVs />} />
                  <Route path="marvel" element={<Marvel />}>
                    <Route path="characters" element={<MarvelCharacters />} />
                    <Route path="comics" element={<MarvelComics />} />
                    <Route path="creators" element={<MarvelCreators />} />
                    <Route path="events" element={<MarvelEvents />} />
                    <Route path="series" element={<MarvelSeries />} />
                    <Route path="stories" element={<MarvelStories />} />
                  </Route>
                </Routes>
              </Suspense>
            </>
          </ModalProvider>
        </AlertProvider>
      </Provider>
    </>
  );
}
