import { useState, useCallback } from 'react';
import { useRol } from '../../hooks/rol/useRol'
import Tabla from '../globales/Tabla';
import VentanaModal from '../globales/VentanasModales';

const Rol = () => {
  const { data: rol, isLoading, error } = useRol();
  const [selectedUser, setSelectedUser] = useState<Record<string, any> | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  // Abrir modal con un usuario seleccionado
  const openModalHandler = useCallback((rol: Record<string, any>, action: string) => {
    setSelectedUser(rol);
    if (action === "ver") {
      setIsModalOpen(true); // Abrir modal solo para "Ver detalles"
    } else if (action === "editar") {
      console.log("Editar rol:", rol); // Lógica para editar
    }
  }, []);

  // Cerrar modal
  const closeModal = useCallback(() => {
    setSelectedUser(null);
    setIsModalOpen(false);
  }, []);

  // Encabezados de la tabla
  const headers = [
    { key: 'id_rol', label: 'ID' },
    { key: 'nombre_rol', label: 'Rol' },
    { key: 'fecha_creacion', label: 'Creación' }
  ];

  return (
    <div className="overflow-x-auto bg-white shadow-md rounded-lg p-4">
      {/* Estado de carga */}
      {isLoading && <div className="text-center text-gray-500">Cargando Roles...</div>}

      {/* Errores */}
      {error instanceof Error && (
        <div className="text-center text-red-500">
          Error al cargar los rol: {error.message}
        </div>
      )}

      {/* Sin datos */}
      {!isLoading && !error && (!Array.isArray(rol) || rol.length === 0) && (
        <div className="text-center text-gray-500">No hay rol registrados.</div>
      )}

      {/* Tabla de rol */}
      {Array.isArray(rol) && rol.length > 0 && (
        <Tabla
          title="Lista de rol"
          headers={headers}
          data={rol.map(rol => ({
            id_rol: rol.id_rol,
            nombre_rol: rol.nombre_rol,
            fecha_creacion: rol.fecha_creacion
          }))}
          onClickAction={openModalHandler} // 👈 Pasar la función de acciones
          searchFields={["nombre_rol"]} // Buscar por nombre o identificación
          filters={[
            {
              key: "nombre_rol",
              label: "Rol",
              options: ["pasante", "instructor", "invitado"], // Opciones de filtro
            },
          ]}
        />
      )}

      {/* Modal de usuario */}
      {selectedUser && (
        <VentanaModal 
          isOpen={isModalOpen} 
          onClose={closeModal} 
          titulo="Detalles del rol" 
          contenido={selectedUser} 
        />
      )}
    </div>
  );
};

export default Rol;