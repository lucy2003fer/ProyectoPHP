import Formulario from "../globales/Formulario";
import { useCrearUsuario } from "@/hooks/usuarios/useCrearUsuario";
import { useRol } from "@/hooks/rol/useRol"; // Suponiendo que tienes un hook para obtener los roles

const CrearUsuario = () => {
  const { data: roles, isLoading: isLoadingRoles } = useRol(); // Obtener los roles
  const { mutate: crearUsuario, isError, isSuccess } = useCrearUsuario();

  const camposUsuario = [
    {
      id: "identificacion",
      label: "Identificación",
      type: "number",
      required: true,
    },
    {
      id: "nombre",
      label: "Nombre",
      type: "text",
      required: true,
    },
    {
      id: "email",
      label: "Email",
      type: "email",
      required: true,
    },
    {
      id: "contrasena",
      label: "Contraseña",
      type: "password",
      required: true,
    },
    {
      id: "fk_id_rol",
      label: "Rol",
      type: "select",
      options: roles?.map((rol) => ({ value: rol.id_rol.toString(), label: rol.nombre_rol })) || [],
      required: true,
    },
  ];

  const handleSubmit = (formData: { [key: string]: string }) => {
    // Convertir los datos al formato esperado por el backend
    const nuevoUsuario = {
      identificacion: Number(formData.identificacion),
      nombre: formData.nombre,
      email: formData.email,
      contrasena: formData.contrasena,
      fk_id_rol: Number(formData.fk_id_rol),
    };

    // Enviar los datos al backend
    crearUsuario(nuevoUsuario);
  };

  const handleCancel = () => {
    console.log("Formulario cancelado");
  };

  if (isLoadingRoles) return <div>Cargando roles...</div>;

  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-800 mb-8">Crear Usuario</h1>
      <Formulario
        fields={camposUsuario}
        onSubmit={handleSubmit}
        isError={isError}
        isSuccess={isSuccess}
        title="Crear Usuario"
        submitButtonText="Crear"
        cancelButtonText="Cancelar"
        onCancel={handleCancel}
      />
    </div>
  );
};

export default CrearUsuario;