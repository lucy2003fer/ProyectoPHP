import { useQuery } from "@tanstack/react-query";
import axios from "axios";

export interface Rol {
  id_rol: number;
  nombre_rol: string;
  fecha_creacion : string;
}

// Definir el tipo de Usuario basado en la API
interface Usuario {
  identificacion: number;
  nombre: string;
  contrasena: string;
  email: string;
  fk_id_rol: Rol | null;
}

// Función para obtener los usuarios desde la API
const fetchUsuarios = async (): Promise<Usuario[]> => {
  const apiUrl = import.meta.env.VITE_API_URL;
  const token = localStorage.getItem("token");

  if (!apiUrl) {
    throw new Error("La URL de la API no está definida en las variables de entorno.");
  }

  if (!token) {
    throw new Error("No hay token disponible, inicia sesión nuevamente.");
  }

  try {
    const { data } = await axios.get<{ status: number; data: Usuario[] }>(
      `${apiUrl}/usuario`,
      {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`, // 👈 Enviando el token
        },
        withCredentials: true, // 👈 Asegura que se envíen cookies si la API las usa
      }
    );

    return data.data || []; // Asegurar que devuelve un array
  } catch (error: any) {
    console.error("Error al obtener usuarios:", error);
    throw new Error(error.response?.data?.message || "Error al obtener los usuarios");
  }
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
