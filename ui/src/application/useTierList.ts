"use client";

import { useEffect, useState } from "react";
import { DragStartEvent, DragEndEvent } from "@dnd-kit/core";
import { TierKey, TierState, Logo } from "@/src/domain/tierlist";
import { useAuth } from "@/src/application/useAuth";
import { API_BASE_URL } from "@/src/application/useAuth";

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

  const { accessToken } = useAuth();

  function normalizeTierList(tierList: Partial<TierState>): TierState {
    return {
      unranked: [],
      S: Array.isArray(tierList.S) ? tierList.S : [],
      A: Array.isArray(tierList.A) ? tierList.A : [],
      B: Array.isArray(tierList.B) ? tierList.B : [],
      C: Array.isArray(tierList.C) ? tierList.C : [],
      D: Array.isArray(tierList.D) ? tierList.D : [],
    };
  }

  useEffect(() => {
    async function loadTierList() {
      try {
        const response = await fetch(`${API_BASE_URL}/tierlists/me`, {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
          credentials: "include",
        });

        if (response.ok) {
          const data = await response.json();

          if (Array.isArray(data?.member) && data.member[0]) {
            const tierList = normalizeTierList(data.member[0]);

            const logosResponse = await fetch(`${API_BASE_URL}/logos`, {
              credentials: "include",
            });
            const logosData = await logosResponse.json();

            const allLogos: Logo[] = logosData.member.map((logo: any) => ({
              id: logo.id,
              name: logo.company,
              imageUrl: logo.imageURL,
            }));

            const logosById = new Map(allLogos.map((logo) => [logo.id, logo]));

            const hydrateTier = (tier: Logo[]) =>
              tier.map((logo) => logosById.get(logo.id) ?? logo);

            const rankedIds = new Set(
              [
                ...tierList.S,
                ...tierList.A,
                ...tierList.B,
                ...tierList.C,
                ...tierList.D,
              ].map((l) => l.id),
            );

            const unranked = allLogos.filter((logo) => !rankedIds.has(logo.id));

            setTiers({
              ...EMPTY_STATE,
              S: hydrateTier(tierList.S),
              A: hydrateTier(tierList.A),
              B: hydrateTier(tierList.B),
              C: hydrateTier(tierList.C),
              D: hydrateTier(tierList.D),
              unranked,
            });

            setLoading(false);
            return;
          }
        }

        // fallback : aucun classement encore
        const logosResponse = await fetch(`${API_BASE_URL}/logos`, {
          credentials: "include",
        });

        const logosData = await logosResponse.json();

        const logos: Logo[] = logosData.member.map((logo: any) => ({
          id: logo.id,
          name: logo.company,
          imageUrl: `https://img.logo.dev/${logo.domain}`,
        }));

        setTiers({
          ...EMPTY_STATE,
          unranked: logos,
        });
      } catch (e) {
        console.error(e);
      } finally {
        setLoading(false);
      }
    }

    if (!accessToken) return;
    loadTierList();
  }, [accessToken]);

  async function persistMove(logoId: string, tier: TierKey) {
    if (!accessToken) return;

    try {
      await fetch(`${API_BASE_URL}/tierlists/move`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${accessToken}`,
        },
        credentials: "include",
        body: JSON.stringify({
          logoId,
          tier,
        }),
      });
    } catch (error) {
      console.error("Failed to persist move", error);
    }
  }

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

    persistMove(logo.id, to);
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
