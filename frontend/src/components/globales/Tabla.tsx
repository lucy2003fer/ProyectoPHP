import { useState, useEffect, useRef } from "react";
import { Pagination } from "@heroui/react";
import Button from "./Button"; // Asegúrate de que la ruta sea correcta

interface TablaProps<T> {
  title: string;
  headers: {
    key: string;
    label: string;
    render?: (row: T) => React.ReactNode;
  }[];
  data: T[];
  onClickAction: (row: T, action: string) => void;
  onCreate?: () => void; // 👈 Nueva prop para el botón de crear
  rowsPerPage?: number;
  searchFields?: string[];
  filters?: { key: string; label: string; options: string[] }[];
}

const Tabla = <T extends { [key: string]: any }>({
  title,
  headers,
  data,
  onClickAction,
  onCreate, // 👈 Nueva prop para el botón de crear
  rowsPerPage = 10,
  searchFields = [],
  filters = [],
}: TablaProps<T>) => {
  const [currentPage, setCurrentPage] = useState(1);
  const [filter, setFilter] = useState("");
  const [sortConfig, setSortConfig] = useState<{ key: string; direction: "asc" | "desc" } | null>(null);
  const [selectedRow, setSelectedRow] = useState<T | null>(null);
  const [selectedFilters, setSelectedFilters] = useState<{ [key: string]: string }>({});
  const menuRef = useRef<HTMLDivElement>(null);

  const filteredData = data.filter((row) => {
    const matchesSearch = searchFields.some((field) =>
      row[field]?.toString().toLowerCase().includes(filter.toLowerCase())
    );

    const matchesFilters = filters.every((filter) => {
      if (selectedFilters[filter.key]) {
        return row[filter.key] === selectedFilters[filter.key];
      }
      return true;
    });

    return matchesSearch && matchesFilters;
  });

  const sortedData = [...filteredData].sort((a, b) => {
    if (sortConfig !== null) {
      const valueA = a[sortConfig.key];
      const valueB = b[sortConfig.key];

      if (valueA < valueB) {
        return sortConfig.direction === "asc" ? -1 : 1;
      }
      if (valueA > valueB) {
        return sortConfig.direction === "asc" ? 1 : -1;
      }
    }
    return 0;
  });

  const paginatedData = sortedData.slice(
    (currentPage - 1) * rowsPerPage,
    currentPage * rowsPerPage
  );

  const handleSort = (key: string) => {
    let direction: "asc" | "desc" = "asc";
    if (sortConfig && sortConfig.key === key && sortConfig.direction === "asc") {
      direction = "desc";
    }
    setSortConfig({ key, direction });
  };

  const handleActionClick = (row: T, event: React.MouseEvent) => {
    event.stopPropagation();
    setSelectedRow(row);
  };

  const closeActionMenu = () => {
    setSelectedRow(null);
  };

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (menuRef.current && !menuRef.current.contains(event.target as Node)) {
        closeActionMenu();
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);
  
  return (
    <>
      <div className="flex justify-between items-center mb-8">
        <h2 className="text-4xl font-extrabold text-gray-900">{title}</h2>
        <div className="flex gap-6 items-center">
          <input
            type="text"
            placeholder={`Buscar por ${searchFields.join(", ")}...`}
            value={filter}
            onChange={(e) => setFilter(e.target.value)}
            className="border border-gray-300 rounded-xl p-3 w-72 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm"
          />
          {filters.map((filter) => (
            <select
              key={filter.key}
              value={selectedFilters[filter.key] || ""}
              onChange={(e) =>
                setSelectedFilters((prev) => ({
                  ...prev,
                  [filter.key]: e.target.value,
                }))
              }
              className="border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm"
            >
              <option value="">Todos los {filter.label}</option>
              {filter.options.map((option, index) => (
                <option key={index} value={option}>
                  {option}
                </option>
              ))}
            </select>
          ))}
          {onCreate && ( // 👈 Botón de crear usuario
            <Button
              text="Agregar"
              onClick={onCreate}
              variant="success"
              className="ml-4"
            />
          )}
        </div>
      </div>

      <div className="overflow-x-auto rounded-xl shadow-lg border border-gray-200">
        <table className="min-w-full bg-white">
          <thead className="bg-gradient-to-r from-green-600 to-green-800 text-white">
            <tr>
              {headers.map((header) => (
                <th
                  key={header.key}
                  className="px-8 py-5 text-sm font-semibold text-white uppercase cursor-pointer hover:bg-green-700 transition duration-300 border-b border-gray-200"
                  onClick={() => handleSort(header.key)}
                >
                  <div className="flex items-center space-x-2">
                    <span>{header.label}</span>
                    {sortConfig?.key === header.key && (
                      <span className="text-xs">
                        {sortConfig.direction === "asc" ? "▲" : "▼"}
                      </span>
                    )}
                  </div>
                </th>
              ))}
              <th className="px-8 py-5 text-sm font-semibold text-white uppercase border-b border-gray-200">Acción</th>
            </tr>
          </thead>
          <tbody>
            {paginatedData.map((row, index) => (
              <tr
                key={index}
                className={`${index % 2 === 0 ? "bg-gray-50" : "bg-white"
                  } hover:bg-green-50 transition duration-300 ease-in-out`}
              >
                {headers.map((header) => (
                  <td
                    key={header.key}
                    className="px-8 py-5 text-sm text-gray-700 border-b border-gray-200"
                  >
                    {header.render ? header.render(row) : row[header.key]}
                  </td>
                ))}
                <td className="px-8 py-5 text-center border-b border-gray-200 relative">
                  <button
                    onClick={(e) => handleActionClick(row, e)}
                    className="text-gray-500 hover:text-gray-700 focus:outline-none"
                  >
                    &#8942;
                  </button>
                  {selectedRow === row && (
                    <div
                      ref={menuRef}
                      className="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-xl z-10"
                    >
                      <button
                        className="block w-full px-6 py-3 text-sm text-gray-700 hover:bg-gray-100 text-left transition duration-300 ease-in-out"
                        onClick={() => {
                          onClickAction(row, "ver");
                          closeActionMenu();
                        }}
                      >
                        Ver detalles
                      </button>
                      <button
                        className="block w-full px-6 py-3 text-sm text-gray-700 hover:bg-gray-100 text-left transition duration-300 ease-in-out"
                        onClick={() => {
                          onClickAction(row, "editar");
                          closeActionMenu();
                        }}
                      >
                        Editar
                      </button>
                    </div>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div className="flex justify-center mt-8">
        <Pagination
          total={Math.ceil(sortedData.length / rowsPerPage)}
          page={currentPage}
          onChange={setCurrentPage}
        />
      </div>

    </>
  );
};

export default Tabla;