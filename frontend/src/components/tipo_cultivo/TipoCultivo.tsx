import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { TipoCultivo, useTipoCultivo } from "@/hooks/tipo_cultivo/useTipoCultivo";
import useEditarTipo from "@/hooks/tipo_cultivo/useEditarTipo";
import Tabla from "../../components/globales/Tabla";
import FormularioModal from "../../components/globales/FormularioModal";
import VentanaModales from "../../components/globales/VentanasModales";

const Rol = () => {
  const navigate = useNavigate();
  const { data: tipocultivo, isLoading, isError } = useTipoCultivo();
  const { mutate: editarTipo } = useEditarTipo();
  const [tipoEditando, setTipoEditando] = useState<TipoCultivo | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [tipoDetalles, setTipoDetalles] = useState<TipoCultivo | null>(null);
  const [isDetallesModalOpen, setIsDetallesModalOpen] = useState(false);

  const handleCrearTipo = () => {
    navigate("/creartipo");
  };

  const handleEditarTipo = (tipocultivo: TipoCultivo) => {
    setTipoEditando(tipocultivo);
    setIsModalOpen(true);
  };

  const handleVerDetalles = (rol: TipoCultivo) => {
    setTipoDetalles(rol);
    setIsDetallesModalOpen(true);
  };

  const handleSubmit = (datosActualizados: Record<string, any>) => {
    if (tipoEditando) {
      editarTipo({ 
        ...tipoEditando, 
        ...datosActualizados,
      });
    }
    setIsModalOpen(false);
  };

  const campos = [
    { 
      key: "nombre", 
      label: "Nombre del tipo", 
      type: "text",
      required: true 
    },
    { 
      key: "descripcion", 
      label: "Descripcion", 
      type: "text",
      required: true 
    },
  ];

  if (isLoading) return <div>Cargando Tipos...</div>;
  if (isError) return <div>Error al cargar los Tipos</div>;

  return (
    <div>
      <Tabla
        title="Tipos de cultivos"
        headers={[
          { key: "id_tipo_cultivo", label: "ID" },
          { key: "nombre", label: "Nombre Tipo" },
          { key: "descripcion", label: "Descripcion" },

        ]}
        data={tipocultivo || []}
        onClickAction={(row, action) => {
          if (action === "editar") {
            handleEditarTipo(row);
          } else if (action === "ver") {
            handleVerDetalles(row);
          }
        }}
        onCreate={handleCrearTipo}
        searchFields={["nombre"]}
      />

      {tipoEditando && (
        <FormularioModal
          isOpen={isModalOpen}
          onClose={() => setIsModalOpen(false)}
          titulo="Editar tipo de cultivo"
          campos={campos}
          datosIniciales={{
            nombre: tipoEditando.nombre,
            descripcion: tipoEditando.descripcion,
          }}
          onSubmit={handleSubmit}
        />
      )}

      {tipoDetalles && (
        <VentanaModales
          isOpen={isDetallesModalOpen}
          onClose={() => setIsDetallesModalOpen(false)}
          contenido={{
            "ID": tipoDetalles.id_tipo_cultivo,
            "Nombre": tipoDetalles.nombre,
            "Descripcion": tipoDetalles.descripcion,
          }}
          titulo="Detalles del tipo de cultivo"
        />
      )}
    </div>
  );
};

export default Rol;