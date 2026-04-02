import { StatusBadge } from './status-badge';

const rows = [
  { customer: 'A-One Mart', route: 'R-12', status: 'pending' },
  { customer: 'Fresh Dairy', route: 'R-02', status: 'delivered' },
  { customer: 'Central Shop', route: 'R-03', status: 'not_delivered' },
];

export function DataTable() {
  return (
    <div className="bg-white border rounded-lg overflow-hidden mt-6">
      <table className="w-full text-sm">
        <thead className="bg-slate-100 text-left">
          <tr>
            <th className="p-3">Customer</th>
            <th className="p-3">Route</th>
            <th className="p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          {rows.map((row) => (
            <tr key={row.customer} className="border-t">
              <td className="p-3">{row.customer}</td>
              <td className="p-3">{row.route}</td>
              <td className="p-3"><StatusBadge status={row.status as 'pending' | 'delivered' | 'not_delivered'} /></td>
            </tr>
          ))}
        </tbody>
      </table>
      <div className="p-3 text-xs text-slate-500 border-t">Page 1 of 5</div>
    </div>
  );
}
