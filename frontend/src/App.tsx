import { Route, Routes } from "react-router-dom";
import UsuarioPage from "./pages/UsuarioPage";

function App() {
  return (
    <Routes>
      <Route element={<UsuarioPage />} path="/" />

    </Routes>
  );
}

export default App;
