'use client';

import { DndContext, DragOverlay } from '@dnd-kit/core';
import { useTierList } from '@/src/application/useTierList';
import { API_BASE_URL, useAuth } from '@/src/application/useAuth';
import { TierSection } from '@/src/components/TierSection';
import { Footer } from '@/src/components/Footer';
import { Button } from '@/components/ui/button';
import Image from 'next/image';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { Download, LogOut, Trophy, Loader2 } from 'lucide-react';

const TIERS = [
    { key: 'S', label: "Les chefs-d'oeuvre du branding", letter: 'S', bgColor: 'bg-red-400', pdfColor: '#f87171' },
    { key: 'A', label: 'Très bons logos', letter: 'A', bgColor: 'bg-orange-400', pdfColor: '#fb923c' },
    { key: 'B', label: 'Ça passe', letter: 'B', bgColor: 'bg-yellow-400', pdfColor: '#facc15' },
    { key: 'C', label: 'Médiocres', letter: 'C', bgColor: 'bg-green-400', pdfColor: '#4ade80' },
    { key: 'D', label: 'Les flops visuels', letter: 'D', bgColor: 'bg-blue-400', pdfColor: '#60a5fa' },
];

function getFilename(contentDisposition: string | null): string | null {
    if (!contentDisposition) return null;

    const match = /filename=\"([^\"]+)\"/.exec(contentDisposition);
    if (match && match[1]) {
        return match[1];
    }

    return null;
}

export default function TierListPage() {
    const {
        tiers,
        activeLogo,
        handleDragStart,
        handleDragEnd,
    } = useTierList();
    const { accessToken, isLoading, logout } = useAuth();
    const router = useRouter();
    const [isDownloading, setIsDownloading] = useState(false);

    const handleDownload = async () => {
        if (!accessToken || isDownloading) return;

        const snapshot = {
            title: 'Mon incroyable Tierlist de logos',
            playerDate: new Date().toLocaleDateString('fr-FR'),
            tiers: [
                {
                    key: 'unranked',
                    label: 'Logos non classés',
                    letter: '',
                    color: '#e2e8f0',
                    logos: tiers.unranked.map((logo) => ({
                        id: logo.id,
                        name: logo.name,
                        imageUrl: logo.imageUrl,
                    })),
                },
                ...TIERS.map((tier) => ({
                    key: tier.key,
                    label: tier.label,
                    letter: tier.letter,
                    color: tier.pdfColor,
                    logos: tiers[tier.key as keyof typeof tiers].map((logo) => ({
                        id: logo.id,
                        name: logo.name,
                        imageUrl: logo.imageUrl,
                    })),
                })),
            ],
        };

        setIsDownloading(true);
        try {
            const response = await fetch(`${API_BASE_URL}/tierlists/pdf`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${accessToken}`,
                },
                credentials: 'include',
                body: JSON.stringify(snapshot),
            });

            if (!response.ok) {
                throw new Error('PDF generation failed.');
            }

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);
            const filename = getFilename(response.headers.get('Content-Disposition')) ?? 'tierlist.pdf';

            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.click();
            URL.revokeObjectURL(url);
        } catch (error) {
            console.error(error);
        } finally {
            setIsDownloading(false);
        }
    };

    useEffect(() => {
        if (!isLoading && !accessToken) {
            router.replace('/login');
        }
    }, [accessToken, isLoading, router]);

    if (isLoading || !accessToken) {
        return (
            <div className="min-h-screen bg-background flex items-center justify-center">
                <div className="flex flex-col items-center gap-4">
                    <Loader2 className="h-8 w-8 animate-spin text-muted-foreground" />
                    <p className="text-muted-foreground">Chargement...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-background">
            {/* Header */}
            <header className="sticky top-0 z-50 border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        {/* Logo */}
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl bg-foreground flex items-center justify-center">
                                <Trophy className="h-5 w-5 text-background" />
                            </div>
                            <span className="text-xl font-bold tracking-tight">Téel</span>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center gap-3">
                            <Button
                                variant="outline"
                                className="gap-2 bg-transparent"
                                onClick={handleDownload}
                                disabled={isDownloading}
                            >
                                {isDownloading ? (
                                    <Loader2 className="h-4 w-4 animate-spin" />
                                ) : (
                                    <Download className="h-4 w-4" />
                                )}
                                <span className="hidden sm:inline">Télécharger les résultats</span>
                            </Button>
                            <Button variant="ghost" onClick={logout} className="gap-2">
                                <LogOut className="h-4 w-4" />
                                <span className="hidden sm:inline">Déconnexion</span>
                            </Button>
                        </div>
                    </div>
                </div>
            </header>

            {/* Main Content */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Page Title */}
                <div className="text-center mb-10">
                    <h1 className="text-3xl md:text-4xl font-bold tracking-tight text-balance">
                        Mon incroyable Tierlist de logos
                    </h1>
                    <p className="mt-2 text-muted-foreground">
                        Glissez et déposez les logos pour créer votre classement
                    </p>
                </div>

                <DndContext
                    onDragStart={handleDragStart}
                    onDragEnd={handleDragEnd}
                >
                    {/* Unranked Logos */}
                    <div className="mb-8">
                        <TierSection
                            tierKey="unranked"
                            label="Logos non classés"
                            tierLetter=""
                            bgColor=""
                            logos={tiers.unranked}
                        />
                    </div>

                    {/* Tier List */}
                    <div className="space-y-3">
                        {TIERS.map((tier) => (
                            <TierSection
                                key={tier.key}
                                tierKey={tier.key as any}
                                label={tier.label}
                                tierLetter={tier.letter}
                                bgColor={tier.bgColor}
                                logos={tiers[tier.key as keyof typeof tiers]}
                            />
                        ))}
                    </div>

                    {/* Drag Overlay */}
                    <DragOverlay>
                        {activeLogo ? (
                            <div className="pointer-events-none">
                                <div className="rounded-xl bg-card border-2 border-foreground shadow-2xl p-2 scale-110">
                                    <Image
                                        src={activeLogo.imageUrl || "/placeholder.svg"}
                                        alt={activeLogo.name}
                                        width={80}
                                        height={80}
                                        className="object-contain"
                                    />
                                </div>
                            </div>
                        ) : null}
                    </DragOverlay>
                </DndContext>
            </main>

            <Footer />
        </div>
    );
}
