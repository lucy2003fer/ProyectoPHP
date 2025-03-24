import { useQuery } from "@tanstack/react-query";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export const useUsuariosId = (identificacion: string | undefined) => {
    return useQuery({
        queryKey: ["usuario", identificacion], 
        queryFn: async () => {
            if (!identificacion) throw new Error("ID no proporcionado");
            const { data } = await axios.get(`${apiUrl}venta/${identificacion}`);
            console.log("🌱 Datos obtenidos del backend:", data); // 👀 Verifica los datos
            return data;
        },
        enabled: !!identificacion, 
    });
};
