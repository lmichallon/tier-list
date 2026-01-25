export type TierKey = 'unranked' | 'S' | 'A' | 'B' | 'C' | 'D';

export type Logo = {
    id: string;
    name: string;
    imageUrl: string;
};

export type TierState = Record<TierKey, Logo[]>;

/**
 * État initial vide.
 * Les logos seront chargés depuis l’API.
 */
export const EMPTY_TIER_STATE: TierState = {
    unranked: [],
    S: [],
    A: [],
    B: [],
    C: [],
    D: [],
};
