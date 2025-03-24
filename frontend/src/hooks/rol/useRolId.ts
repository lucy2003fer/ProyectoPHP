import { useQuery } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export const useRolId = (id_rol: string | undefined) => {
    return useQuery({
        queryKey: ["rol", id_rol], 
        queryFn: async () => {
            if (!id_rol) throw new Error("ID no proporcionado");
            const { data } = await axios.get(`${apiUrl}rol/${id_rol}`);
            console.log("🌱 Datos obtenidos del backend:", data); // 👀 Verifica los datos
            return data;
        },
        enabled: !!id_rol, 
    });
};
