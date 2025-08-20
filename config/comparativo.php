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
    | Templates de Jogadas, Rebotes, Transições e Finalizações
    |--------------------------------------------------------------------------
    */
    'templates' => [

        'dribles' => [
            // Início
            "{at} protege a bola e avança com calma",
            "{at} dribla no centro, observando {df}",
            "{at} faz um drible de proteção e aguarda a brecha",
            "{at} troca de mãos lentamente para enganar a defesa",

            // Meio
            "{at} puxa um crossover rápido e deixa {df} sem reação",
            "{at} executa um behind-the-back e ganha centímetros",
            "{at} usa um hesitation move, freia e parte de surpresa",
            "{at} aplica um euro step para driblar o marcador",

            // Fim
            "{at} aplica um drible desconcertante e quebra os tornozelos de {df}",
            "{at} gira no spin move e explode pra dentro do garrafão",
            "{at} executa um step-back fulminante e cria espaço",
            "{at} faz um in-and-out dribble e penetra com velocidade",
        ],

        'finishes' => [
            // Início
            "{at} tenta a bandeja simples e finaliza com toque suave",
            "{at} arrisca um arremesso de média distância",
            "{at} executa um floater sobre a cabeça de {df}",
            "{at} dá um tomahawk leve e cai dentro",

            // Meio
            "{at} infiltra com força e finaliza com estilo",
            "{at} mata um jumper no rosto de {df}",
            "{at} realiza um drop-step no poste e converte com força",
            "{at} executa um up-and-under e engana totalmente {df}",

            // Fim
            "{at} voa para a enterrada monstruosa!",
            "{at} acerta a bola no estouro do cronômetro!",
            "{at} solta um fadeaway em movimento e a bola cai limpa",
            "{at} faz um step-back three-point e converte de longe",
        ],

        'converts' => [
            // Frases exclusivas para quando a cesta converte de fato
            "{at} domina o aro e converte a cesta com estilo",
            "{at} empurra a bola no aro e a converte perfeitamente",
            "{at} finaliza com precisão cirúrgica e converte a cesta",
            "{at} converte o arremesso no meio da defesa de {df}",
            "{at} converte um arremesso complicado e celebra!",
            "{at} encaixa um jumper preciso e converte",
            "{at} enterra com autoridade e converte a cesta",
            "{at} afunda um arremesso de longe e converte sem medo",
        ],

        'misses' => [
            // Início
            "A bola beija o aro e sai",
            "O chute sai torto e não cai",
            "O toque é suave demais e a bola não encontra a cesta",
            "A bola quica no aro e explode pra fora",

            // Meio
            "O ferro canta e a bola voa longe",
            "Bate no aro e a posse ainda está viva!",
            "O lançamento sai pressionado e termina no aro",
            "A defesa de {df} atrapalha o arremesso e erra por pouco",

            // Fim
            "A torcida prende a respiração... mas a bola não cai!",
            "Explode no aro! Que oportunidade perdida!",
            "O chute em suspensão falha e aplica o silêncio na arena",
            "O arremesso bateu no topo do aro e não caiu",
        ],

        'rebotes' => [
            // Início
            "{df} pega o rebote com tranquilidade",
            "{df} garante a posse de bola",
            "{df} antecipa a rota e agarra o rebote",
            "{df} pula forte e domina o rebote ofensivo",

            // Meio
            "{df} sobe mais alto e domina o rebote",
            "{df} arranca a bola no alto e já olha pra frente",
            "{df} corta a trajetória do adversário e recupera o rebote",
            "{df} luta na garrafa e sai com a bola",

            // Fim
            "{df} arranca o rebote no meio da confusão!",
            "{df} conquista o rebote no grito!",
            "{df} sai com o rebote após embate físico",
            "{df} garante o rebote decisivo e inicia a transição",
        ],

        'transicoes' => [
            // Início
            "{df} avança com a bola devagar",
            "{df} organiza a jogada no ataque",
            "{df} corre a quadra em velocidade controlada",
            "{df} conduz a transição sem pressa",

            // Meio
            "{df} acelera pela lateral e busca espaço",
            "{df} explode no contra-ataque e invade o garrafão",
            "{df} engata o turbo e domina o contra-ataque",
            "{df} faz um pocket pass e surpreende a defesa",

            // Fim
            "{df} dispara na transição, quadra inteira vibrando!",
            "{df} quebra a marcação e parte decidido pra vitória!",
            "{df} executa uma assistência perfeita na correção da jogada",
            "{df} finaliza a transição com um passe flutuante para o aro",
        ],

    ],
];
