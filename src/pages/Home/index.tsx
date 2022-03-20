import React from // useState
"react";
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
    <div className="container">
      <div className="columns">
        <div className="column">
          <Link to="/marvel">Marvel</Link>
        </div>
        <div className="column">
          <Link to="/movies">Movies</Link>
        </div>
        <div className="column">
          <Link to="/tvs">TVs</Link>
        </div>
      </div>
    </div>
  );
};

export default Home;
