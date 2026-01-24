export type TierKey = 'unranked' | 'S' | 'A' | 'B' | 'C' | 'D';

export type Logo = {
    id: string;
    name: string;
    imageUrl: string;
};

export type TierState = Record<TierKey, Logo[]>;

export const INITIAL_TIER_STATE: TierState = {
    unranked: [
        {
            id: 'logo-1',
            name: 'Logo 1',
            imageUrl: 'https://picsum.photos/100/100',
        },
        {
            id: 'logo-2',
            name: 'Logo 2',
            imageUrl: 'https://picsum.photos/100/101',
        },
        {
            id: 'logo-3',
            name: 'Logo 3',
            imageUrl: 'https://picsum.photos/100/102',
        },
        {
            id: 'logo-4',
            name: 'Logo 4',
            imageUrl: 'https://picsum.photos/100/103',
        },
        {
            id: 'logo-5',
            name: 'Logo 5',
            imageUrl: 'https://picsum.photos/100/104',
        },
        {
            id: 'logo-6',
            name: 'Logo 6',
            imageUrl: 'https://picsum.photos/100/105',
        },
        {
            id: 'logo-7',
            name: 'Logo 7',
            imageUrl: 'https://picsum.photos/100/106',
        },
        {
            id: 'logo-8',
            name: 'Logo 8',
            imageUrl: 'https://picsum.photos/100/107',
        },
        {
            id: 'logo-9',
            name: 'Logo 9',
            imageUrl: 'https://picsum.photos/100/108',
        },
        {
            id: 'logo-10',
            name: 'Logo 10',
            imageUrl: 'https://picsum.photos/100/109',
        },
    ],
    S: [],
    A: [],
    B: [],
    C: [],
    D: [],
};
