import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useRol } from "@/hooks/rol/useRol";
import useEditarRol from "@/hooks/rol/useEditarRol";
import Tabla from "../../components/globales/Tabla";
import FormularioModal from "../../components/globales/FormularioModal";
import VentanaModales from "../../components/globales/VentanasModales";
import type { Rol } from "@/hooks/rol/useEditarRol";

const Rol = () => {
  const navigate = useNavigate();
  const { data: roles, isLoading, isError } = useRol();
  const { mutate: editarRol } = useEditarRol();
  const [rolEditando, setRolEditando] = useState<Rol | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [rolDetalles, setRolDetalles] = useState<Rol | null>(null);
  const [isDetallesModalOpen, setIsDetallesModalOpen] = useState(false);

  const handleCrearRol = () => {
    navigate("/crearrol");
  };

  const handleEditarRol = (rol: Rol) => {
    setRolEditando(rol);
    setIsModalOpen(true);
  };

  const handleVerDetalles = (rol: Rol) => {
    setRolDetalles(rol);
    setIsDetallesModalOpen(true);
  };

  const handleSubmit = (datosActualizados: Record<string, any>) => {
    if (rolEditando) {
      editarRol({ 
        ...rolEditando, 
        ...datosActualizados,
        fecha_creacion: new Date(datosActualizados.fecha_creacion).toISOString().split('T')[0]
      });
    }
    setIsModalOpen(false);
  };

  const campos = [
    { 
      key: "nombre_rol", 
      label: "Nombre del Rol", 
      type: "text",
      required: true 
    },
    { 
      key: "fecha_creacion", 
      label: "Fecha de creación", 
      type: "date",
      required: true 
    },
  ];

  if (isLoading) return <div>Cargando roles...</div>;
  if (isError) return <div>Error al cargar los roles</div>;

  return (
    <div>
      <Tabla
        title="Roles"
        headers={[
          { key: "id_rol", label: "ID" },
          { key: "nombre_rol", label: "Nombre del Rol" },
          { 
            key: "fecha_creacion", 
            label: "Fecha de creación",
            render: (row: Rol) => new Date(row.fecha_creacion).toLocaleDateString()
          },
        ]}
        data={roles || []}
        onClickAction={(row, action) => {
          if (action === "editar") {
            handleEditarRol(row);
          } else if (action === "ver") {
            handleVerDetalles(row);
          }
        }}
        onCreate={handleCrearRol}
        searchFields={["nombre_rol"]}
      />

      {rolEditando && (
        <FormularioModal
          isOpen={isModalOpen}
          onClose={() => setIsModalOpen(false)}
          titulo="Editar Rol"
          campos={campos}
          datosIniciales={{
            nombre_rol: rolEditando.nombre_rol,
            fecha_creacion: rolEditando.fecha_creacion.split('T')[0]
          }}
          onSubmit={handleSubmit}
        />
      )}

      {rolDetalles && (
        <VentanaModales
          isOpen={isDetallesModalOpen}
          onClose={() => setIsDetallesModalOpen(false)}
          contenido={{
            "ID": rolDetalles.id_rol,
            "Nombre del Rol": rolDetalles.nombre_rol,
            "Fecha de creación": new Date(rolDetalles.fecha_creacion).toLocaleDateString(),
          }}
          titulo="Detalles del Rol"
        />
      )}
    </div>
  );
};

export default Rol;