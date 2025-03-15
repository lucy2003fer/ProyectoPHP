import { useUsuarios } from "../hooks/useUsuario";

const Usuarios = () => {
  const { data: usuarios, isLoading, error } = useUsuarios();

  if (isLoading) return <p className="text-gray-500">Cargando usuarios...</p>;
  if (error) return <p className="text-red-500">Error al cargar usuarios</p>;

  return (
    <div className="bg-white shadow-md rounded-lg p-4">
      <h2 className="text-xl font-bold mb-4">Usuarios</h2>
      <ul className="divide-y divide-gray-200">
        {(usuarios ?? []).map((user) => (
          <li key={user.identificacion} className="py-2">
            <p className="text-lg font-medium">{user.nombre}</p>
            <p className="text-gray-500">{user.email}</p>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default Usuarios;
