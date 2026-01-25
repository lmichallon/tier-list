'use client';

import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { FormEvent, useEffect, useState } from 'react';
import { useAuth } from '@/src/application/useAuth';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Eye, EyeOff, Trophy, Star, Crown, Sparkles } from 'lucide-react';

function TierBadge({ tier, className }: { tier: string; className?: string }) {
  const colors: Record<string, string> = {
    S: 'bg-amber-500 text-amber-950',
    A: 'bg-rose-500 text-rose-950',
    B: 'bg-orange-400 text-orange-950',
    C: 'bg-emerald-500 text-emerald-950',
    D: 'bg-sky-500 text-sky-950',
  };
  return (
    <span
      className={`inline-flex items-center justify-center w-10 h-10 rounded-lg font-bold text-lg ${colors[tier]} ${className}`}
    >
      {tier}
    </span>
  );
}

export default function LoginPage() {
  const { login, accessToken, isLoading } = useAuth();
  const router = useRouter();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  useEffect(() => {
    if (!isLoading && accessToken) {
      router.replace('/');
    }
  }, [accessToken, isLoading, router]);

  async function handleSubmit(event: FormEvent) {
    event.preventDefault();
    setError(null);
    setSubmitting(true);

    try {
      await login(email, password);
      router.replace('/');
    } catch (err) {
      setError('Email ou mot de passe incorrect.');
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <main className="min-h-screen flex flex-col lg:flex-row">
      {/* Left Panel - Branding */}
      <div className="hidden lg:flex lg:w-1/2 bg-foreground text-background relative overflow-hidden">
        {/* Background Pattern */}
        <div className="absolute inset-0 opacity-5">
          <div className="absolute top-20 left-10 w-32 h-32 rounded-full bg-background" />
          <div className="absolute top-40 right-20 w-48 h-48 rounded-full bg-background" />
          <div className="absolute bottom-32 left-1/4 w-24 h-24 rounded-full bg-background" />
          <div className="absolute bottom-20 right-10 w-40 h-40 rounded-full bg-background" />
        </div>

        <div className="relative z-10 flex flex-col justify-between p-12 w-full">
          {/* Logo */}
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-background text-foreground rounded-lg flex items-center justify-center">
              <Trophy className="w-6 h-6" />
            </div>
            <span className="text-2xl font-bold tracking-tight">Téel</span>
          </div>

          {/* Main Content */}
          <div className="space-y-8">
            <div className="space-y-4">
              <h1 className="text-5xl font-bold leading-tight text-balance">
                Classez, rangez,
                <br />
                partagez.
              </h1>
              <p className="text-lg text-background/70 max-w-md leading-relaxed">
                Créez des tier lists visuelles pour organiser et partager vos classements avec le monde entier.
              </p>
            </div>

            {/* Tier Preview */}
            <div className="flex items-center gap-3">
              <TierBadge tier="S" />
              <TierBadge tier="A" />
              <TierBadge tier="B" />
              <TierBadge tier="C" />
              <TierBadge tier="D" />
            </div>

            {/* Features */}
            <div className="grid grid-cols-2 gap-4 max-w-md">
              <div className="flex items-center gap-3 text-background/80">
                <Star className="w-5 h-5 text-amber-500" />
                <span className="text-sm">Classements illimités</span>
              </div>
              <div className="flex items-center gap-3 text-background/80">
                <Crown className="w-5 h-5 text-amber-500" />
                <span className="text-sm">Templates populaires</span>
              </div>
              <div className="flex items-center gap-3 text-background/80">
                <Sparkles className="w-5 h-5 text-amber-500" />
                <span className="text-sm">Partage facile</span>
              </div>
              <div className="flex items-center gap-3 text-background/80">
                <Trophy className="w-5 h-5 text-amber-500" />
                <span className="text-sm">Communauté active</span>
              </div>
            </div>
          </div>

          {/* Footer */}
          <p className="text-sm text-background/50">
            Rejoignez des milliers de créateurs de tier lists.
          </p>
        </div>
      </div>

      {/* Right Panel - Login Form */}
      <div className="flex-1 flex items-center justify-center p-6 sm:p-8 lg:p-12 bg-background">
        <div className="w-full max-w-md space-y-8">
          {/* Mobile Logo */}
          <div className="flex lg:hidden items-center justify-center gap-3 mb-8">
            <div className="w-10 h-10 bg-foreground text-background rounded-lg flex items-center justify-center">
              <Trophy className="w-6 h-6" />
            </div>
            <span className="text-2xl font-bold tracking-tight text-foreground">Téel</span>
          </div>

          {/* Header */}
          <div className="space-y-2">
            <h2 className="text-3xl font-bold tracking-tight text-foreground">
              Bon retour !
            </h2>
            <p className="text-muted-foreground">
              Connectez-vous pour accéder à vos tier lists
            </p>
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-5">
            <div className="space-y-2">
              <Label htmlFor="email" className="text-foreground font-medium">
                Email
              </Label>
              <Input
                id="email"
                type="email"
                required
                placeholder="vous@exemple.com"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                className="h-12 bg-secondary border-border focus:border-foreground focus:ring-foreground transition-colors"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="password" className="text-foreground font-medium">
                Mot de passe
              </Label>
              <div className="relative">
                <Input
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  required
                  minLength={8}
                  placeholder="Votre mot de passe"
                  value={password}
                  onChange={(event) => setPassword(event.target.value)}
                  className="h-12 bg-secondary border-border focus:border-foreground focus:ring-foreground transition-colors pr-12"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
                  aria-label={showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'}
                >
                  {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                </button>
              </div>
            </div>

            {error && (
              <div className="bg-destructive/10 border border-destructive/20 text-destructive rounded-lg p-3 text-sm">
                {error}
              </div>
            )}

            <Button
              type="submit"
              disabled={submitting}
              className="w-full h-12 bg-foreground text-background hover:bg-foreground/90 font-medium text-base transition-all"
            >
              {submitting ? (
                <span className="flex items-center gap-2">
                  <span className="w-4 h-4 border-2 border-background/30 border-t-background rounded-full animate-spin" />
                  Connexion...
                </span>
              ) : (
                'Se connecter'
              )}
            </Button>
          </form>

          {/* Divider */}
          <div className="relative">
            <div className="absolute inset-0 flex items-center">
              <div className="w-full border-t border-border" />
            </div>
            <div className="relative flex justify-center text-xs uppercase">
              <span className="bg-background px-3 text-muted-foreground">
                Nouveau sur Téel ?
              </span>
            </div>
          </div>

          {/* Register Link */}
          <Link
            href="/register"
            className="flex items-center justify-center w-full h-12 border border-border rounded-lg text-foreground font-medium hover:bg-secondary transition-colors"
          >
            Créer un compte
          </Link>

          {/* Mobile Tier Badges */}
          <div className="flex lg:hidden items-center justify-center gap-2 pt-4">
            <TierBadge tier="S" className="w-8 h-8 text-sm" />
            <TierBadge tier="A" className="w-8 h-8 text-sm" />
            <TierBadge tier="B" className="w-8 h-8 text-sm" />
            <TierBadge tier="C" className="w-8 h-8 text-sm" />
            <TierBadge tier="D" className="w-8 h-8 text-sm" />
          </div>
        </div>
      </div>
    </main>
  );
}
