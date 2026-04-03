import './globals.css';
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Dew Route Product Delivery System',
  description: 'SaaS dashboard for milk and product delivery operations',
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
