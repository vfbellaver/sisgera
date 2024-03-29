<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\Sisgera\Models\Requerimento::class, function (Faker $faker) {

    $status = ['Enviado','Em andamento','Deferido','Indeferido'];

    $cursos = [
                ['nome' => 'Tecnologia em Sistemas para Internet','turno' => 'Noturno'],
                ['nome' => 'Tecnologia em Alimentos','turno' => 'Noturno'],
                ['nome' => 'Quimica','turno' => 'Noturno'],
                ['nome' => 'Engenharia de Pesca','turno' => 'Integral'],
            ];

    $curso = $cursos[rand(0, 3)];
    return [
        'data_criacao' =>\Carbon\Carbon::now()->format('Y-m-d H:m:s'),
        'protocolo' => $faker->swiftBicNumber,
        'justificativa' => $faker->text($maxNbChars = 600),
        'situacao' => $status[rand(0, 3)],
        'curso' => $curso['nome'],
        'turno' => $curso['turno'],
        'periodo' => $faker->numberBetween(0,9),
        'turma' => '1000',
        'data_fechamento' => 'situacao'== $status[1] ?  \Carbon\Carbon::now()->addMonths(3)->format('Y-m-d d-m-Y H:m:s') : null,


    ];
});
