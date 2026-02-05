"use client";

import { useEffect, useState } from "react";
import { API_BASE_URL, useAuth } from "@/src/application/useAuth";

export function usePayment() {
  const { accessToken } = useAuth();
  const [isPaid, setIsPaid] = useState<boolean | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function loadStatus() {
      if (!accessToken) {
        setIsPaid(false);
        setLoading(false);
        return;
      }
      setLoading(true);
      try {
        const response = await fetch(`${API_BASE_URL}/payments/status`, {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
          credentials: "include",
        });

        if (!response.ok) {
          setIsPaid(false);
          return;
        }

        const data = await response.json();
        const paidFromMember = Array.isArray(data?.member)
          ? data.member[0]?.paid ?? data.member[0]
          : undefined;
        const paidFromHydra = Array.isArray(data?.["hydra:member"])
          ? data["hydra:member"][0]?.paid ?? data["hydra:member"][0]
          : undefined;

        const paid =
          typeof data?.paid === "boolean"
            ? data.paid
            : typeof paidFromMember === "boolean"
              ? paidFromMember
              : typeof paidFromHydra === "boolean"
                ? paidFromHydra
                : false;

        setIsPaid(paid);
      } catch (error) {
        console.error(error);
        setIsPaid(false);
      } finally {
        setLoading(false);
      }
    }

    loadStatus();
  }, [accessToken]);

  async function startCheckout() {
    if (!accessToken) return;

    const response = await fetch(`${API_BASE_URL}/payments/checkout`, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${accessToken}`,
      },
      credentials: "include",
    });

    if (!response.ok) {
      throw new Error("Failed to create checkout session");
    }

    const data = await response.json();
    if (data?.url) {
      window.location.href = data.url;
    }
  }

  return {
    isPaid,
    loading,
    startCheckout,
  };
}
