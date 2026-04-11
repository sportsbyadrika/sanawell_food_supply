"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { Pencil, Trash2, Plus } from "lucide-react";
import toast from "react-hot-toast";

type Agency = {
  id: number;
  name: string;
  contact_email: string;
  contact_number: string;
  whatsapp_number: string;
  status: string;
};

export default function AgenciesPage() {
  const router = useRouter();

  const [agencies, setAgencies] = useState<Agency[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [search, setSearch] = useState("");

  const [tempPassword, setTempPassword] = useState<string | null>(null);
  const [adminEmail, setAdminEmail] = useState<string | null>(null);

  const [form, setForm] = useState({
    name: "",
    contact_email: "",
    contact_number: "",
    whatsapp_number: "",
  });

  // ================= AUTH =================
  useEffect(() => {
    const token = localStorage.getItem("token");
    if (!token) router.push("/login");
    else fetchAgencies();
  }, []);

  // ================= FETCH =================
  const fetchAgencies = async () => {
    try {
      setLoading(true);
      const token = localStorage.getItem("token");

      const res = await fetch("http://localhost:4000/agencies", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const data = await res.json();

      if (Array.isArray(data)) setAgencies(data);
      else if (Array.isArray(data.data)) setAgencies(data.data);
      else setAgencies([]);
    } catch {
      toast.error("Failed to fetch agencies");
    } finally {
      setLoading(false);
    }
  };

  // ================= CREATE =================
  const handleCreate = async () => {
    try {
      const token = localStorage.getItem("token");

      const res = await fetch("http://localhost:4000/agencies", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(form),
      });

      const data = await res.json();
      if (!res.ok) throw new Error();

      setTempPassword(data?.tempPassword || null);
      setAdminEmail(data?.admin?.email || null);

      toast.success("Agency created 🎉");

      setForm({
        name: "",
        contact_email: "",
        contact_number: "",
        whatsapp_number: "",
      });

      setOpen(false);
      fetchAgencies();
    } catch {
      toast.error("Create failed");
    }
  };

  // ================= DELETE =================
  const handleDelete = async (id: number) => {
    if (!confirm("Delete this agency?")) return;

    try {
      const token = localStorage.getItem("token");

      const res = await fetch(
        `http://localhost:4000/agencies/${id}`,
        {
          method: "DELETE",
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      if (!res.ok) throw new Error();

      toast.success("Deleted successfully");
      fetchAgencies();
    } catch {
      toast.error("Delete failed");
    }
  };

  // ================= FILTER =================
  const filteredAgencies = agencies.filter((a) =>
    a.name.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="min-h-screen bg-gradient-to-br from-[#0f172a] to-[#020617] text-white p-6">

      {/* HEADER */}
      <div className="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <div>
          <h1 className="text-3xl font-bold">Agencies</h1>
          <p className="text-gray-400 text-sm">
            Manage all your agencies in one place
          </p>
        </div>

        <button
          onClick={() => setOpen(true)}
          className="bg-gradient-to-r from-blue-500 to-indigo-600 hover:scale-105 px-5 py-2 rounded-xl flex items-center gap-2 shadow-lg transition"
        >
          <Plus size={16} />
          Add Agency
        </button>
      </div>

      {/* STATS */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div className="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 rounded-xl shadow">
          <p className="text-sm text-gray-200">Total Agencies</p>
          <h2 className="text-2xl font-bold">{agencies.length}</h2>
        </div>

        <div className="bg-gradient-to-r from-green-600 to-emerald-600 p-4 rounded-xl shadow">
          <p className="text-sm text-gray-200">Active</p>
          <h2 className="text-2xl font-bold">
            {agencies.filter((a) => a.status === "active").length}
          </h2>
        </div>

        <div className="bg-gradient-to-r from-purple-600 to-pink-600 p-4 rounded-xl shadow">
          <p className="text-sm text-gray-200">New This Week</p>
          <h2 className="text-2xl font-bold">+12</h2>
        </div>
      </div>

      {/* TEMP PASSWORD */}
      {tempPassword && (
        <div className="bg-yellow-400 text-black p-4 rounded-lg mb-6 shadow">
          <h3 className="font-bold text-lg">Admin Created</h3>
          <p>Email: {adminEmail}</p>
          <p>Password: {tempPassword}</p>
        </div>
      )}

      {/* SEARCH */}
      <div className="mb-4">
        <input
          placeholder="Search agencies..."
          className="bg-gray-900 border border-gray-700 px-4 py-2 rounded-lg w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-blue-500"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
      </div>

      {/* TABLE */}
      <div className="bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-xl rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        {loading ? (
          <p className="p-6 text-center">Loading...</p>
        ) : filteredAgencies.length === 0 ? (
          <p className="p-6 text-center text-gray-400">
            No agencies found
          </p>
        ) : (
          <table className="w-full text-sm">
            <thead className="bg-white/10 text-gray-300">
              <tr>
                <th className="p-3">ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>WhatsApp</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              {filteredAgencies.map((a) => (
                <tr
                  key={a.id}
                  className="border-t border-white/10 hover:bg-blue-500/10 transition"
                >
                  <td className="p-3 text-center">{a.id}</td>
                  <td>{a.name}</td>
                  <td>{a.contact_email}</td>
                  <td>{a.contact_number}</td>
                  <td>{a.whatsapp_number}</td>

                  <td>
                    <span className="px-3 py-1 text-xs rounded-full bg-green-500/20 text-green-400 font-medium">
                      ● {a.status}
                    </span>
                  </td>

                  <td className="flex gap-2 justify-center py-3">
                    <button className="p-2 rounded-lg hover:bg-blue-500/20 transition">
                      <Pencil size={16} />
                    </button>

                    <button
                      onClick={() => handleDelete(a.id)}
                      className="p-2 rounded-lg hover:bg-red-500/20 text-red-400 transition"
                    >
                      <Trash2 size={16} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>

      {/* MODAL */}
      {open && (
        <div className="fixed inset-0 bg-black/60 flex justify-center items-center">
          <div className="bg-gray-900 p-6 rounded-xl w-[400px] space-y-4 shadow-xl">
            <h2 className="text-xl font-bold">Create Agency</h2>

            <input
              placeholder="Name"
              className="w-full p-2 rounded bg-gray-800 border border-gray-700"
              value={form.name}
              onChange={(e) =>
                setForm({ ...form, name: e.target.value })
              }
            />

            <input
              placeholder="Email"
              className="w-full p-2 rounded bg-gray-800 border border-gray-700"
              value={form.contact_email}
              onChange={(e) =>
                setForm({ ...form, contact_email: e.target.value })
              }
            />

            <input
              placeholder="Contact Number"
              className="w-full p-2 rounded bg-gray-800 border border-gray-700"
              value={form.contact_number}
              onChange={(e) =>
                setForm({ ...form, contact_number: e.target.value })
              }
            />

            <input
              placeholder="WhatsApp Number"
              className="w-full p-2 rounded bg-gray-800 border border-gray-700"
              value={form.whatsapp_number}
              onChange={(e) =>
                setForm({ ...form, whatsapp_number: e.target.value })
              }
            />

            <div className="flex justify-end gap-2 pt-2">
              <button
                onClick={() => setOpen(false)}
                className="px-4 py-2 bg-gray-700 rounded"
              >
                Cancel
              </button>

              <button
                onClick={handleCreate}
                className="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700"
              >
                Create
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}