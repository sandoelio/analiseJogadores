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
            "{at} faz um crossover veloz e deixa {df} comendo poeira",
            "{at} leva a bola entre as pernas, confunde {df} e avança",
            "{at} troca de mãos por trás das costas e parte pra cesta",
            "{at} executa um spin move impecável e invade o garrafão",
        ],

        'finishes' => [
            "{at} sobe para a bandeja, mas desequilibra e arremessa torto",
            "{at} tenta enterrada cravada mas encontra {df} pronto para o bloqueio",
            "{at} emenda um jumper de média, bola na rede sem tocar aro",
            "{at} dispara um arremesso de 3 que sai limpo do colo do aro",
        ],

        'misses' => [
            "A bola explode no aro e sobra viva",
            "O ferro faz “timbre” e a redonda cai para quem estiver mais esperto",
            "Arremesso no plástico e ressalto decidido",
        ],

        'rebotes' => [
            "{df} salta e garante o rebote ofensivo",
            "{df} se antecipa, põe a mão na bola e inicia o contra-ataque",
            "{df} mergulha por trás do aro e arranca com a posse",
        ],

        'transicoes' => [
            "{df} avança em velocidade até a linha de 3 pontos",
            "{df} espera o espaço, recua e prepara o arremesso de longa distância",
            "{df} dribla de saída entre as pernas e testa a marcação de {at}",
        ],
    ],

];
