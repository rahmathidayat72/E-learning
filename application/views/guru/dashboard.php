<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">
            <div class="col-lg-6">
                <div class="infobox-3 bg-white" style="width: 100%;">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                            <polygon points="12 15 17 21 7 21 12 15"></polygon>
                        </svg>
                    </div>
                    <h5 class="info-heading"><?= $guru->nama_guru; ?></h5>
                    <p class="info-text">data ini diatur oleh administrator., jika ada perubahan bisa hubungi admin</p>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-group">
                                <li class="list-group-item bg-primary light text-center">Kelas Saya</li>
                                <?php if ($guru_kelas) : ?>
                                    <?php foreach ($guru_kelas as $gk) : ?>
                                        <?php foreach ($kelas as $kel) : ?>
                                            <?php if ($gk->kelas == $kel->id_kelas) : ?>
                                                <li class="list-group-item"><?= $kel->nama_kelas; ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li class="list-group-item">Tidak Ada</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list-group">
                                <li class="list-group-item bg-primary light text-center">Mapel Saya</li>
                                <?php if ($guru_mapel) : ?>
                                    <?php foreach ($guru_mapel as $gm) : ?>
                                        <?php foreach ($mapel as $mpl) : ?>
                                            <?php if ($gm->mapel == $mpl->id_mapel) : ?>
                                                <li class="list-group-item"><?= $mpl->nama_mapel; ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li class="list-group-item">Tidak Ada</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="">Copyright © 2021 <a target="_blank" href="http://bit.ly/demo-abdul" class="text-primary">Abduloh Malela</a></p>
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