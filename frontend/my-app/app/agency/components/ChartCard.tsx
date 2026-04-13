export default function ChartCard({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <section className="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-xl">
      <h3 className="mb-4 text-sm font-medium text-slate-300">{title}</h3>
      <div className="h-72 w-full">{children}</div>
    </section>
  );
}
