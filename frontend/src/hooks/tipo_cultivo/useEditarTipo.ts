import { useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";
import { TipoCultivo } from "./useTipoCultivo";

const apiUrl = import.meta.env.VITE_API_URL;


const useEditarTipo = () => {
    const queryClient = useQueryClient();
  
    return useMutation({
      mutationFn: async (tipoActualizado: TipoCultivo) => {
        const { id_tipo_cultivo, ...datos } = tipoActualizado;
        const { data } = await axios.put(
          `${apiUrl}/tipocultivo/${id_tipo_cultivo}`,
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
        queryClient.invalidateQueries({ queryKey: ["tipocultivo"] });
      },
      onError: (error) => {
        console.error("Error al actualizar el usuario:", error);
        throw new Error("No se pudo actualizar el usuario. Por favor, inténtalo de nuevo.");
      },
    });
  };

export default useEditarTipo;