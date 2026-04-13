import { ArrowUpDown } from 'lucide-react';

export interface Column<T> {
  key: keyof T;
  label: string;
  sortable?: boolean;
  render?: (value: T[keyof T], row: T) => React.ReactNode;
}

interface DataTableProps<T extends Record<string, unknown>> {
  columns: Column<T>[];
  data: T[];
  sortBy?: keyof T;
  sortDirection?: 'asc' | 'desc';
  onSort?: (key: keyof T) => void;
}

export default function DataTable<T extends Record<string, unknown>>({
  columns,
  data,
  sortBy,
  sortDirection,
  onSort,
}: DataTableProps<T>) {
  return (
    <div className="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
      <div className="overflow-x-auto">
        <table className="min-w-full text-sm">
          <thead className="bg-white/5 text-left text-slate-300">
            <tr>
              {columns.map((column) => (
                <th key={String(column.key)} className="px-4 py-3 font-medium">
                  <button
                    type="button"
                    onClick={() => column.sortable && onSort?.(column.key)}
                    className="inline-flex items-center gap-1"
                  >
                    {column.label}
                    {column.sortable && <ArrowUpDown size={14} className="text-slate-400" />}
                    {sortBy === column.key && <span className="text-xs">{sortDirection === 'asc' ? '↑' : '↓'}</span>}
                  </button>
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {data.map((row, idx) => (
              <tr key={idx} className="border-t border-white/5 text-slate-200 hover:bg-white/5">
                {columns.map((column) => (
                  <td key={String(column.key)} className="px-4 py-3">
                    {column.render ? column.render(row[column.key], row) : (row[column.key] as React.ReactNode)}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
