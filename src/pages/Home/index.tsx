import React from "react"; // useState
import { Link } from "react-router-dom";
// import { useAlert } from "../../components/Alert";
// import Box from "../../components/Box";
// import Button from "../../components/Button";
// import { useModal } from "../../components/Modal";

const Home = () => {
  // const { setModal } = useModal();
  // const { setAlert } = useAlert();
  // const [counter, setCounter] = useState(0);
  // const openModal = () => {
  //   setModal({
  //     title: "Title ku",
  //     body: <>Modal</>,
  //     onConfirm: () => null,
  //     confirmText: "Okay bro",
  //     onCancel: () => null,
  //     cancelText: "Batalin ajalah",
  //   });
  // };
  // const openAlert = () => {
  //   const newCounter = counter + 1;
  //   setAlert(`hello ${newCounter}`, {
  //     time: 3000,
  //   });
  //   setCounter(newCounter);
  // };
  return (
    <section className="section">
      <div className="container">
        <div className="columns is-multiline is-desktop">
          <div className="column">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/movies">Movies</Link>
            </div>
          </div>
          <div className="column">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/tv-shows">TV Shows</Link>
            </div>
          </div>
          <div className="column">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/pokemon">Pokemon</Link>
            </div>
          </div>
          <div className="column">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/marvel">Marvel</Link>
            </div>
          </div>
          <div className="column">
            <div className="box is-justify-content-center is-flex-direction-column is-flex">
              <Link to="/spotify">Spotify</Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Home;
