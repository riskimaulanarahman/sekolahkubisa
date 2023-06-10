<?php

return [

  'menu' => [
        [
            'icon' => 'fa fa-home',
            'title' => 'Dashboard',
            'url' => '/',
            'route-name' => 'dashboard'
        ],
        [
            'icon' => 'fa fa-book',
            'title' => 'Materi',
            'url' => '/materi',
            'route-name' => 'materi.index'
        ],
        [
            'icon' => 'fa fa-book',
            'title' => 'Video Puisi',
            'url' => '/videopuisi',
            'route-name' => 'videopuisi.index'
        ],
        [
            'icon' => 'fa fa-book',
            'title' => 'Biodata',
            'url' => '/biodata',
            'route-name' => 'biodata.index'
        ],
        // [
        //     'icon' => 'fa fa-users',
        //     'title' => 'Kelola User',
        //     'url' => '/master-user',
        //     'route-name' => 'admin.masteruser'
        // ],
    ]
];
