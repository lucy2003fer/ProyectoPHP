import { useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export interface NuevoUsuario {
    identificacion: number;
    nombre: string;
    email: string;
    contrasena: string;
    fk_id_rol: number; // Este campo será seleccionado desde un <select>
  }

export const useCrearUsuario = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (nuevoUsuario: NuevoUsuario) => {
      const token = localStorage.getItem("token");
      if (!token) {
        throw new Error("No se ha encontrado un token de autenticación");
      }

      const { data } = await axios.post(
        `${apiUrl}/usuario`, // Ajusta la URL según tu endpoint
        nuevoUsuario,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      return data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["usuarios"] }); // Recarga la lista de usuarios
    },
    onError: (error: any) => {
      console.error("Error al crear el usuario:", error.message);
    },
  });
};