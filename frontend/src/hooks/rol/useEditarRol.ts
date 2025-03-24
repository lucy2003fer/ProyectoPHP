import { useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export interface Rol {
  id_rol: number;
  nombre_rol: string;
  fecha_creacion : string;
}

const useEditarRol = () => {
    const queryClient = useQueryClient();
  
    return useMutation({
      mutationFn: async (RolActualizado: Rol) => {
        const { id_rol, ...datos } = RolActualizado;
        const { data } = await axios.put(
          `${apiUrl}/Rol/${id_rol}`,
          datos,
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        return data;
      },
      onSuccess: () => {
        // Invalida la consulta para recargar los datos
        queryClient.invalidateQueries({ queryKey: ["rol"] });
      },
      onError: (error) => {
        console.error("Error al actualizar el rol:", error);
        throw new Error("No se pudo actualizar el rol. Por favor, inténtalo de nuevo.");
      },
    });
  };

export default useEditarRol;