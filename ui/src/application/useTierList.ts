'use client';

import { useState } from 'react';
import {
    DragStartEvent,
    DragEndEvent,
} from '@dnd-kit/core';

import {
    TierKey,
    TierState,
    Logo,
    INITIAL_TIER_STATE,
} from '@/src/domain/tierlist';

/**
 * Hook applicatif : gère l’état et les règles de la tierlist.
 * Aucune logique de rendu (clean separation UI / logique).
 */
export function useTierList() {
    // État global de la tierlist (logos répartis par tier)
    const [tiers, setTiers] = useState<TierState>(INITIAL_TIER_STATE);

    // Logo actuellement déplacé (utilisé par le DragOverlay)
    const [activeLogo, setActiveLogo] = useState<Logo | null>(null);

    // Déclenché au début du drag : mémorise le logo actif
    function handleDragStart(event: DragStartEvent) {
        setActiveLogo(event.active.data.current?.logo as Logo);
    }

    // Déclenché à la fin du drag : applique le déplacement si valide
    function handleDragEnd(event: DragEndEvent) {
        const { active, over } = event;

        // Fin du drag : on supprime l’overlay
        setActiveLogo(null);

        // En cas de drop hors d’une zone valide
        if (!over) return;

        // Tier source et tier cible
        const from = active.data.current?.tier as TierKey;
        const to = over.data.current?.tier as TierKey;

        // Logo déplacé
        const logo = active.data.current?.logo as Logo;

        // Aucune action si déplacement invalide ou inutile
        if (!from || !to || from === to) return;

        // Mise à jour immuable de la tierlist
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
        handleDragStart,
        handleDragEnd,
    };
}
