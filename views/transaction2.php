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

    .cart-btn-right a {

        float: right;
    }
</style>

<div class="card">
    <div class="card-header d-flex flex-grow-1 align-items-center">
        <p class="h4 m-0"> Transaction</p>

    </div>
    <div class="card-body">
        <h4 class="page-title">Daftar Produk</h4>

        <form action="" id="formDataBarang" method="post">
            <?= csrf_field() ?>

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
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="col-12">
                        <div class="cart-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                        <!-- /.cart-table -->
                        <div class="row cart-btn-section">

                            <!-- /.col-xl-6 -->
                            <div class="col-12 col-sm-4 col-lg-12">
                                <div class="cart-btn-left">
                                    <a href="javascript:;" class="btn btn-success" id="update-cart-btn">Update Cart</a>
                                </div>
                            </div>
                            <!-- /.col-xl-6 -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="cart-subtotal text-left">


                        <table class="table">
                            <tr>
                                <td>
                                    <strong>Total Harga</strong>
                                </td>
                                <td>
                                    <p id="totalProduk">0</p>
                                </td>
                            </tr>
                            <tr class="nominal-error" hidden="">
                                <td class="text-danger nominal-min">Nominal bayar kurang</td>
                            </tr>
                            <tr>
                                <td><strong>Bayar</strong></td>
                                <td>
                                    <input type="number" class="form-control bayar-input" name="bayar" id="bayar" required>
                                </td>
                            </tr>
                        </table>

                        <div class="text-left mt-4">
                            <button class="btn btn-primary" type="submit" onclick="input()">Checkout</button>
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
                $('#product').html(response);
                bindAddToCartEvents()
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
    // Fungsi untuk memeriksa apakah keranjang kosong
    function isCartEmpty() {
        const cartData = localStorage.getItem('cart');
        if (cartData) {
            const cart = JSON.parse(cartData);
            return cart.length === 0;
        }
        return true;
    }

    // Fungsi untuk mengatur status tombol checkout
    function updateCheckoutButtonStatus() {
        const checkoutButton = document.querySelector('button[type="submit"]');
        const cartEmpty = isCartEmpty();
        checkoutButton.disabled = cartEmpty;
    }
    document.addEventListener('DOMContentLoaded', updateCheckoutButtonStatus);
</script>

<script>
    displayCartItems();
    updateCart();


    function getCartData() {
        const cartData = localStorage.getItem('cart');
        if (cartData) {
            return JSON.parse(cartData);
        }
        return [];
    }

    function displayCartItems() {
        const cart = getCartData();

        const cartTableBody = document.querySelector('.cart-table tbody');

        cartTableBody.innerHTML = '';

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
                    removeFromCart(item.id);
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
            quantityInput.min = 0; // Tetapkan nilai minimum

            // Tambahkan event listener untuk memastikan nilai tidak kurang dari 0
            quantityInput.addEventListener('input', function() {
                if (quantityInput.value < 0) {
                    quantityInput.value = 0; // Kembalikan nilai ke 0 jika kurang dari 0
                }
            });

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
            priceSpan.textContent = `${item.quantity} x ${formattedPrice}`;
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

    // Call the function to initially display cart items

    function bindAddToCartEvents() {

        document.querySelectorAll('.addtocart').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                // Extract product details
                let productElement = button.closest('.produk');
                let productId = button.id.split('-')[1];
                let productName = productElement.querySelector('#namaProduk').textContent;
                let final_price = parseFloat(button.parentElement.querySelector('#hargaProduk').textContent.replace('Rp. ', '').replace(',', ''));
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                let existingProduct = cart.find(item => item.productId === productId);

                if (existingProduct) {
                    // Jika produk sudah ada, tambahkan jumlahnya
                    existingProduct.quantity += 1;
                } else {
                    // Jika produk belum ada, tambahkan sebagai item baru
                    cart.push({
                        productId,
                        productName,
                        final_price,
                        quantity: 1
                    });
                }

                localStorage.setItem('cart', JSON.stringify(cart));

                // Optionally, provide feedback to the user
                displayCartItems();
                updateCart()
                updateCheckoutButtonStatus()
            });
        });
    }


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
                // console.log(newQuantity);

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
        totalProduk.innerHTML = `${rupiahHargaProduk}`;

        // Simpan data keranjang kembali ke localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    document.getElementById('update-cart-btn').addEventListener('click', updateCart);

    function removeFromCart(productId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        let indexToRemove = cart.findIndex(item => item.id === productId);

        // Jika produk ditemukan di keranjang, hapus produk tersebut
        if (indexToRemove !== -1) {
            cart.splice(indexToRemove, 1);
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        displayCartItems();

    }

    function cekDiscountQuantity(productId, newQuantity) {
        const user_id = document.querySelector('#user_id').value;
        const token = document.querySelector('[name=_token]').value;

        $.ajax({
            url: "<?php echo routeTo('commerce/cekDiscountQuantity') ?>",
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
                console.log(response);
                // Iterasi setiap produk dalam respons
                let cartProduct = cartData.find(item => item.productId == response.data.id_product);
                if (cartProduct) {
                    cartProduct.final_price = response.data.final_price;
                }
                // Menyimpan kembali data yang sudah diperbarui ke dalam localStorage
                localStorage.setItem('cart', JSON.stringify(cartData));
      
                displayCartItems();
                updateCart();

            },
            error: function(xhr, status, error) {
                console.error(`Error: ${error}`);
            }
        });
    }

    function hapusCart() {
        localStorage.clear();
    }

    function input() {
        storeCartDataInForm();
        hapusCart();
        document.querySelector('#formDataBarang').submit()
    }


    function storeCartDataInForm() {
        const cart = getCartData();
        const form = document.querySelector('#formDataBarang');
        const productsInput = document.createElement('input');

        productsInput.type = 'hidden';
        productsInput.name = 'cartData';

        const cartDataString = JSON.stringify(cart);
        productsInput.value = cartDataString;

        form.appendChild(productsInput);

        const totalProdukElement = document.querySelector('#totalProduk');

        function extractNumericValue(text) {
            return parseInt(text.replace(/[^0-9]/g, ''), 10);
        }

        const totalProdukValue = extractNumericValue(totalProdukElement.textContent);

        const totalProdukInput = document.createElement('input');
        totalProdukInput.type = 'hidden';
        totalProdukInput.name = 'totalProduk';
        totalProdukInput.value = totalProdukValue;

        form.appendChild(totalProdukInput);
    }
</script>