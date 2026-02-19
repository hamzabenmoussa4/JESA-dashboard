@extends('filament-panels::pages.auth.login')

{{-- Variante robuste : style inline (sans stack) pour s'assurer de l'application --}}

<style>
    html, body {
        height: 100%;
    }
    body {
        background: url('{{ asset('images/hero.jpg') }}') no-repeat center center fixed !important;
        background-size: cover !important;
        /* fallback couleur si image absente */
        background-color: #0b1220 !important;
    }

    /* Effet glass sur la carte de login (selon versions/themes) */
    .fi-auth-card .fi-card,
    .fi-section > div,
    .fi-simple main > div {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 16px;
    }
</style>
{{-- Affiche les boutons social du plugin --}}
{!! \Filament\Support\Facades\FilamentView::renderHook('filament.auth.login.form.after') !!}
{{-- @if (session('auth_error'))
  <div class="mb-4 text-sm text-red-500 font-semibold">
    {{ session('auth_error') }}
  </div>
@endif --}}

@section('content')
    @parent
@endsection
