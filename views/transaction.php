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

<div class="card">
    <div class="card-header d-flex flex-grow-1 align-items-center">
        <p class="h4 m-0"> Transaction</p>

    </div>
    <div class="card-body">
        <h4 class="page-title">Daftar Produk</h4>

        <form method="POST" name="transaction_form" id="transaction_form" action="">
            <?= csrf_field() ?>

            <?php if (get_role(auth()->id)->role_id != env('CUSTOMER_ROLE_ID')) : ?>
                <div class="form-group col-md-12 mb-2 mt-2">
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
                                                <label for="provinsiTujuan" class="form-control">Provinsi Tujuan</label>
                                                <select class="form-control" id="provinsiTujuan" name="provinsiTujuan" onchange="getKabupatenTujuan(this.value)">
                                                
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

    // Ambil hasil pemeriksaan kondisi dari PHP
    const isCustomerRole = <?= get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID') ? 1 : 0 ?>;

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
                quantity: 1
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

<script>
	// Panggil fungsi untuk menampilkan data cart saat halaman dimuat
	displayCart();
	updateCart();

	// Fungsi untuk mendapatkan data cart dari localStorage
	function getCartData() {
		const cartData = localStorage.getItem('cart');
		if (cartData) {
			return JSON.parse(cartData);
		}
		return [];
	}

	// Fungsi untuk menampilkan data cart dalam tabel
	function displayCart() {
		// Dapatkan data cart dari localStorage
		const cart = getCartData();

		// Temukan elemen tabel cart
		const cartTableBody = document.querySelector('.cart-table tbody');

		// Bersihkan isi tabel sebelumnya
		cartTableBody.innerHTML = '';

		// Loop melalui setiap item di cart dan buat baris tabel
		cart.forEach(item => {
			const tr = document.createElement('tr');

			// Tambahkan atribut data-product-id ke elemen tr
			tr.setAttribute('data-product-id', item.productId);

			// Kolom untuk tombol hapus
			const deleteTd = document.createElement('td');
			const deleteLink = document.createElement('a');
			deleteLink.href = 'javascript:void(0);';
			deleteLink.textContent = 'X';
			deleteLink.onclick = () => {
				// Tampilkan alert konfirmasi
				const isConfirmed = confirm("Apakah Anda yakin ingin menghapus item ini?");
				// Jika pengguna memilih "OK" pada alert
				if (isConfirmed) {
					// Panggil fungsi removeFromCart dengan itemId dari item
					removeFromCart(item.productId);
					// Segarkan (refresh) halaman untuk memperbarui tampilan
					location.reload();
				}
			};
			deleteTd.appendChild(deleteLink);
			tr.appendChild(deleteTd);

			// Kolom untuk nama produk
			const nameTd = document.createElement('td');
			const nameDiv = document.createElement('div');
			nameDiv.className = 'product-title';
			const nameLink = document.createElement('a');
			nameLink.href = '#';
			nameLink.textContent = item.productName;
			nameDiv.appendChild(nameLink);
			nameTd.appendChild(nameDiv);
			tr.appendChild(nameTd);

			// Kolom untuk kuantitas
			const quantityTd = document.createElement('td');
			const quantityInput = document.createElement('input');
			quantityInput.type = 'number';
			quantityInput.value = item.quantity;
			quantityTd.appendChild(quantityInput);
			tr.appendChild(quantityTd);

			// Kolom untuk harga per item
			const priceTd = document.createElement('td');
			const priceDiv = document.createElement('div');
			priceDiv.className = 'price-box';
			const priceSpan = document.createElement('span');
			priceSpan.className = 'price';

			// Gunakan `toLocaleString()` untuk memformat harga sesuai dengan standar Indonesia
			// const formattedPrice = item.productPrice.toLocaleString('id-ID', {
			const formattedPrice = item.final_price.toLocaleString('id-ID', {
				style: 'currency',
				currency: 'IDR',
				minimumFractionDigits: 0,
			});

			// Set textContent dengan format yang sudah diperoleh
			priceSpan.textContent = `${item.quantity} x ${item.final_price}`;
			priceDiv.appendChild(priceSpan);
			priceTd.appendChild(priceDiv);
			tr.appendChild(priceTd);

			// Kolom untuk total harga
			const totalPriceTd = document.createElement('td');
			const totalPriceDiv = document.createElement('div');
			totalPriceDiv.className = 'total-price-box';
			const totalPriceSpan = document.createElement('span');
			totalPriceSpan.className = 'price';
			const totalPrice = item.quantity * item.final_price;
			const formattedTotalPrice = totalPrice.toLocaleString('id-ID', {
				style: 'currency',
				currency: 'IDR',
				minimumFractionDigits: 0,
			});

			totalPriceSpan.textContent = `${formattedTotalPrice}`;
			totalPriceDiv.appendChild(totalPriceSpan);
			totalPriceTd.appendChild(totalPriceDiv);
			tr.appendChild(totalPriceTd);

			// Tambahkan baris ke tabel
			cartTableBody.appendChild(tr);
		});
	}
	// Menambahkan event listener untuk elemen select dengan ID "ongkir"
	document.getElementById('ongkir').addEventListener('change', function() {
		totalOngkir(this.value);
	});


	function updateCart() {
		// Dapatkan data keranjang dari localStorage
		let cart = JSON.parse(localStorage.getItem('cart')) || [];

		// Dapatkan semua baris dalam tabel cart
		const rows = document.querySelectorAll('.cart-table tbody tr');
		let totalHargaProduk = 0;

		// Iterasi melalui setiap baris dalam tabel
		rows.forEach(row => {
			// Dapatkan input kuantitas
			const quantityInput = row.querySelector('input[type="number"]');
			// Dapatkan ID produk
			const productId = row.getAttribute('data-product-id'); // Asumsikan setiap baris memiliki atribut data-product-id

			// Periksa apakah kuantitas input ada dan valid
			if (quantityInput) {
				const newQuantity = parseInt(quantityInput.value);

				// Validasi kuantitas
				if (!isNaN(newQuantity) && newQuantity >= 0) {

					// Dapatkan harga per item
					const itemPrice = row.querySelector('.price-box .price');
					const itemPriceText = itemPrice.textContent;
					// Pisahkan kuantitas dan harga dari string
					const [quantityText, priceText] = itemPriceText.split(' x ');
					const priceValue = priceText.replace(/[^0-9]/g, '');
					const price = parseFloat(priceValue);

					// Validasi harga per item
					if (!isNaN(price)) {
						// Hitung total baru
						const newTotal = newQuantity * price;

						// Perbarui kolom harga
						const formattedPrice = newQuantity + ' x Rp. ' + price.toLocaleString('id-ID');
						const priceColumn = row.querySelector('.price-box .price');
						priceColumn.textContent = formattedPrice;

						// Perbarui kolom total
						const formattedTotal = 'Rp. ' + newTotal.toLocaleString('id-ID');
						const totalPriceColumn = row.querySelector('.total-price-box .price');
						totalPriceColumn.textContent = formattedTotal;

						// Tambahkan total harga ke total keseluruhan
						totalHargaProduk += newTotal;
						const itemIndex = cart.findIndex(item => item.productId === productId);
						if (itemIndex !== -1) {
							// Periksa apakah kuantitas berubah sebelum memanggil cekDiscountQuantity
							if (cart[itemIndex].quantity !== newQuantity) {
								cart[itemIndex].quantity = newQuantity;
								// Panggil fungsi cekDiscount hanya ketika kuantitas berubah
								cekDiscountQuantity(productId, newQuantity);
							}
						}

						
						// Panggil fungsi cekDiscount untuk mengecek diskon berdasarkan kuantitas baru
					} else {
						console.error('Invalid item price');
					}
				} else {
					console.error('Invalid quantity');
				}
			} else {
				console.error('Quantity input not found');
			}
		});

		// Format total harga produk
		const rupiahHargaProduk = 'Rp. ' + totalHargaProduk.toLocaleString('id-ID');

		// Perbarui total harga produk di halaman
		const totalProduk = document.querySelector('#totalProduk');
		totalProduk.innerHTML = `<span>Total Harga Produk:</span> ${rupiahHargaProduk}`;

		// Simpan data keranjang kembali ke localStorage
		localStorage.setItem('cart', JSON.stringify(cart));
	}

	function cekDiscountQuantity(productId, newQuantity) {
		const user_id = <?php echo json_encode(isset(auth()->id) && auth()->id ? auth()->id : null); ?>;
		const token = document.querySelector('[name=_token]').value;

		$.ajax({
			url: "<?php echo routeTo('landing/cekDiscountQuantityProduk') ?>",
			method: "POST",
			data: {
				_token: token,
				user_id: user_id,
				quantity: newQuantity,
				productId: productId
			},
			success: function(response) {
				// Mengambil data dari localStorage
				let cartData = JSON.parse(localStorage.getItem('cart')) || [];

				// Iterasi setiap produk dalam respons
				let cartProduct = cartData.find(item => item.productId == response.data.id_product);
				if (cartProduct) {
					cartProduct.final_price = response.data.final_price;
				}
				// Menyimpan kembali data yang sudah diperbarui ke dalam localStorage
				localStorage.setItem('cart', JSON.stringify(cartData));
				updateCart();
				location.reload();



			},
			error: function(xhr, status, error) {
				console.error(`Error: ${error}`);
			}
		});
	}

</script>