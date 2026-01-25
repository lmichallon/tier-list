'use client';

import { ReactNode } from 'react';
import { AuthProvider } from '@/src/application/useAuth';

export function Providers({ children }: { children: ReactNode }) {
  return <AuthProvider>{children}</AuthProvider>;
}
