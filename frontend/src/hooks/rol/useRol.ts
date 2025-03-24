import { useQuery } from "@tanstack/react-query";
import axios from "axios";

export interface Rol {
  id_rol: number;
  nombre_rol: string;
  fecha_creacion : string;
}

const fetchRol = async (): Promise<Rol[]> => {
  const apiUrl = import.meta.env.VITE_API_URL;
  const token = localStorage.getItem("token");

  if (!apiUrl) {
    throw new Error("La URL de la API no está definida en las variables de entorno.");
  }

  if (!token) {
    throw new Error("No hay token disponible, inicia sesión nuevamente.");
  }

  try {
    const { data } = await axios.get<{ status: number; data:Rol[] }>(
      `${apiUrl}/rol`,
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
    console.error("Error al obtener roles:", error);
    throw new Error(error.response?.data?.message || "Error al obtener los roles");
  }
};

// Hook personalizado para obtener usuarios
export const useRol = () => {
  return useQuery({
    queryKey: ["rol"], // Nombre clave del query
    queryFn: fetchRol,
    select: (data) => (Array.isArray(data) ? data : []), // Validar el array
    staleTime: 1000 * 60 * 5, // Cache por 5 minutos
    retry: 2, // Reintentar 2 veces si hay error
  });
};
