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
import { SpotifyPlayerProvider } from "./components/SpotifyPlayer";

const Home = lazy(() => import("./pages/Home"));
const About = lazy(() => import("./pages/About"));
const Pokemon = lazy(() => import("./pages/Playground/pages/Pokemon"));
const Movies = lazy(() => import("./pages/Playground/pages/Movies"));
const TvShow = lazy(() => import("./pages/Playground/pages/TvShow"));
const Marvel = lazy(() => import("./pages/Playground/pages/Marvel"));
const MarvelCharacters = lazy(
  () => import("./pages/Playground/pages/Marvel/pages/Characters")
);
const MarvelComics = lazy(
  () => import("./pages/Playground/pages/Marvel/pages/Comics")
);
const MarvelCreators = lazy(
  () => import("./pages/Playground/pages/Marvel/pages/Creators")
);
const MarvelEvents = lazy(
  () => import("./pages/Playground/pages/Marvel/pages/Events")
);
const MarvelSeries = lazy(
  () => import("./pages/Playground/pages/Marvel/pages/Series")
);
const MarvelStories = lazy(
  () => import("./pages/Playground/pages/Marvel/pages/Stories")
);
const Spotify = lazy(() => import("./pages/Playground/pages/Spotify"));
const Playground = lazy(() => import("./pages/Playground"));

export default function App() {
  return (
    <>
      <Provider store={store}>
        <AlertProvider>
          <ModalProvider>
            <SpotifyPlayerProvider>
              <>
                <Navbar />
                <Suspense fallback={<>loading</>}>
                  <Routes>
                    <Route index element={<Home />} />
                    <Route path="about" element={<About />} />

                    <Route path="playground" element={<Playground />}>
                      <Route path="movies" element={<Movies />} />
                      <Route path="pokemon" element={<Pokemon />} />
                      <Route path="tv-shows" element={<TvShow />} />
                      <Route path="marvel" element={<Marvel />}>
                        <Route
                          path="characters"
                          element={<MarvelCharacters />}
                        />
                        <Route path="comics" element={<MarvelComics />} />
                        <Route path="creators" element={<MarvelCreators />} />
                        <Route path="events" element={<MarvelEvents />} />
                        <Route path="series" element={<MarvelSeries />} />
                        <Route path="stories" element={<MarvelStories />} />
                      </Route>
                      <Route path="spotify" element={<Spotify />} />
                    </Route>
                  </Routes>
                </Suspense>
              </>
            </SpotifyPlayerProvider>
          </ModalProvider>
        </AlertProvider>
      </Provider>
    </>
  );
}
