@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('welcome.css') }}">

<div class="welcome-wrapper">

    <div class="welcome-card">

        <!-- Logo -->
        <div class="welcome-logo">

            <img src="{{ asset('imagem/logo.png') }}" alt="Cesta Baiana">

        </div>

        <!-- Título -->
        <div class="welcome-header">

            <h1>Análise de Desenvolvimento</h1>

            <h2>Evoluir também faz parte do jogo.</h2>

        </div>

        <!-- Introdução -->
        <div class="welcome-intro">

            <p>
                Todo atleta sonha em chegar mais longe. Mas para evoluir é preciso conhecer
                seus pontos fortes, identificar oportunidades de melhoria e acompanhar
                sua evolução ao longo do tempo.
            </p>

            <p>
                A <strong>Análise de Desenvolvimento</strong> do
                <strong>Cesta Baiana Basquete</strong>
                foi criada para oferecer uma avaliação individual, com indicadores que
                auxiliam no desenvolvimento técnico e esportivo de cada atleta.
            </p>

        </div>

        <!-- Cards -->
        <div class="welcome-grid">

            <div class="info-card">

                <div class="info-icon">

                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>

                </div>

                <div class="info-content">

                    <h3>Para o atleta e sua família</h3>

                    <p>
                        É uma oportunidade de compreender seu momento atual,
                        acompanhar sua evolução e direcionar melhor seus
                        treinos e objetivos.
                    </p>

                </div>

            </div>

            <div class="info-card">

                <div class="info-icon">

                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">

                        <path d="M3 10L12 4l9 6"/>
                        <path d="M5 10v8"/>
                        <path d="M9 10v8"/>
                        <path d="M15 10v8"/>
                        <path d="M19 10v8"/>
                        <path d="M2 18h20"/>

                    </svg>

                </div>

                <div class="info-content">

                    <h3>Para escolas, clubes e treinadores</h3>

                    <p>
                        É uma ferramenta de apoio para avaliar o desenvolvimento
                        dos atletas, identificar potenciais, acompanhar resultados
                        e contribuir para uma formação cada vez mais qualificada.
                    </p>

                </div>

            </div>

        </div>

        <!-- Missão -->

        <div class="welcome-mission">

            <p>

                Mais do que gerar números, nossa missão é transformar informações
                em desenvolvimento, fortalecendo a formação dos atletas e
                contribuindo para o crescimento do basquete baiano.

            </p>

        </div>

        <!-- Destaque -->

        <div class="welcome-highlight">

            <div class="quote">

                ❝

            </div>

            <div class="highlight-text">

                Porque o talento revela potencial.
                O desenvolvimento transforma potencial em oportunidades.
                🏀

            </div>

        </div>

        <div class="divider"></div>

        <div class="welcome-footer">

            <a href="{{ route('public.home') }}" class="btn-continuar">

                Continuar

                <span>→</span>

            </a>

        </div>

    </div>

</div>

@endsection