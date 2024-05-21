<?php get_header() ?>
<style>
    table td img {
        max-width: 150px;
    }

    element.style {
        display: none;
    }
</style>
<div class="card">
    <div class="card-header d-flex flex-grow-1 align-items-center">
        <p class="h4 m-0"><?php get_title() ?></p>

    </div>
    <div class="card-body">
        <?php if ($success_msg) : ?>
            <div class="alert alert-success"><?= $success_msg ?></div>
        <?php endif ?>
        <?php if ($error_msg) : ?>
            <div class="alert alert-danger"><?= $error_msg ?></div>
        <?php endif ?>
        <div class="mb-4 ">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Invoice</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext"><?= $invoice[0]->code; ?></span>
                </div>
            </div>
            <form action="" method="post">

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">

                        <span class="form-control-plaintext"><?= $invoice[0]->status ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <span class="form-control-plaintext"><?= $invoice[0]->user_name ?></span>
                    </div>
                </div>
            </form>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext">Rp. <?= number_format($invoice[0]->total_amount); ?></span>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext"><?=$shippings->province . ', ' . $shippings->city . ', ' . $shippings->subdistrict . ', ' . $shippings->address?></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Catatan</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext"><?=$shippings->notes?></span>
                </div>
            </div>

            <?php if ($invoice[0]->status != 'Finished') : ?>
            <div class="form-group row mb-2">

                <label for="staticEmail" class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                <?php if ($invoice[0]->image) : ?>
                    <div class="col-sm-10">
                        <span class="form-control-plaintext"><a href="<?= routeTo($invoice[0]->image) ?>" target="_blank">Lihat Bukti Pembayaran</a></span>
                    </div>
                <?php else : ?>

                    <div class="col-sm-10">
                        <span class="form-control-plaintext text-danger">Pembeli belum melakukan pembayaran</span>
                    </div>
                <?php endif  ?>
            </div>

            <div class="form-group row mb-2">
                    <form action="" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3 col-3">
                            <label for="status" class="form-label select2">Update Status</label>
                            <select name="status" id="" class="form-control">
                                <option value="">--Pilih Status--</option>
                                <option value="Waiting for Payment" <?= $invoice[0]->status == "Waiting for Payment" ? 'selected' : null ?>>Waiting for Payment</option>
                                <option value="Confirm" <?= $invoice[0]->status == "Confirm" ? 'selected' : null ?>>Confirm</option>
                                <option value="Finished">Finished</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-outline-primary">Simpan</button>
             
                <?php endif ?>
            </div>




            <div class=" table-hover table-sales">
                <table class="table table-bordered datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Item Type</th>
                                <th>Quantity</th>
                                <th>Item Price</th>
                                <th>Total Price</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoice as $data) : ?>
                                <tr style="text-align: left;">
                                    <td><?= $data->name; ?></td>
                                    <td><?= $data->item_type; ?></td>
                                    <td><?= $data->quantity; ?></td>
                                    <td><?= number_format($data->item_price); ?></td>
                                    <td><?= number_format($data->total_price); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>

                    </table>
                </table>
                </form>
            </div>
        </div>
    </div>
    <?php get_footer() ?>