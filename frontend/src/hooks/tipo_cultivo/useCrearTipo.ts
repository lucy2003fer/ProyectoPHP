import { useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export interface NuevoTipo {
  nombre: string;
  descripcion: string;
}

export const useCrearTipo = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (nuevoTipo: NuevoTipo) => {
      const token = localStorage.getItem("token");
      if (!token) {
        throw new Error("No se ha encontrado un token de autenticación");
      }

      try {
        const { data } = await axios.post(
          `${apiUrl}/tipocultivo`,
          nuevoTipo,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              'Content-Type': 'application/json'
            }
          }
        );
        return data;
      } catch (error) {
        if (axios.isAxiosError(error)) {
          throw new Error(error.response?.data?.message || "Error al crear el tipo de cultivo");
        }
        throw error;
      }
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tipocultivo"] });
    },
    onError: (error: Error) => {
      console.error("Error al crear el tipo de cultivo:", error.message);
    }
  });
};