import { useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export interface NuevoRol {
    nombre_rol: string;
    fecha_creacion: string; // Este campo será seleccionado desde un <select>
}

const useCrearRol = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (nuevoRol: NuevoRol) => {
      const token = localStorage.getItem("token");
      if (!token) throw new Error("No hay token de autenticación");

      try {
        // Formatea la fecha según lo que espera tu backend (generalmente 'YYYY-MM-DD')
        const fechaFormateada = new Date(nuevoRol.fecha_creacion)
          .toISOString()
          .split('T')[0];

        const response = await axios.post(
          `${apiUrl}/rol`, // Usa '/rol' como en tu endpoint original
          {
            ...nuevoRol,
            fecha_creacion: fechaFormateada // Envía solo la parte de la fecha
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
              'Content-Type': 'application/json'
            }
          }
        );
        return response.data;
      } catch (error) {
        if (axios.isAxiosError(error)) {
          console.error("Error completo:", error.response?.data);
          throw new Error(error.response?.data?.message || "Error al crear el rol");
        }
        throw error;
      }
    },
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ["rol"] }),
  });
};

export default useCrearRol;