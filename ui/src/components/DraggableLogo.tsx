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
};

export function DraggableLogo({ logo, tier }: Props) {
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
                className="shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md"
                title={logo.name}
            />
        </div>
    );
}
