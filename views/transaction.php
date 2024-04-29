<?php get_header() ?>
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
        <div class="row mb-2">
            <div class="col-12">
                <div class="form-group">
                    <input type="text" class="form-control" name="search" placeholder="Cari barang">
                </div>
            </div>
        </div>
        <div class="product-list-container">


            <div class=" product-list row mb-3">
                <?php foreach ($products as $product) : ?>

                    <div class="col-md-4 pb-3">


                        <div class="produk list-group-item justify-content-between align-items-center active-list">
                            <div class="col-md-6 col-sm-4 text-group">
                                <p class="m-0">Rp. <?= number_format($product->price) ?></p>
                                <p class="m-0 txt-light"><?= $product->product_name ?></p>
                            </div>

                            <div class="col-md-3 col-sm-4 d-flex align-items-center">
                                <span class=""><i class="mdi mdi-cube-outline"></i></span>
                                <p class="m-0"><?= $product->sku ?></p>
                            </div>

                            <a href="#" class="col-md-1 col-sm-12 btn btn-icons btn-rounded btn-outline-primary font-weight-bold btn-pilih" id="<?= $product->id_product; ?>" role="button"><i class="mdi mdi-chevron-right"></i></a>
                        </div>
                    </div>
                <?php endforeach ?>

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

        <form method="POST" name="transaction_form" id="transaction_form" action="">
            <?= csrf_field() ?>

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
                                                    <input type="hidden" name="user_id" value="<?= auth()->id ?>">
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
                                        <tr>
                                            <td class="text-left">
                                                <span class="diskon-td">Diskon</span>
                                                <a href="#" class="ubah-diskon-td">Ubah diskon</a>
                                                <a href="#" class="simpan-diskon-td" hidden="">Simpan</a>
                                            </td>
                                            <td class="text-right d-flex justify-content-end align-items-center pt-2">
                                                <input type="number" class="form-control diskon-input mr-2" min="0" max="100" name="diskon" value="0" hidden="">
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
                                <div class="form-group mb-2 mt-2">
                                    <label for="exampleInputPassword1"></label>
                                    <select class="form-control select2 is-invalid" name="user_id" id="user_id" required>
                                        <option value="">Pilih Customer</option>
                                        <?php foreach ($customer as $cus) : ?>
                                            <option value="<?= $cus->id; ?>"><?= $cus->name; ?></option>
                                        <?php endforeach ?>
                                        <option value="0">Masukan nama customer baru</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-2">
                                    <table class="table-payment-3">
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

        // Periksa apakah URL sudah benar
        $.ajax({
            url: "<?php echo routeTo('commerce/dataProduct') ?>" + "?id=" + id_product,
            method: "GET",
            success: function(response) {
                console.log(response)
                var check = $('.id-barang-td:contains(' + response.data.id_product + ')').length;
                if (check == 0) {
                    tambahData(response.data.id_product, response.data.product_name, response.data.price, response.data.sku);
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
    });
</script>