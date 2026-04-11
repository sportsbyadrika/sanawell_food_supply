"use client";

import { Building2, CheckCircle, XCircle } from "lucide-react";

import { useRouter } from "next/navigation";
import { useEffect } from "react";

export default function Dashboard() {
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem("token");

    if (!token) {
      router.push("/login");
    }
  }, []);
  return (
    <div>

      <h2 className="text-3xl font-bold mb-2">
        Dashboard Overview
      </h2>

      <p className="text-gray-400 mb-8">
        Manage agencies and monitor system activity.
      </p>

      {/* Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">

        <Card
          title="Total Agencies"
          value="10"
          description="All registered agencies"
          icon={<Building2 />}
        />

        <Card
          title="Active Agencies"
          value="8"
          description="Currently active"
          icon={<CheckCircle />}
        />

        <Card
          title="Inactive Agencies"
          value="2"
          description="Temporarily inactive"
          icon={<XCircle />}
        />

      </div>
    </div>
  );
  type CardProps = {
  title: string;
  value: string;
  description: string;
  icon: React.ReactNode;
};

function Card({ title, value, description, icon }: CardProps) {
  return (
    <div className="
      bg-white/5 
      backdrop-blur-md 
      border border-white/10
      rounded-xl 
      p-6 
      shadow-lg 
      hover:scale-[1.02] 
      transition
    ">

      {/* Icon */}
      <div className="w-12 h-12 flex items-center justify-center bg-yellow-400/20 text-yellow-400 rounded-lg">
        {icon}
      </div>

      {/* Content */}
      <h3 className="mt-4 text-gray-300">
        {title}
      </h3>

      <h2 className="text-3xl font-bold text-white">
        {value}
      </h2>

      <p className="text-sm text-gray-500 mt-1">
        {description}
      </p>
    </div>
  );
}
}