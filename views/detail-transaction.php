<?php get_header() ?>
<style>
    table td img {
        max-width: 150px;
    }

    element.style {
        display: none;
    }
</style>
<div class="card container" id="print-section">
    <div class="card-body p-4 container">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-6" style="text-align:right">
                <h3>INVOICE #<?= $invoice[0]->code; ?></h3>
                <span class="d-block">Issue Date : <?=date('d-m-Y H:i', strtotime($invoice[0]->created_at))?></span>
                <span><?=strtoupper($invoice[0]->status)?></span>
            </div>
        </div>
        <div class="mb-4 ">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext"><b><?= $invoice[0]->user_name ?></b></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Total</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext">Rp. <b><?= number_format($invoice[0]->total_amount); ?></b></span>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <?php if($shippings): ?>
                    <span class="form-control-plaintext"><?=$shippings?->province . ', ' . $shippings?->city . ', ' . $shippings?->subdistrict . ', ' . $shippings?->address?></span>
                    <?php endif ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Catatan</label>
                <div class="col-sm-10">
                    <?php if($shippings): ?>
                    <span class="form-control-plaintext"><?=$shippings?->notes?></span>
                    <?php endif ?>
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
                </form>
            </div>
            <?php endif ?>

            <div class=" table-hover table-sales mt-3">
                <table class="table table-bordered datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Item Type</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoice as $i => $data) : ?>
                            <tr style="text-align: left;">
                                <td><?= $i+1; ?></td>
                                <td><?= $data->name; ?></td>
                                <td><?= $data->item_type; ?></td>
                                <td><?= $data->quantity; ?></td>
                                <td>Rp. <?= number_format($data->item_price); ?></td>
                                <td>Rp. <?= number_format($data->total_price); ?></td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="6"></td>
                        </tr>
                        <tr style="text-align: left;">
                            <td colspan="4"></td>
                            <td><b>Total</b></td>
                            <td>Rp. <b><?= number_format($invoice[0]->total_amount); ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<center>
    <button class="btn btn-primary" onclick="printSection()"><i class="fas fa-print"></i> Cetak</button>
</center>
<script>
function printSection()
{
    const oldHtml = document.body.innerHTML
    const printEl = document.querySelector('#print-section').innerHTML

    document.body.innerHTML = printEl
    window.print()

    setTimeout(() => {
        document.body.innerHTML = oldHtml
    }, 100);
}
</script>
<?php get_footer() ?>