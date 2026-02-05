'use client';

import { useDraggable } from '@dnd-kit/core';
import { CSS } from '@dnd-kit/utilities';
import { TierKey, Logo } from '@/src/domain/tierlist';
import Image from "next/image";


/**
 * Composant UI représentant un logo draggable.
 * Ne contient aucune logique métier.
 */
type Props = {
    logo: Logo;
    tier: TierKey;
    disabled?: boolean;
};

export function DraggableLogo({ logo, tier, disabled = false }: Props) {
    // Hook dnd-kit pour rendre l’élément draggable
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        isDragging,
    } = useDraggable({
        id: logo.id,
        data: { tier, logo }, // permet d’identifier le tier source
        disabled,
    });

    // Style dynamique appliqué pendant le drag
    const style = {
        transform: CSS.Translate.toString(transform),
        opacity: isDragging ? 0 : 1,
    };

    return (
        <div
            ref={setNodeRef}
            {...listeners}
            {...attributes}
            style={style}
        >
            <Image
                src={logo.imageUrl}
                alt={logo.name}
                width={80}
                height={80}
                className={`shadow-sm hover:shadow-md ${disabled ? 'opacity-60 cursor-not-allowed' : 'cursor-grab active:cursor-grabbing'}`}
                title={logo.name}
            />
        </div>
    );
}
