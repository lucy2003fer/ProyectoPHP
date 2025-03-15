import { useQuery } from "@tanstack/react-query";
import axios from "axios";

// Definir el tipo de Usuario basado en la API
interface Usuario {
  identificacion: number;
  nombre: string;
  contrasena: string;
  email: string;
  fk_id_rol: number;
}

// Función para obtener los usuarios desde la API
const fetchUsuarios = async (): Promise<Usuario[]> => {
  const { data } = await axios.get<{ status: number; data: Usuario[] }>(
    `${import.meta.env.VITE_API_URL}/usuario`
  );
  
  return data.data || []; // Asegurar que devuelve un array
};

// Hook personalizado para obtener usuarios
export const useUsuarios = () => {
  return useQuery({
    queryKey: ["usuarios"], // Nombre clave del query
    queryFn: fetchUsuarios,
    select: (data) => (Array.isArray(data) ? data : []), // Validar el array
    staleTime: 1000 * 60 * 5, // Cache por 5 minutos
    retry: 2, // Reintentar 2 veces si hay error
  });
};
