<?php

namespace App\Http\Controllers;


class PageController extends Controller
{
    protected $data = [
        [
            "name" => 'Luigi',
            "lastname" => 'Rossi',
        ],
        [
            "name" => 'Mario',
            "lastname" => 'Verdi',
        ],
        [
            "name" => 'Giovanni',
            "lastname" => 'Bianchi'
        ]
    ];

    public function about() {
        return view("about");
    }

    public function blog() {
        return view("blog", [
            'img_url' => 'https://picsum.photos/286/180',
            'img_title' => 'Immagine inclusa',
            'slot' => '',
        ]);
    }

    public function staff() {

        // Metodo 1 per passare dati alle view
        /*
        return view("staff", [
            "title" => "Our Staff", 
            "staff" => $this->data
        ]);
        */

        // Metodo 2
        /*
        return view("staff")
                ->with('staff', $this->data)
                ->with('title', 'Our Staff');
        */

        // Metodo 3
        /*
        return view("staff")
                ->withStaff($this->data) // Se segna errore è finto.
                ->withTitle('Our Staff');
        */

        // Metodo 4
        $staff = $this->data;
        $title = 'Our Staff';

        return view("staffb", compact('staff', 'title'));
    }
}
