<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {

        $data = [
            'title' => 'Home | WebProgramming UNPAS'
        ];
        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Me'
        ];
        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'alamat' => [
                [
                    'tipe' => 'Rumah',
                    'alamat' => 'Jl. Purwakarta',
                    'kota' => 'Purwakarta'
                ],
                [
                    'tipe' => 'Kantor',
                    'alamat' => 'Jl. Jakarta',
                    'kota' => 'Jakarta'
                ]
            ]
        ];
        return view('pages/contact', $data);
    }
    //--------------------------------------------------------------------

}
