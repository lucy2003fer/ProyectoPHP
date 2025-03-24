import React from "react";
import Button from "../globales/Button";

interface FormField {
  id: string;
  label: string;
  type: string;
  options?: { value: string; label: string }[]; // Soporte para opciones con valor y etiqueta
  placeholder?: string;
  required?: boolean;
  disabled?: boolean;
}

interface FormProps {
  fields: FormField[];
  onSubmit: (formData: { [key: string]: string }) => void;
  isError?: boolean;
  isSuccess?: boolean;
  title: string;
  initialValues?: { [key: string]: string };
  submitButtonText?: string;
  cancelButtonText?: string;
  onCancel?: () => void; // Manejo de cancelación personalizado
  errors?: { [key: string]: string }; // Errores específicos por campo
}

const Formulario: React.FC<FormProps> = ({
  fields,
  onSubmit,
  isError,
  isSuccess,
  title,
  initialValues,
  submitButtonText = "Registrar",
  cancelButtonText = "Cancelar",
  onCancel,
  errors = {},
}) => {
  const [formData, setFormData] = React.useState<{ [key: string]: string }>(
    initialValues || {}
  );

  React.useEffect(() => {
    setFormData(initialValues || {});
  }, [initialValues]);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
  ) => {
    const { id, value } = e.target;
    setFormData((prev) => ({ ...prev, [id]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit(formData);
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-5xl mx-auto bg-white p-6 rounded-3xl shadow-lg">
      <h2 className="text-2xl font-semibold text-gray-800 mb-4 text-center">{title}</h2>
      {fields.map((field) => (
        <div key={field.id} className="mb-4">
          <label htmlFor={field.id} className="block mb-2 font-bold text-gray-700">
            {field.label}
          </label>
          {field.type === "select" ? (
            <select
              id={field.id}
              className="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              onChange={handleChange}
              value={formData[field.id] || ""}
              required={field.required}
              disabled={field.disabled}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {field.options?.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </select>
          ) : field.type === "textarea" ? (
            <textarea
              id={field.id}
              className="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              onChange={handleChange}
              value={formData[field.id] || ""}
              placeholder={field.placeholder}
              required={field.required}
              disabled={field.disabled}
              rows={4}
            />
          ) : (
            <input
              type={field.type}
              id={field.id}
              className="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              onChange={handleChange}
              value={formData[field.id] || ""}
              placeholder={field.placeholder}
              required={field.required}
              disabled={field.disabled}
            />
          )}
          {errors[field.id] && (
            <p className="text-red-500 text-sm mt-1">{errors[field.id]}</p>
          )}
        </div>
      ))}
      {isError && (
        <div className="text-red-500 mt-4 mb-4 flex justify-center items-center">
          Error al procesar la solicitud.
        </div>
      )}
      {isSuccess && (
        <div className="text-green-500 mt-4 mb-4 flex justify-center items-center">
          Operación exitosa.
        </div>
      )}
      <div className="flex justify-center items-center mt-8 gap-4">
        <Button
          type="submit" // ✅ Agregar type="submit"
          text={submitButtonText}
          variant="success"
          className="w-1/2"
        />
        <Button
          type="button" // ✅ Agregar type="button"
          text={cancelButtonText}
          onClick={onCancel || (() => {})} // ✅ Función vacía por defecto
          variant="danger"
          className="w-1/2"
        />
      </div>
    </form>
  );
};

export default Formulario;