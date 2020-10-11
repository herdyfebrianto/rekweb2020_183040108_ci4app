<?php

namespace App\Controllers;

use App\Models\KomikModel;
use CodeIgniter\HTTP\Request;

class komik extends BaseController
{
    protected $komikModel;
    public function __construct()
    {

        $this->komikModel = new KomikModel();
    }

    public function index()
    {

        // dd($komik);
        $data = [
            'title' => 'Daftar Komik',
            'komik' => $this->komikModel->getKomik()
        ];

        // $komikModel = new \App\Models\KomikModel();
        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $komik = $this->komikModel->getKomik($slug);
        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        //jika komik tidak ada di table
        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik ' . $slug . ' Tidak Ditemukan');
        }

        return view('komik/detail', $data);
    }

    public function create()
    {
        // session();
        $data = [
            'title' => 'Form Tambah data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} Komik Harus Di Isi.',
                    'is_unique' => '{field} Komik Sudah Terdaftar.'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran Gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');
        // dd($fileSampul);
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.png';
        } else {
            $namaSampul = $fileSampul->getRandomName();

            $fileSampul->move('img', $namaSampul);

            // $namaSampul = $fileSampul->getName();

        }


        $slug = url_title($this->request->getVar('judul'), '-', true);
        // dd($this->request->getVar());
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul

        ]);

        session()->setFlashdata('pesan', 'Data Berhasil Di Tambahkan.');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        // cari gambar berdasar ID
        $komik = $this->komikModel->find($id);

        // jika gambar default
        if ($komik['sampul'] != 'default.png') {

            unlink('img/' . $komik['sampul']);
        }

        // hapus gambar
        $this->komikModel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil Di Hapus.');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        // session();
        $data = [
            'title' => 'Form Ubah data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)

        ];

        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // dd($this->request->getVar());
        // cek judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }

        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} Komik Harus Di Isi.',
                    'is_unique' => '{field} Komik Sudah Terdaftar.'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran Gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');
        // cek gambar
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            $namaSampul = $fileSampul->getRandomName();
            // upload gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file lama
            unlink('img/' . $this->request->getVar('sampulLama'));
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        // dd($this->request->getVar());
        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul

        ]);

        session()->setFlashdata('pesan', 'Data Berhasil Di Ubah.');

        return redirect()->to('/komik');
    }
}

        // // connect db tanpa model
        // $db = \Config\Database::connect();
        // $komik = $db->query("SELECT * FROM komik");
        // foreach($komik->getResultArray() as $row) {
        //     d($row);
        // }