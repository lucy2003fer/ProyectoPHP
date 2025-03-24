import { useState } from "react";
import { useUsuarios } from "@/hooks/usuarios/useUsuario";
import { useRol } from "@/hooks/rol/useRol";
import useEditarUsuario, { Usuario } from "@/hooks/usuarios/useEditarUsuario";
import Tabla from "../../components/globales/Tabla";
import FormularioModal from "../../components/globales/FormularioModal";

const Usuarios = () => {
  const { data: usuarios, isLoading, isError } = useUsuarios();
  const { data: roles } = useRol();
  const { mutate: editarUsuario } = useEditarUsuario();
  const [usuarioEditando, setUsuarioEditando] = useState<Usuario | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const handleCrearUsuario = () => {
    // Lógica para crear un nuevo usuario
    console.log("Crear nuevo usuario");
  };

  const handleEditarUsuario = (usuario: Usuario) => {
    setUsuarioEditando(usuario);
    setIsModalOpen(true);
  };

  const handleSubmit = (datosActualizados: Record<string, any>) => {
    if (usuarioEditando) {
      editarUsuario({ ...usuarioEditando, ...datosActualizados });
    }
    setIsModalOpen(false);
  };

  const campos = [
    { key: "nombre", label: "Nombre" },
    { key: "email", label: "Email", type: "email" },
    {
      key: "fk_id_rol",
      label: "Rol",
      type: "select",
      options: roles?.map((rol) => ({ value: rol.id_rol, label: rol.nombre_rol })) || [],
    },
  ];

  if (isLoading) return <div>Cargando usuarios...</div>;
  if (isError) return <div>Error al cargar los usuarios</div>;

  return (
    <div>
      <Tabla
        title="Usuarios"
        headers={[
          { key: "identificacion", label: "Identificación" },
          { key: "nombre", label: "Nombre" },
          { key: "email", label: "Email" },
          {
            key: "fk_id_rol",
            label: "Rol",
            render: (row: Usuario) => row.fk_id_rol?.nombre_rol || "—",
          },
        ]}
        data={usuarios || []}
        onClickAction={(row, action) => {
          if (action === "editar") {
            handleEditarUsuario(row);
          }
        }}
        onCreate={handleCrearUsuario} // 👈 Botón de crear usuario
        searchFields={["nombre", "identificacion"]}
      />

      {usuarioEditando && (
        <FormularioModal
          isOpen={isModalOpen}
          onClose={() => setIsModalOpen(false)}
          titulo="Editar Usuario"
          campos={campos}
          datosIniciales={{
            ...usuarioEditando,
            fk_id_rol: usuarioEditando?.fk_id_rol?.id_rol || "",
          }}
          onSubmit={handleSubmit}
        />
      )}
    </div>
  );
};

export default Usuarios;