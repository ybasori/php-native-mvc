import React, { useState } from "react";
import { useAlert } from "../../components/Alert";
import Box from "../../components/Box";
import Button from "../../components/Button";
import { useModal } from "../../components/Modal";

const Home = () => {
  const { setModal } = useModal();
  const { setAlert } = useAlert();
  const [counter, setCounter] = useState(0);
  const openModal = () => {
    setModal({
      title: "Title ku",
      body: <>Modal</>,
      onConfirm: () => null,
      confirmText: "Okay bro",
      onCancel: () => null,
      cancelText: "Batalin ajalah",
    });
  };
  const openAlert = () => {
    const newCounter = counter + 1;
    setAlert(`hello ${newCounter}`);
    setCounter(newCounter);
  };
  return (
    <section className="section">
      <div className="container">
        <h1 className="title">Hello World</h1>
        <p className="subtitle">
          My first website with <strong>Bulma</strong>!
        </p>
      </div>
      <Button onClick={openModal}>open modal</Button>
      <Button onClick={openAlert}>open alert</Button>
      <Box>mantap</Box>
    </section>
  );
};

export default Home;
