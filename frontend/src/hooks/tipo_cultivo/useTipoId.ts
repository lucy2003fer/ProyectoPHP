import { useQuery } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export const useTipoId = (id_tipo_cultivo: string | undefined) => {
    return useQuery({
        queryKey: ["tipocultivo", id_tipo_cultivo], 
        queryFn: async () => {
            if (!id_tipo_cultivo) throw new Error("ID no proporcionado");
            const { data } = await axios.get(`${apiUrl}/tipocultivo/${id_tipo_cultivo}`);
            console.log("🌱 Datos obtenidos del backend:", data); // 👀 Verifica los datos
            return data;
        },
        enabled: !!id_tipo_cultivo, 
    });
};
