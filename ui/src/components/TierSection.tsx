'use client';

import { useDroppable } from '@dnd-kit/core';
import { TierKey, Logo } from '@/src/domain/tierlist';
import { DraggableLogo } from './DraggableLogo';

/**
 * Section représentant un tier (zone droppable).
 * Se contente d’afficher les logos et d’accepter les drops.
 */
type Props = {
    tierKey: TierKey;
    label: string;
    bg: string;
    logos: Logo[];
};

export function TierSection({ tierKey, label, bg, logos }: Props) {
    // Hook dnd-kit pour définir la zone comme cible de drop
    const { setNodeRef } = useDroppable({
        id: tierKey,
        data: { tier: tierKey },  // permet d’identifier le tier cible
    });

    return (
        <section ref={setNodeRef} className={`${bg} rounded-md p-4 mb-4`}>
            <h2 className="font-semibold mb-3">{label}</h2>

            <div className="flex flex-wrap gap-2 min-h-[40px]">
                {logos.map((logo) => (
                    <DraggableLogo
                        key={logo.id}
                        logo={logo}
                        tier={tierKey}
                    />
                ))}
            </div>
        </section>
    );
}
