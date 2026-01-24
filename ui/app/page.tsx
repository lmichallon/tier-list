'use client';

import { DndContext, DragOverlay } from '@dnd-kit/core';
import { useTierList } from '@/src/application/useTierList';
import { TierSection } from '@/src/components/TierSection';
import Image from 'next/image';

const TIERS = [
    { key: 'S', label: "S : Les chefs-d'œuvre du branding", bg: 'bg-red-200' },
    { key: 'A', label: 'A : Très bons logos', bg: 'bg-yellow-200' },
    { key: 'B', label: 'B : Ça passe', bg: 'bg-green-200' },
    { key: 'C', label: 'C : Médiocres', bg: 'bg-blue-200' },
    { key: 'D', label: 'D : Les flops visuels', bg: 'bg-gray-200' },
];

export default function TierListPage() {
    const {
        tiers,
        activeLogo,
        handleDragStart,
        handleDragEnd,
    } = useTierList();

    return (
        <main className="max-w-5xl mx-auto px-4 py-6">
            {/* Title */}
            <h1 className="text-2xl font-bold text-center mb-10">
                Mon incroyable Tierlist de logos
            </h1>

            {/* Actions */}
            <div className="flex flex-col sm:flex-row justify-end gap-3 mb-6">
                <button className="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md cursor-pointer">
                    Télécharger les résultats globaux
                </button>
            </div>

            <DndContext
                onDragStart={handleDragStart}
                onDragEnd={handleDragEnd}
            >
                {/* Unranked */}
                <TierSection
                    tierKey="unranked"
                    label="Logos non classés"
                    bg="bg-gray-50 border border-dashed border-gray-300"
                    logos={tiers.unranked}
                />

                {/* Tiers */}
                {TIERS.map((tier) => (
                    <TierSection
                        key={tier.key}
                        tierKey={tier.key as any}
                        label={tier.label}
                        bg={tier.bg}
                        logos={tiers[tier.key as keyof typeof tiers]}
                    />
                ))}

                {/* Drag overlay */}
                <DragOverlay>
                    {activeLogo ? (
                        <div className="pointer-events-none">
                            <Image
                                src={activeLogo.imageUrl}
                                alt={activeLogo.name}
                                width={80}
                                height={80}
                                className="shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md"
                            />
                        </div>
                    ) : null}
                </DragOverlay>
            </DndContext>
        </main>
    );
}
