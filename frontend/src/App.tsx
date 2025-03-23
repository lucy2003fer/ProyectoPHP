import { Route, Routes } from "react-router-dom";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { HeroUIProvider } from "@heroui/system";
import UsuarioPage from "./pages/UsuarioPage";
import Login from "./components/usuarios/InicioSesion"




const queryClient = new QueryClient();

function App() {
  return (
    <HeroUIProvider>
      <QueryClientProvider client={queryClient}>
        <Routes>
          <Route path="/" element={<Login />} />
          <Route element={<UsuarioPage />} path="/usuarios" />

        </Routes>
      </QueryClientProvider>
    </HeroUIProvider>
  );
}

export default App;
