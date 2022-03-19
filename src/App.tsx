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
                  <Route path="/" element={<Home />} />
                  <Route path="/about" element={<About />} />
                </Routes>
              </Suspense>
            </>
          </ModalProvider>
        </AlertProvider>
      </Provider>
    </>
  );
}
