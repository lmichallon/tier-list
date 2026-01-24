import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Tierlist de logos",
  description: "Projet final de Clean Architecture qui consiste en la création d'une application de tierlist de logos suivant le modèle hexagonal",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
        <body className={`${geistSans.variable} ${geistMono.variable} antialiased`}>
        {children}

            <footer className="bg-gray-900 text-gray-300 text-sm">
                <div className="max-w-6xl mx-auto px-4 py-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-center">
                    <span>
                      © {new Date().getFullYear()} – Projet Clean Architecture
                    </span>

                    <span className="text-gray-400">
                        M2 IW ESGI LYON : MICHALLON Lisa - LAI YIO LAI TONG Maxime - CAUVET Louis
                    </span>
                </div>
            </footer>
        </body>
    </html>
  );
}
