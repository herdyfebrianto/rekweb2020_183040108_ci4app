<?= $this->extend('layout/templates'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="mt-2">Detail Komik</h2>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="<?= base_url('/img'); ?>/<?= $komik['sampul']; ?>" alt="" class="card-img" width="100px">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $komik['judul']; ?></h5>
                            <p class="card-text"><b>Penulis : <?= $komik['penulis']; ?></b></p>
                            <p class="card-text"><small class="text-muted"><b>Penerbit : <?= $komik['penerbit']; ?></b></small></p>

                            <a href="<?= base_url('/komik/edit'); ?>/<?= $komik['slug']; ?>" class="btn btn-warning">Edit</a>

                            <form action="<?= base_url('/komik'); ?>/<?= $komik['id']; ?>" method="post" class="d-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda Yakin?');">Delete</button>
                            </form>

                            <br><br>
                            <a href="../komik" class="btn">Kembali Ke Daftar Komik</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>