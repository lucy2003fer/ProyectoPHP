import Formulario from "../globales/Formulario";
import { useCrearTipo } from "../../hooks/tipo_cultivo/useCrearTipo";
import { useState, useEffect } from "react"; // Añade useEffect

const CrearTipo = () => {
  const { mutate: crearTipo, isError, error, isSuccess } = useCrearTipo();
  const [serverError, setServerError] = useState<string | null>(null);
  const [showSuccess, setShowSuccess] = useState(false); // Nuevo estado para controlar el mensaje

  // Efecto para manejar la visualización del mensaje de éxito
  useEffect(() => {
    if (isSuccess) {
      setShowSuccess(true);
      const timer = setTimeout(() => setShowSuccess(false), 2000); // Oculta después de 3 segundos
      return () => clearTimeout(timer); // Limpia el timer al desmontar
    }
  }, [isSuccess]);

  const camposTipo = [
    {
      id: "nombre",
      label: "Nombre",
      type: "text",
      required: true,
      placeholder: "Ej: Hortalizas",
    },
    {
      id: "descripcion",
      label: "Descripción",
      type: "text",
      required: true,
      placeholder: "Descripción del tipo de cultivo",
    },
  ];

  const handleSubmit = (formData: { [key: string]: string }) => {
    setServerError(null);
    
    if (!formData.nombre.trim()) {
      setServerError("El nombre es requerido");
      return;
    }

    crearTipo({
      nombre: formData.nombre.trim(),
      descripcion: formData.descripcion,
    });
  };

  return (
    <div className="max-w-md mx-auto p-4">
      <h1 className="text-3xl font-bold text-gray-800 mb-6">Crear Tipo de Cultivo</h1>
      
      {(isError || serverError) && (
        <div className="mb-4 p-3 bg-red-100 text-red-700 rounded">
          {serverError || error?.message}
        </div>
      )}

      <Formulario
        fields={camposTipo}
        onSubmit={handleSubmit}
        isError={isError}
        isSuccess={showSuccess} // Usamos el estado local controlado
        title=""
        submitButtonText="Crear Tipo"
        cancelButtonText="Cancelar"
        onCancel={() => window.history.back()}
      />
    </div>
  );
};

export default CrearTipo;