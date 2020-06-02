<?php

$routes = [];

$routes[] = [
  'GET',
  '/',
  [
    'controller' => 'MainController',
    'method' => 'home',
  ],
  'main-home'
];

// On crée une deuxième route pour retourner du json
// /lists` | GET | - | Récupération des données de toutes les listes |

$routes[] = [
    'GET',
    '/lists',
    [
      'controller' => 'ListController',
      'method' => 'all',
    ],
    'list-all'
  ];


// $this->router->map(
//       'GET',
//       '/',
//       [
//         'controller' => 'MainController',
//         'method' => 'home',
//       ],
//       'main-home'
//     );