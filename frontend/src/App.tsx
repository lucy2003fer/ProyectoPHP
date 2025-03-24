import { Route, Routes } from "react-router-dom";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { HeroUIProvider } from "@heroui/system";
import UsuarioPage from "./pages/usuarios/UsuarioPage";
import Login from "./components/usuarios/InicioSesion"
import Principal from "./components/globales/Principal";
import RolPage from "./pages/rol/RolPage";
import CrearUsuarioPage from "./pages/usuarios/CrearUsuarioPage";
import CrearRolPage from "./pages/rol/CrearRolPage";




const queryClient = new QueryClient();

function App() {
  return (
    <HeroUIProvider>
      <QueryClientProvider client={queryClient}>
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/usuarios" element={<Principal><UsuarioPage /></Principal>} />
          <Route path="crearusuario" element={<Principal><CrearUsuarioPage /></Principal>} />
          
          <Route path="rol" element={<Principal><RolPage /></Principal>} />
          <Route path="crearrol" element={<Principal><CrearRolPage /></Principal>} />
        </Routes>
      </QueryClientProvider>
    </HeroUIProvider>
  );
}

export default App;
