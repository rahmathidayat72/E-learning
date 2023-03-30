<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">
            <div class="col-lg-6 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading text-center">
                        <h5 class="text-center">Relasi Guru - Kelas</h5>
                        <form action="<?= base_url('guru_kelas'); ?>" method="POST">
                            <table class="table table-bordered mt-2">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kelas as $kel) :  ?>
                                        <tr>
                                            <td><?= $kel->nama_kelas; ?></td>
                                            <td>
                                                <label class="new-control new-checkbox checkbox-primary">
                                                    <input type="checkbox" class="new-control-input check-kelas" <?= check_kelas(encrypt_url($guru->id_guru), $kel->id_kelas); ?> data-id_guru="<?= encrypt_url($guru->id_guru); ?>" data-id_kelas="<?= $kel->id_kelas; ?>">
                                                    <span class="new-control-indicator"></span> Mengajar
                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="widget shadow p-3">
                    <div class="widget-heading text-center">
                        <h5 class="text-center">Relasi Guru - Mapel</h5>
                        <form action="<?= base_url('guru_mapel'); ?>" method="POST">
                            <table class="table table-bordered mt-2">
                                <thead>
                                    <tr>
                                        <th>Mapel</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mapel as $m) :  ?>
                                        <tr>
                                            <td><?= $m->nama_mapel; ?></td>
                                            <td>
                                                <label class="new-control new-checkbox checkbox-primary">
                                                    <input type="checkbox" class="new-control-input check-mapel" <?= check_mapel(encrypt_url($guru->id_guru), $m->id_mapel); ?> data-id_guru="<?= encrypt_url($guru->id_guru); ?>" data-id_mapel="<?= $m->id_mapel; ?>">
                                                    <span class="new-control-indicator"></span> Mengajar
                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="">Copyright © 2021 <a target="_blank" href="http://bit.ly/demo-abdul">Abduloh Malela</a>, All rights reserved. <a href="https://freepik.com" target="_blank" class="text-primary">Illustration by Freepik</a></p>
        </div>
        <div class="footer-section f-section-2">
            <p class="">CBT-MALELA v 1.1</p>
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->
<script>
    <?= $this->session->flashdata('pesan'); ?>
</script>