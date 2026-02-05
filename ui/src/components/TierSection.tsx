'use client';

import { useDroppable } from '@dnd-kit/core';
import { TierKey, Logo } from '@/src/domain/tierlist';
import { DraggableLogo } from './DraggableLogo';

/**
 * Section représentant un tier (zone droppable).
 */
type Props = {
    tierKey: TierKey;
    label: string;
    tierLetter: string;
    bgColor: string;
    logos: Logo[];
    disabled?: boolean;
};

export function TierSection({ tierKey, label, tierLetter, bgColor, logos, disabled = false }: Props) {
    const { setNodeRef, isOver } = useDroppable({
        id: tierKey,
        data: { tier: tierKey },
    });

    const isUnranked = tierKey === 'unranked';

    return (
        <section
            ref={setNodeRef}
            className={`
                relative rounded-2xl overflow-hidden transition-all duration-300
                ${isOver ? 'ring-2 ring-foreground/30 scale-[1.01]' : ''}
                ${isUnranked ? 'border-2 border-dashed border-border bg-muted/30' : 'border border-border bg-card shadow-sm'}
            `}
        >
            <div className="flex">
                {/* Tier Badge */}
                {!isUnranked && (
                    <div
                        className={`${bgColor} w-20 md:w-24 flex-shrink-0 flex items-center justify-center`}
                    >
                        <span className="text-3xl md:text-4xl font-black text-foreground/90">
                            {tierLetter}
                        </span>
                    </div>
                )}

                {/* Content Area */}
                <div className="flex-1 p-4">
                    <div className="flex items-center gap-2 mb-3">
                        {isUnranked && (
                            <div className="w-8 h-8 rounded-lg bg-muted flex items-center justify-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    className="text-muted-foreground"
                                >
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                </svg>
                            </div>
                        )}
                        <h2 className={`text-sm font-medium ${isUnranked ? 'text-muted-foreground' : 'text-foreground/70'}`}>
                            {label}
                        </h2>
                        <span className="ml-auto text-xs text-muted-foreground bg-muted px-2 py-0.5 rounded-full">
                            {logos.length} {logos.length > 1 ? 'logos' : 'logo'}
                        </span>
                    </div>

                    <div className={`flex flex-wrap gap-3 min-h-[100px] ${isUnranked ? 'min-h-[120px]' : ''} ${logos.length === 0 ? 'items-center justify-center' : ''}`}>
                        {logos.length === 0 ? (
                            <p className="text-sm text-muted-foreground/60 italic">
                                {isUnranked ? 'Tous les logos sont classés' : 'Glissez des logos ici'}
                            </p>
                        ) : (
                            logos.map((logo) => (
                                <DraggableLogo
                                    key={logo.id}
                                    logo={logo}
                                    tier={tierKey}
                                    disabled={disabled}
                                />
                            ))
                        )}
                    </div>
                </div>
            </div>
        </section>
    );
}
