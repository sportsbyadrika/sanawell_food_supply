export function StatCards() {
  const stats = [
    { label: 'Products', value: '120' },
    { label: 'Staff', value: '24' },
    { label: 'Drivers', value: '12' },
    { label: 'Pending Deliveries', value: '36' },
  ];

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
      {stats.map((stat) => (
        <div key={stat.label} className="bg-white p-4 rounded-lg border">
          <p className="text-sm text-slate-500">{stat.label}</p>
          <p className="text-2xl font-semibold">{stat.value}</p>
        </div>
      ))}
    </div>
  );
}
