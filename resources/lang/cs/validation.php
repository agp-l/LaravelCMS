<?php

return [
    'required' => 'Toto pole je povinné.',
    'email' => 'Zadej platnou e-mailovou adresu.',
    'unique' => 'Tento :attribute již existuje.',
    'confirmed' => 'Potvrzení hesla nesouhlasí.',
    'min' => [
        'string' => 'Pole musí mít alespoň :min znaků.',
        'numeric' => 'Hodnota musí být alespoň :min.',
    ],
    'max' => [
        'string' => 'Pole nesmí být delší než :max znaků.',
    ],
    'between' => [
        'string' => 'Hodnota musí být mezi :min a :max znaky.',
    ],
    'same' => 'Pole :attribute a :other se musí shodovat.',
    'exists' => 'Vybraný :attribute neexistuje.',
    'attributes' => [
        'name' => 'jméno',
        'email' => 'e-mail',
        'password' => 'heslo',
        'password_confirmation' => 'potvrzení hesla',
        'token' => 'token',
    ],
];
