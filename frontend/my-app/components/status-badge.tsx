export function StatusBadge({ status }: { status: 'pending' | 'delivered' | 'not_delivered' }) {
  const classes = {
    pending: 'bg-amber-100 text-amber-700',
    delivered: 'bg-emerald-100 text-emerald-700',
    not_delivered: 'bg-rose-100 text-rose-700',
  };

  return <span className={`px-2 py-1 text-xs rounded-full ${classes[status]}`}>{status}</span>;
}
