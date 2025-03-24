import { useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export interface Rol {
  id_rol: number;
  nombre_rol: string;
  fecha_creacion : string;
}

export interface Usuario {
  identificacion: number;
  nombre: string;
  email: string;
  fk_id_rol: Rol | null;
}


const useEditarUsuario = () => {
    const queryClient = useQueryClient();
  
    return useMutation({
      mutationFn: async (usuarioActualizado: Usuario) => {
        const { identificacion, ...datos } = usuarioActualizado;
        const { data } = await axios.put(
          `${apiUrl}/usuario/${identificacion}`,
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
        queryClient.invalidateQueries({ queryKey: ["usuarios"] });
      },
      onError: (error) => {
        console.error("Error al actualizar el usuario:", error);
        throw new Error("No se pudo actualizar el usuario. Por favor, inténtalo de nuevo.");
      },
    });
  };

export default useEditarUsuario;