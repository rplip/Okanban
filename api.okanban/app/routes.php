<?php

$routes = [];

$routes[] = [
  'GET',
  '/',
  [
    'controller' => '\Okanban\Controllers\MainController',
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
      'controller' => '\Okanban\Controllers\ListController',
      'method' => 'all',
    ],
    'list-all'
  ];

  $routes[] = [
    'POST',
    '/lists/add',
    [
      'controller' => '\Okanban\Controllers\AddController',
      'method' => 'add',
    ],
    'list-add'
  ];

  $routes[] = [
    'POST',
    '/lists/update/[i:id]',
    [
      'controller' => '\Okanban\Controllers\ListController',
      'method' => 'updateList',
    ],
    'list-update'
  ];

  $routes[] = [
    'GET',
    '/cards',
    [
      'controller' => '\Okanban\Controllers\CardController',
      'method' => 'all',
    ],
    'card-all'
  ];

  $routes[] = [
    'GET',
    '/cards/delete/[i:id]',
    [
      'controller' => '\Okanban\Controllers\CardController',
      'method' => 'deleteCard',
    ],
    'card-delete'
  ];

  $routes[] = [
    'GET',
    '/test',
    [
      'controller' => '\Okanban\Controllers\MainController',
      'method' => 'test',
    ],
    'main-test'
  ];

  $routes[] = [
    'POST',
    '/cards/[i:id]/update',
    [
      'controller' => '\Okanban\Controllers\CardController',
      'method' => 'update',
    ],
    'card-update'
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