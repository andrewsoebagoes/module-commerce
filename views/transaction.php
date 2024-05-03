<?php

use Core\Session;

get_header() ?>
<style>
    .product-list-container {
        max-height: 300px;
        width: 100%;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    .produk {
        border: 1px solid grey;
        border-radius: 10px;
        padding: 8px;
    }

    .sidebar {
        display: none;
    }

    .main-panel {
        width: 100%;
    }

    .btn-pilih {
        margin-right: 5px;
        padding: 5px;
    }

    @media (min-width: 1000px) {
        .navbar.default-layout .navbar-brand-wrapper {
            width: 70px;
        }

        .navbar.default-layout .navbar-brand-wrapper .brand-logo-mini {
            display: inline-block;
        }

        .navbar.default-layout .navbar-brand-wrapper .brand-logo-mini img {
            width: 40px;
            max-width: 100%;
            margin: auto;
        }

        .navbar.default-layout .navbar-menu-wrapper {
            width: calc(100% - 70px);
        }
    }
</style>
<div class="page-title-box">
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="http://localhost:8080/">Home</a></li>
            <li class="breadcrumb-item"><a href="http://localhost:8080/commerce/transaction">Transaction</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Index</a></li>
        </ol>
    </div>
    <h4 class="page-title">Transaction</h4>
</div>
<div class="card">
    <div class="card-header d-flex flex-grow-1 align-items-center">
        <p class="h4 m-0"> Transaction</p>

    </div>
    <div class="card-body">
        <h4 class="page-title">Daftar Produk</h4>

        <form method="POST" name="transaction_form" id="transaction_form" action="">
            <?= csrf_field() ?>

            <?php if (get_role(auth()->id)->role_id != env('CUSTOMER_ROLE_ID')) : ?>
                <div class="form-group col-md-5 mb-2 mt-2">
                    <label for="exampleInputPassword1"></label>
                    <select class="form-control select2 is-invalid" name="user_id" id="user_id" onchange="cekDiscount(this.value)" required>
                        <option value="">Pilih Customer</option>
                        <?php foreach ($customer as $cus) : ?>
                            <option value="<?= $cus->id; ?>"><?= $cus->name; ?></option>
                        <?php endforeach ?>
                        <option value="0">Masukan nama customer baru</option>
                    </select>
                </div>
            <?php endif ?>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari barang">
                    </div>
                </div>
            </div>
            <div class="product-list-container">

                <div class="product-list row mb-3" id="product">


                </div>
            </div>
            <?php if ($success_msg) : ?>
                <div class="alert alert-success"><?= $success_msg ?></div>
            <?php endif ?>
            <?php if ($error_msg) : ?>
                <div class="alert alert-danger"><?= $error_msg ?></div>
            <?php endif ?>
            <div class="row page-title-header">
                <div class="col-12">
                    <div class="page-header d-flex justify-content-start align-items-center">
                        <h4 class="page-title">Transaksi Barang</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger kode_barang_error" hidden="" role="alert">
                        <i class="mdi mdi-information-outline"></i> Kode barang tidak tersedia
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger kode_barang_double" hidden="" role="alert">
                        <i class="mdi mdi-information-outline"></i> Barang sudah ditambahkan
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 mb-4">
                    <div class="row">
                        <div class="col-12 bg-dark-blue">
                            <div class="card card-noborder b-radius">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between align-items-center transaction-header">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="icon-holder">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </div>
                                                <div class="transaction-code" style="margin-left: 10px;">
                                                    <p class="m-0 text-white">Kode Transaksi</p>
                                                    <p class="m-0 text-white">T<?= date('dmYHis') ?></p>
                                                    <input type="text" name="kode_transaksi" value="T<?= date('dmYHis') ?>" hidden="">
                                                    <input type="hidden" name="customer_user_id" value="<?= auth()->id ?>">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card card-noborder b-radius">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-start align-items-center">
                                            <div class="cart-icon" style="margin-right: 10px;">
                                                <i class="mdi mdi-cart-outline"></i>
                                            </div>
                                            <p class="m-0 ml-3 text-black-50"> Daftar Pesanan</p>
                                        </div>
                                        <div class="col-12 mt-3 table-responsive">
                                            <table class="table table-checkout">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-noborder b-radius">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 payment-1">
                                    <table class="table-payment-1">
                                        <tr>
                                            <td class="text-left">Tanggal</td>
                                            <td class="text-right"><?= date('d M, Y') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Waktu</td>
                                            <td class="text-right"><?= date('H:i') ?></td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="col-12 mt-4">
                                    <table class="table-payment-2">
                                        <tr>
                                            <td class="text-left">
                                                <span class="subtotal-td">Subtotal</span>
                                                <span class="jml-barang-td">0 Barang</span>
                                            </td>
                                            <td class="text- nilai-subtotal1-td">Rp. 0</td>
                                            <td hidden=""><input type="text" class="nilai-subtotal2-td" name="subtotal" value="0"></td>
                                        </tr>
                                        <tr style="display: none;">
                                            <td class="text-left">
                                                <span class="diskon-td">Diskon</span>
                                                <a href="#" class="ubah-diskon-td">Ubah diskon</a>
                                                <a href="#" class="simpan-diskon-td" hidden="">Simpan</a>
                                            </td>
                                            <td class="text-right d-flex justify-content-end align-items-center pt-2">
                                                <input type="hidden" class="form-control diskon-input mr-2" min="0" max="100" name="diskon" value="0" hidden="">
                                                <span class="nilai-diskon-td mr-1">0</span>
                                                <span>%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center nilai-total1-td">Rp. 0</td>
                                            <td hidden=""><input type="text" class="nilai-total2-td" name="total" value="0"></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-12 mt-2">
                                    <table class="table-payment-3">
                                        <?php if (get_role(auth()->id)->role_id != env('CUSTOMER_ROLE_ID')) : ?>
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">Rp.</div>
                                                        </div>
                                                        <input type="text" class="form-control number-input input-notzero bayar-input" name="bayar" placeholder="Masukkan nominal bayar">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="nominal-error" hidden="">
                                                <td class="text-danger nominal-min">Nominal bayar kurang</td>
                                            </tr>

                                            <tr>
                                                <td class="text-right">
                                                    <button class="btn btn-bayar" type="button">Bayar</button>
                                                </td>
                                            </tr>
                                        <?php else : ?>

                                            <div class="mb-3 mt-3">
                                                <label for="provinsiTujuan" class="form-label">Provinsi Tujuan</label>
                                                <select class="form-select" id="provinsiTujuan" name="provinsiTujuan" onchange="getKabupatenTujuan(this.value)">
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="kabupatenTujuan" class="form-label">Kabupaten/Kota Tujuan</label>
                                                <select class="form-select" id="kabupatenTujuan" name="kabupatenTujuan">
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="address" class="form-label">Alamat Lengkap</label>
                                                <textarea type="text" id="address" class="form-control" name="address"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="ekspedisi" class="form-label">Ekspedisi</label>
                                                <select name="ekspedisi" id="ekspedisi" class="form-control" onchange="cekOngkir(this.value)">
                                                    <option value="">--Pilih ekspedisi--</option>
                                                    <option value="pos">Pos Indonesia</option>
                                                    <option value="tiki">TIKI</option>
                                                    <option value="jne">JNE</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="ongkir" class="form-label">Pilih Ongkir</label>
                                                <select class="form-select" id="ongkir" name="ongkir">

                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="notes" class="form-label">Catatan</label>
                                                <textarea type="text" id="notes" class="form-control" name="notes"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="address" class="form-label">
                                                    <input type="checkbox" value="1" id="preorder" class="" name="preorder"> Pre Order
                                                </label>
                                            </div>


                                            <tr>
                                                <td class="text-right">
                                                    <button class="btn btn-bayar" type="button">Checkout</button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>


<?php get_footer() ?>

<script>
    getProvinsiTujuan();

    function getProvinsiTujuan() {
        $.ajax({
            url: "<?php echo routeTo('commerce/getProvinsi') ?>",
            method: "GET",
            success: function(response) {
                $('#provinsiTujuan').html(response);
            },
            error: function(xhr, status, error) {
                console.error(`Error: ${error}`);
            }
        });
    }

    function getKabupatenTujuan(idProvinsi) {
        $.ajax({
            url: "<?php echo routeTo('commerce/getKabupaten') ?>" + "?id_provinsi=" + idProvinsi,
            method: "GET",
            success: function(response) {
                $('#kabupatenTujuan').html(response);
            },
            error: function(xhr, status, error) {
                console.error(`Error: ${error}`);
            }
        });
    }

    // Fungsi untuk mengecek ongkos kirim
    function cekOngkir() {
        var jumlah_barang = 0;
        $('.jumlah_barang_text').each(function() {
            jumlah_barang += parseInt($(this).text());
        });
        const kabupatenTujuan = $('#kabupatenTujuan').val();
        const beratBarang = 1000;
        const ekspedisi = $('#ekspedisi').val();

        $.ajax({
            url: "<?php echo routeTo('commerce/getOngkir') ?>",
            method: "POST",
            data: {
                _token: document.querySelector('[name=_token]').value,
                destination: kabupatenTujuan,
                weight: jumlah_barang * 1000,
                courier: ekspedisi
            },
            success: function(response) {
                $('#ongkir').html(response);
                console.log(jumlah_barang);
            },
            error: function(xhr, status, error) {
                console.error(`Error: ${error}`);
            }

        });
    }
</script>



<script>
    <?php
    // Periksa apakah pengguna memiliki peran yang sesuai
    $isCustomerRole = get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID');
    ?>

    // Ambil hasil pemeriksaan kondisi dari PHP
    const isCustomerRole = <?php echo json_encode($isCustomerRole); ?>;

    // Jika pengguna memiliki peran pelanggan, jalankan fungsi cekDiscount()
    if (isCustomerRole) {
        cekDiscount();
    }

    function cekDiscount() {
        const user_id = $('#user_id').val();

        $.ajax({
            url: "<?php echo routeTo('commerce/cekDiscount') ?>",
            method: "POST",
            data: {
                _token: document.querySelector('[name=_token]').value,
                user_id: user_id ?? <?= auth()->id ?>,
            },
            success: function(response) {
                console.log(response);
                $('#product').html(response);
            },
            error: function(xhr, status, error) {
                console.error(`Error: ${error}`);
            }

        });
    }

    $(document).ready(function() {
        $('#user_id').on('change', function() {
            if ($(this).val() === '0') {
                if ($('#newCustomerInput').length === 0) {
                    $('<input>', {
                        type: 'text',
                        name: 'new_customer_name',
                        id: 'newCustomerInput',
                        class: 'form-control mt-2',
                        placeholder: 'Masukkan nama customer baru'
                    }).insertAfter(this);
                }
            } else {
                $('#newCustomerInput').remove();
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click', '.btn-pilih', function(e) {
        e.preventDefault();

        // Gunakan atribut data untuk mengambil ID produk dengan benar
        var id_product = $(this).attr('id');
        var user_id = $('#user_id').val();

        // Periksa apakah URL sudah benar
        $.ajax({
            url: "<?php echo routeTo('commerce/dataProduct') ?>" + "?id=" + id_product + "&user_id=" + user_id,
            method: "GET",
            success: function(response) {
                console.log(response)
                var check = $('.id-barang-td:contains(' + response.data.id_product + ')').length;
                if (check == 0) {
                    tambahData(response.data.id_product, response.data.product_name, response.data.final_price, response.data.sku);
                } else {
                    swal(
                        "",
                        "Barang telah ditambahkan",
                        "error"
                    );
                }
            }
        });
    });



    $(document).on('click', '.btn-bayar', function() {
        var total = parseInt($('.nilai-total2-td').val());
        var bayar = parseInt($('.bayar-input').val());
        var check_barang = parseInt($('.jumlah_barang_text').length);
        var role_id = <?= get_role(auth()->id)->role_id ?>;
        var customer = <?= env('CUSTOMER_ROLE_ID') ?>;
        if (role_id != customer) {
            if (bayar >= total) {
                $('.nominal-error').prop('hidden', true);
                if (check_barang != 0) {
                    if ($('.diskon-input').attr('hidden') != 'hidden') {
                        $('.diskon-input').addClass('is-invalid');
                    } else {
                        $('#transaction_form').submit();
                    }
                } else {
                    swal(
                        "",
                        "Pesanan Kosong",
                        "error"
                    );
                }
            } else {
                if (isNaN(bayar)) {
                    $('.bayar-input').valid();
                } else {
                    $('.nominal-error').prop('hidden', false);
                }

                if (check_barang == 0) {
                    swal(
                        "",
                        "Pesanan Kosong",
                        "error"
                    );
                }
            }
        } else {
            if (check_barang != 0) {
                if ($('.diskon-input').attr('hidden') != 'hidden') {
                    $('.diskon-input').addClass('is-invalid');
                } else {
                    $('#transaction_form').submit();
                }
            } else {
                swal(
                    "",
                    "Pesanan Kosong",
                    "error"
                );
            }
        }
    });
</script>