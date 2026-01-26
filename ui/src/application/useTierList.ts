'use client';

import { useEffect, useState } from 'react';
import { DragStartEvent, DragEndEvent } from '@dnd-kit/core';
import { TierKey, TierState, Logo } from '@/src/domain/tierlist';
import { API_BASE_URL } from '@/src/application/useAuth';

/**
 * Hook applicatif : gère l’état et les règles de la tierlist.
 * Aucune logique de rendu (clean separation UI / logique).
 */

const EMPTY_STATE: TierState = {
    unranked: [],
    S: [],
    A: [],
    B: [],
    C: [],
    D: [],
};

export function useTierList() {
    // État global de la tierlist (logos répartis par tier)
    const [tiers, setTiers] = useState<TierState>(EMPTY_STATE);

    // Logo actuellement déplacé (utilisé par le DragOverlay)
    const [activeLogo, setActiveLogo] = useState<Logo | null>(null);

    const [loading, setLoading] = useState(true);

    /** Charge les logos depuis l’API */
    useEffect(() => {
        async function loadLogos() {
            try {
                const response = await fetch(`${API_BASE_URL}/logos`, {
                    credentials: 'include',
                });

                if (!response.ok) {
                    throw new Error('Failed to load logos');
                }

                const data = await response.json();

                if (!Array.isArray(data.member)) {
                    throw new Error('Invalid logos payload');
                }

                const logos: Logo[] = data.member.map((logo: any) => ({
                    id: logo.id,
                    name: logo.company,
                    imageUrl: logo.imageURL,
                }));

                setTiers({
                    ...EMPTY_STATE,
                    unranked: logos,
                });
            } catch (error) {
                console.error(error);
            } finally {
                setLoading(false);
            }
        }

        loadLogos();
    }, []);

    // Déclenché au début du drag : mémorise le logo actif
    function handleDragStart(event: DragStartEvent) {
        setActiveLogo(event.active.data.current?.logo as Logo);
    }

    // Déclenché à la fin du drag : applique le déplacement si valide
    function handleDragEnd(event: DragEndEvent) {
        const { active, over } = event;
        setActiveLogo(null);

        if (!over) return;

        const from = active.data.current?.tier as TierKey;
        const to = over.data.current?.tier as TierKey;
        const logo = active.data.current?.logo as Logo;

        if (!from || !to || from === to) return;

        setTiers((prev) => ({
            ...prev,
            [from]: prev[from].filter((l) => l.id !== logo.id),
            [to]: [...prev[to], logo],
        }));
    }

    // API exposée à la couche UI
    return {
        tiers,
        activeLogo,
        loading,
        handleDragStart,
        handleDragEnd,
    };
}
