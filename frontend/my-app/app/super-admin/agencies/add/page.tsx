"use client";

import { useState } from "react";

export default function AddAgency() {
 const [form, setForm] = useState({
  name: "",
  contact_email: "",
  contact_number: "",
  whatsapp_number: "",
});
  return (
    <div className="max-w-lg">

      <h2 className="text-2xl font-bold text-white mb-6">
        Add Agency
      </h2>

      <div className="
        bg-white/5 
        backdrop-blur-md 
        border border-white/10 
        rounded-xl 
        p-6 
        space-y-4
      ">

        <input
          placeholder="Agency Name"
          value={form.name}
onChange={(e) =>
  setForm({ ...form, name: e.target.value })
}
          className="
            w-full 
            bg-white/10 
            border border-white/20 
            text-white 
            p-3 
            rounded-lg 
            outline-none 
            focus:border-yellow-400
          "
        />

        <input
          placeholder="Admin Email"
         value={form.contact_email}
onChange={(e) =>
  setForm({ ...form, contact_email: e.target.value })
}
          className="
            w-full 
            bg-white/10 
            border border-white/20 
            text-white 
            p-3 
            rounded-lg 
            outline-none 
            focus:border-yellow-400
          "
        />
   <input
          placeholder="Contact Number"
         value={form.contact_number}
onChange={(e) =>
  setForm({ ...form, contact_number: e.target.value })
}
          className="
            w-full 
            bg-white/10 
            border border-white/20 
            text-white 
            p-3 
            rounded-lg 
            outline-none 
            focus:border-yellow-400
          "
        />
           <input
          placeholder="Whatsapp Number"
         value={form.whatsapp_number}
onChange={(e) =>
  setForm({ ...form,whatsapp_number: e.target.value })
}
          className="
            w-full 
            bg-white/10 
            border border-white/20 
            text-white 
            p-3 
            rounded-lg 
            outline-none 
            focus:border-yellow-400
          "
        />
        <button className="
          w-full 
          bg-yellow-400 
          text-black 
          py-3 
          rounded-lg 
          font-semibold 
          hover:bg-yellow-500 
          transition
        ">
          Save Agency
        </button>

      </div>
    </div>
  );
}