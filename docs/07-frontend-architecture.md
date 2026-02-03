# Frontend (Next.js)

## Organisation

```
ui/
  app/
    page.tsx
    (auth)/login/page.tsx
    (auth)/register/page.tsx
  src/
    application/
      useAuth.tsx
      useTierList.ts
    domain/
      tierlist.ts
    components/
      TierSection.tsx
      DraggableLogo.tsx
      Footer.tsx
```

## Points clés

- **useAuth** gère login/register/refresh/logout et stocke l’access token en mémoire.
- **useTierList** charge la tier list utilisateur et persiste les mouvements.
- **page.tsx** orchestre le drag & drop et le téléchargement du PDF de statistiques.

## Extraits de code (commentés)

### Chargement de la tier list

```ts
// ui/src/application/useTierList.ts
const response = await fetch(`${API_BASE_URL}/tierlists/me`, {
  headers: { Authorization: `Bearer ${accessToken}` },
  credentials: "include",
});
```

**Commentaire :** la tier list est liée à l’utilisateur authentifié.

### Persistance d’un déplacement

```ts
await fetch(`${API_BASE_URL}/tierlists/move`, {
  method: "POST",
  headers: { "Content-Type": "application/json", Authorization: `Bearer ${accessToken}` },
  credentials: "include",
  body: JSON.stringify({ logoId, tier }),
});
```

**Commentaire :** chaque déplacement est immédiatement enregistré côté backend.

### Téléchargement du PDF

```ts
const response = await fetch(`${API_BASE_URL}/tierlists/pdf`, {
  method: "POST",
  headers: { Authorization: `Bearer ${accessToken}` },
  credentials: "include",
});

const data = await response.json();
const pdfResponse = await fetch(data.url);
```

**Commentaire :** l’API renvoie une URL Minio ; le front télécharge ensuite le PDF.

