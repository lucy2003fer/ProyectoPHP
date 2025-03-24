import { useState } from "react";

interface FormularioModalProps {
  isOpen: boolean;
  onClose: () => void;
  titulo: string;
  campos: { key: string; label: string; type?: string; options?: { value: any; label: string }[] }[];
  datosIniciales: Record<string, any>;
  onSubmit: (datosActualizados: Record<string, any>) => void;
}

const FormularioModal = ({
  isOpen,
  onClose,
  titulo,
  campos,
  datosIniciales,
  onSubmit,
}: FormularioModalProps) => {
  const [datos, setDatos] = useState(datosIniciales);

  const handleChange = (key: string, value: string) => {
    setDatos((prev) => ({ ...prev, [key]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit(datos);
    onClose();
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 className="text-2xl font-bold mb-4">{titulo}</h2>
        <form onSubmit={handleSubmit}>
          {campos.map((campo) => (
            <div key={campo.key} className="mb-4">
              <label className="block text-sm font-medium text-gray-700">
                {campo.label}
              </label>
              {campo.type === "select" ? (
                <select
                  value={datos[campo.key] || ""}
                  onChange={(e) => handleChange(campo.key, e.target.value)}
                  className="mt-1 block w-full border border-gray-300 rounded-md p-2"
                >
                  {campo.options?.map((option) => (
                    <option key={option.value} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
              ) : (
                <input
                  type={campo.type || "text"}
                  value={datos[campo.key] || ""}
                  onChange={(e) => handleChange(campo.key, e.target.value)}
                  className="mt-1 block w-full border border-gray-300 rounded-md p-2"
                />
              )}
            </div>
          ))}
          <div className="flex justify-end gap-4">
            <button
              type="button"
              onClick={onClose}
              className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
            >
              Cancelar
            </button>
            <button
              type="submit"
              className="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default FormularioModal;