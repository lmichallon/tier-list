# Conclusion

## Synthese factuelle

- Backend organise en architecture hexagonale (Domain / Application / Infrastructure / Interface).
- Authentification JWT + refresh token en cookies HttpOnly, exposee via `/api/auth/*`.
- Logos exposes en lecture via `/api/logos`, avec URL logo.dev completee en provider.
- Tier list accessible via `/api/tierlists/me`, mise a jour via `/api/tierlists/move`.
- Generation PDF de statistiques via `/api/tierlists/pdf`, stockage dans Minio et retour d'une URL publique.
- Frontend Next.js consommant ces endpoints (hooks `useAuth` et `useTierList`).

## Limites observees dans le projet

- Pas de tests automatises ni de pipeline CI.
- L'operation d'ajout de logo existe en code (service + processor) mais n'est pas exposee en POST dans l'API.
- Le PDF genere est un recapitulatif statistique, pas un rendu pixel-perfect de l'UI.
