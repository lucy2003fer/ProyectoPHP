import { useQuery } from "@tanstack/react-query";
import axios from "axios";

export interface TipoCultivo {
  id_tipo_cultivo: number;
  nombre: string;
  descripcion : string;
}

const fetchTipo = async (): Promise<TipoCultivo[]> => {
  const apiUrl = import.meta.env.VITE_API_URL;
  const token = localStorage.getItem("token");

  if (!apiUrl) {
    throw new Error("La URL de la API no está definida en las variables de entorno.");
  }

  if (!token) {
    throw new Error("No hay token disponible, inicia sesión nuevamente.");
  }

  try {
    const { data } = await axios.get<{ status: number; data:TipoCultivo[] }>(
      `${apiUrl}/tipocultivo`,
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
    console.error("Error al obtener tipos cultivos:", error);
    throw new Error(error.response?.data?.message || "Error al obtener los tipos cultivos");
  }
};

// Hook personalizado para obtener usuarios
export const useTipoCultivo = () => {
  return useQuery({
    queryKey: ["tipocultivo"], // Nombre clave del query
    queryFn: fetchTipo,
    select: (data) => (Array.isArray(data) ? data : []), // Validar el array
    staleTime: 1000 * 60 * 5, // Cache por 5 minutos
    retry: 2, // Reintentar 2 veces si hay error
  });
};
