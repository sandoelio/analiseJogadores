<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Número de eventos no duelo
    |--------------------------------------------------------------------------
    */
    'eventos' => 10,

    /*
    |--------------------------------------------------------------------------
    | Campos de Estatísticas
    |--------------------------------------------------------------------------
    */
    'campos' => [
        'arremesso',
        'passe',
        'marcacao',
        'finalizacao',
        'jogada',
        'dominio',
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates de Jogadas, Rebotes e Transições
    |--------------------------------------------------------------------------
    */
    'templates' => [
        'dribles' => [
            // Início
            "{at} protege a bola e avança com calma",
            "{at} dribla no centro, observando {df}",

            // Meio
            "{at} puxa um crossover e acelera pra cima de {df}",
            "{at} troca de mãos e deixa {df} na saudade",

            // Fim
            "{at} aplica um drible desconcertante e quebra os tornozelos de {df}",
            "{at} gira no spin move e explode pra dentro do garrafão",
        ],

        'finishes' => [
            // Início
            "{at} tenta a bandeja simples",
            "{at} arrisca um arremesso de média distância",

            // Meio
            "{at} infiltra com força e finaliza com estilo",
            "{at} mata um jumper no rosto de {df}",

            // Fim
            "{at} voa para a enterrada monstruosa!",
            "{at} acerta a bola no estouro do cronômetro!",
        ],

        'misses' => [
            // Início
            "A bola beija o aro e sai",
            "O chute sai torto e não cai",

            // Meio
            "O ferro canta e a bola voa longe",
            "Bate no aro e a posse ainda está viva!",

            // Fim
            "A torcida prende a respiração... mas a bola não cai!",
            "Explode no aro! Que oportunidade perdida!",
        ],

        'rebotes' => [
            // Início
            "{df} pega o rebote com tranquilidade",
            "{df} garante a posse de bola",

            // Meio
            "{df} sobe mais alto e domina o rebote",
            "{df} arranca a bola no alto e já olha pra frente",

            // Fim
            "{df} arranca o rebote no meio da confusão!",
            "{df} conquista o rebote no grito!",
        ],

        'transicoes' => [
            // Início
            "{df} avança com a bola devagar",
            "{df} organiza a jogada no ataque",

            // Meio
            "{df} acelera pela lateral e busca espaço",
            "{df} explode no contra-ataque e invade o garrafão",

            // Fim
            "{df} dispara na transição, quadra inteira vibrando!",
            "{df} quebra a marcação e parte decidido pra vitória!",
        ],
    ],
];
