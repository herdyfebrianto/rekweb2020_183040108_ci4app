<?php

namespace App\Controllers;

use App\Models\OrangModel;
use CodeIgniter\HTTP\Request;

class Orang extends BaseController
{
    protected $orangModel;
    public function __construct()
    {

        $this->orangModel = new OrangModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $orang = $this->orangModel->search($keyword);
        } else {
            $orang = $this->orangModel;
        }

        $data = [
            'title' => 'Daftar Orang-Orang',
            // 'orang' => $this->orangModel->findAll()
            'orang' => $orang->paginate(7, 'orang'),
            'pager' => $this->orangModel->pager,
            'currentPage' => $currentPage
        ];

        // $komikModel = new \App\Models\KomikModel();
        return view('orang/index', $data);
    }
}

        // // connect db tanpa model
        // $db = \Config\Database::connect();
        // $komik = $db->query("SELECT * FROM komik");
        // foreach($komik->getResultArray() as $row) {
        //     d($row);
        // }