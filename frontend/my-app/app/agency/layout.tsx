import Sidebar from './components/Sidebar';
import Header from './components/Header';

export default function AgencyLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="min-h-screen bg-slate-950">
      <div className="mx-auto flex max-w-[1600px]">
        <Sidebar />
        <main className="flex-1 p-4 md:p-6">
          <Header />
          {children}
        </main>
      </div>
    </div>
  );
}
