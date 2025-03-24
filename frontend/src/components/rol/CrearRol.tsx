import Formulario from "../globales/Formulario";
import useCrearRol from "@/hooks/rol/useCrearRol";

const CrearRol = () => {
  const { mutate: crearRol, isError, isSuccess } = useCrearRol();

  const camposRol = [
    {
      id: "nombre_rol",
      label: "Nombre del Rol",
      type: "text",
      required: true,
    },
    {
      id: "fecha_creacion",
      label: "Fecha de creación",
      type: "date",
      required: true
    },
  ];

  const handleSubmit = (formData: { [key: string]: string }) => {
    crearRol({
      nombre_rol: formData.nombre_rol,
      fecha_creacion: formData.fecha_creacion
    });
  };

  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-800 mb-8">Crear Rol</h1>
      <Formulario
        fields={camposRol}
        onSubmit={handleSubmit}
        isError={isError}
        isSuccess={isSuccess}
        title="Crear Rol"
        submitButtonText="Crear"
        cancelButtonText="Cancelar"
        onCancel={() => window.history.back()}
      />
    </div>
  );
};

export default CrearRol;