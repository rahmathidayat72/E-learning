</div>
<!-- END MAIN CONTAINER -->

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>bootstrap/js/popper.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>assets/js/app.js"></script>
<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/apex/apexcharts.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>assets/js/dashboard/dash_1.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/table/datatable/datatables.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/select2/select2.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/select2/custom-select2.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/file-upload/file-upload-with-preview.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>assets/js/scrollspyNav.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/jquery-step/jquery.steps.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/jquery-step/custom-jquery.steps.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela/'); ?>plugins/summernote/summernote-ext-resized-data-image.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script>
    $(document).ready(function() {
        <?php $kelas = $this->db->get('kelas')->result(); ?>

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // var ss = $(".select2-input").select2({
        //     tags: true,
        // });

        $('.content').css('background', 'white');
        $('.steps ul').css('display', 'none');


        $('#datatable-table').DataTable({
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "tampilkan halaman _PAGE_ dari _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Cari Data...",
                "sLengthMenu": "Hasil :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [ [-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50] ]
        });

        $('.btn-hapus').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    document.location.href = href
                }
            });
        });

        // KELAS
        $('.tambah-baris-kelas').click(function() {
            const kelas = `
                <tr>
                    <td><input type="text" name="nama_kelas[]" required style="border: none; background: transparent; width: 100%; height: 50px;"></td>
                    <td>
                    <button class="btn btn-danger">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                    </td>
                </tr>
           `;

            $('#tbody-kelas').append(kelas)
        });

        $('#tbody-kelas').on('click', 'tr td button', function() {
            $(this).parents('tr').remove();
        });

        $('.edit-kelas').click(function() {
            const id_kelas = $(this).data('kelas');
            $.ajax({
                type: 'POST',
                data: {
                    id_kelas: id_kelas
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('ajax/edit_kelas') ?>",
                success: function(data) {
                    $.each(data, function(id_kelas, nama_kelas) {
                        $("#id_kelas").val(data.id_kelas);
                        $("#nama_kelas").val(data.nama_kelas);
                    });
                }
            });
        });
        // END KELAS


        // MAPEL
        $('.tambah-baris-mapel').click(function() {
            const mapel = `
                <tr>
                    <td><input type="text" name="nama_mapel[]" required style="border: none; background: transparent; width: 100%; height: 50px;"></td>
                    <td>
                    <button class="btn btn-danger">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                    </td>
                </tr>
           `;

            $('#tbody-mapel').append(mapel)
        });

        $('#tbody-mapel').on('click', 'tr td button', function() {
            $(this).parents('tr').remove();
        });

        $('.edit-mapel').click(function() {
            const id_mapel = $(this).data('mapel');
            $.ajax({
                type: 'POST',
                data: {
                    id_mapel: id_mapel
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('ajax/edit_mapel') ?>",
                success: function(data) {
                    $.each(data, function(id_mapel, nama_mapel) {
                        $("#id_mapel").val(data.id_mapel);
                        $("#nama_mapel").val(data.nama_mapel);
                    });
                }
            });
        });
        // END MAPEL

        // SISWA
        $('.tambah-baris-siswa').click(function() {
            const siswa = `
            <tr>
                <td><input type="text" name="nis[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td><input type="text" name="nama_siswa[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td>
                    <select name="jenis_kelamin[]" required style="border: none; background: transparent; width: 100%; height: 100%;">
                        <option value="">pilih</option>
                        <option value="Laki - Laki">Laki - Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </td>
                <td><input type="text" name="email[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td>
                    <select name="kelas[]" required style="border: none; background: transparent; width: 100%; height: 100%;">
                        <option value="">pilih</option>
                        <?php foreach ($kelas as $kel) : ?>
                            <option value="<?= $kel->id_kelas; ?>"><?= $kel->nama_kelas; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button class="btn btn-danger">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                </td>
            </tr>
           `;

            $('#tbody-siswa').append(siswa)
        });

        $('#tbody-siswa').on('click', 'tr td button', function() {
            $(this).parents('tr').remove();
        });

        $('.edit-siswa').click(function() {
            const id_siswa = $(this).data('siswa');
            $.ajax({
                type: 'POST',
                data: {
                    id_siswa: id_siswa
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('ajax/edit_siswa') ?>",
                success: function(data) {
                    $.each(data, function(id_siswa, nama_siswa, email, kelas, is_active) {
                        $("#id_siswa").val(data.id_siswa);
                        $("#nama_siswa").val(data.nama_siswa);
                        $("#email").val(data.email);
                        $("#kelas").val(data.kelas);
                        $("#active").val(data.is_active);
                    });
                }
            });
        });
        // END SISWA

        // GURU
        $('.tambah-baris-guru').click(function() {
            const guru = `
            <tr>
                <td><input type="text" name="nama_guru[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td><input type="text" name="email[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td>
                    <button class="btn btn-danger">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                </td>
            </tr>
           `;

            $('#tbody-guru').append(guru)
        });

        $('#tbody-guru').on('click', 'tr td button', function() {
            $(this).parents('tr').remove();
        });

        $('.edit-guru').click(function() {
            const id_guru = $(this).data('guru');
            $.ajax({
                type: 'POST',
                data: {
                    id_guru: id_guru
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('ajax/edit_guru') ?>",
                success: function(data) {
                    $.each(data, function(id_guru, nama_guru, email, is_active) {
                        $("#id_guru").val(data.id_guru);
                        $("#nama_guru").val(data.nama_guru);
                        $("#email").val(data.email);
                        $("#active").val(data.is_active);
                    });
                }
            });
        });
        // END GURU

        // CHECK GURU KELAS
        $('.check-kelas').on('click', function() {
            const id_guru = $(this).data('id_guru');
            const id_kelas = $(this).data('id_kelas');

            $.ajax({
                type: 'post',
                data: {
                    id_guru: id_guru,
                    id_kelas: id_kelas
                },
                async: true,
                url: "<?= base_url('ajax/guru_kelas') ?>",
                success: function() {
                    location.reload();
                }
            });
        });
        // END CHECK GURU KELAS

        // CHECK GURU MAPEL
        $('.check-mapel').on('click', function() {
            const id_guru = $(this).data('id_guru');
            const id_mapel = $(this).data('id_mapel');

            $.ajax({
                type: 'post',
                data: {
                    id_guru: id_guru,
                    id_mapel: id_mapel
                },
                async: true,
                url: "<?= base_url('ajax/guru_mapel') ?>",
                success: function() {
                    location.reload();
                }
            });
        });
        // END CHECK GURU MAPEL

        // MATERI
        $('.file_materi').click(function() {
            swal({
                title: 'Perhatian!',
                text: 'pastikan anda sudah mengatur maksimal upload di php.ini',
                type: 'warning',
                padding: '2em'
            })
        });

        $('.edit_materi').click(function() {
            const id_materi = $(this).data('materi');
            $.ajax({
                type: 'POST',
                data: {
                    id_materi: id_materi
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('ajax/edit_materi') ?>",
                success: function(data) {
                    $.each(data, function(id_materi, kode_materi, nama_materi, guru, mapel, kelas, text_materi) {
                        $("input[name=e_kode_materi]").val(data.kode_materi);
                        $("input[name=e_nama_materi]").val(data.nama_materi);
                        $("select[name=e_mapel]").val(data.mapel);
                        $("select[name=e_kelas]").val(data.kelas);
                        $("textarea[name=e_text_materi]").val(data.text_materi);
                    });
                }
            });
        });

        $('#chat_materi').click(function() {
            const chat_materi = $('textarea[name=text]').val();
            const kode_materi = $('input[name=kode_materi]').val();

            $.ajax({
                type: 'POST',
                data: {
                    chat_materi: chat_materi,
                    kode_materi: kode_materi
                },
                async: true,
                url: "<?= base_url('ajax/chat_materi') ?>",
                success: function(html) {
                    $('textarea[name=text]').val('');
                }
            });
        });

        // START TUGAS
        $('#chat_tugas').click(function() {
            const chat_tugas = $('textarea[name=text]').val();
            const kode_tugas = $('input[name=kode_tugas]').val();

            $.ajax({
                type: 'POST',
                data: {
                    chat_tugas: chat_tugas,
                    kode_tugas: kode_tugas
                },
                async: true,
                url: "<?= base_url('ajax/chat_tugas') ?>",
                success: function(html) {
                    $('textarea[name=text]').val('');
                }
            });
        });

        $('.btn_edit_tugas').click(function() {
            const id_tugas = $(this).data('tugas');
            $.ajax({
                type: 'POST',
                data: {
                    id_tugas: id_tugas
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('ajax/edit_tugas') ?>",
                success: function(data) {
                    $.each(data, function(id_tugas, kode_tugas, kelas, mapel, guru, nama_tugas, deskripsi, due_date) {
                        $("input[name=e_kode_tugas]").val(data.kode_tugas);
                        $("input[name=e_nama_tugas]").val(data.nama_tugas);
                        $("select[name=e_mapel]").val(data.mapel);
                        $("select[name=e_kelas]").val(data.kelas);
                        $("textarea[name=e_deskripsi]").val(data.deskripsi);
                        var tgl = data.due_date.substring(0, 10);
                        $("input[name=e_tgl]").val(tgl);
                        var jam = data.due_date.substring(11, 16);
                        $("input[name=e_jam]").val(jam);
                        // alert(jam);
                    });
                }
            });
        });

        // END TUGAS


        // TAMBAH SOAL PG
        // SISWA
        var no_soal = 2;
        $('.tambah-pg').click(function() {
            const pg = `
            <div class="isi_soal">
            <hr>
                <div class="form-group">
                    <label for="">Soal No . ` + no_soal + `</label>
                    <textarea name="nama_soal[]" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan A</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">A</span>
                                </div>
                                <input type="text" name="pg_1[]" class="form-control" placeholder="Opsi A" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan B</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">B</span>
                                </div>
                                <input type="text" name="pg_2[]" class="form-control" placeholder="Opsi B" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan C</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">C</span>
                                </div>
                                <input type="text" name="pg_3[]" class="form-control" placeholder="Opsi C" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan D</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">D</span>
                                </div>
                                <input type="text" name="pg_4[]" class="form-control" placeholder="Opsi D" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan E</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">E</span>
                                </div>
                                <input type="text" name="pg_5[]" class="form-control" placeholder="Opsi E" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Jawaban</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <input type="text" name="jawaban[]" class="form-control" placeholder="Contoh : A" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0);" class="btn btn-danger hapus-pg">Hapus</a>
            </div>
           `;

            $('#soal_pg').append(pg);
            no_soal++;
        });

        $('#soal_pg').on('click', '.isi_soal a', function() {
            $(this).parents('.isi_soal').remove();
        });

        $('.tambah-essay').click(function() {
            const essay = `
            <div class="isi_soal mt-2">
                <div class="form-group">
                    <label for="">Soal No. ` + no_soal + `</label><br>
                    <textarea class="summernote" name="soal[]" cols="30" rows="5" wrap="hard"></textarea>
                </div>
                <a href="javascript:void(0);" class="btn btn-danger hapus-pg">Hapus</a>
            </div>
           `;

            $('#soal_essay').append(essay);
            no_soal++;
        });
        $('#soal_essay').on('click', '.isi_soal a', function() {
            $(this).parents('.isi_soal').remove();
        });
        $("circle-basic").steps({
            cssClass: 'circle wizard'
        });

        // END TAMBAH SOAL PG

        var oneUpload = new FileUploadWithPreview('fileMateri');
        var secondUpload = new FileUploadWithPreview('videoMateri');

        var oneUpload = new FileUploadWithPreview('e_fileMateri');
        var secondUpload = new FileUploadWithPreview('e_videoMateri');

        // END MATERI
    })
</script>

</body>


</html>