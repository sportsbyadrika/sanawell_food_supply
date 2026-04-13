export default function UnauthorizedPage() {
  return (
    <div className="min-h-screen grid place-items-center bg-slate-950 text-slate-100 px-4">
      <div className="max-w-md rounded-2xl border border-white/10 bg-white/5 p-8 text-center">
        <h1 className="text-2xl font-semibold">Unauthorized</h1>
        <p className="mt-2 text-slate-300">You do not have access to this panel.</p>
      </div>
    </div>
  );
}
