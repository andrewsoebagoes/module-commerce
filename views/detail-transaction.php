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
                    <span class="form-control-plaintext"><?= $invoice->code; ?></span>
                </div>
            </div>
            <form action="" method="post">

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">

                        <span class="form-control-plaintext"><?= $invoice->status ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <span class="form-control-plaintext"><?= $invoice->user_name ?></span>
                    </div>
                </div>
            </form>

            <div class="form-group row mb-2">
                <label for="staticEmail" class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                    <span class="form-control-plaintext">Rp. <?= number_format($invoice->total_amount); ?></span>
                </div>
            </div>

            <div class="form-group row mb-2">

                <label for="staticEmail" class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                <?php if ($invoice->image) : ?>
                    <div class="col-sm-10">
                        <span class="form-control-plaintext"><a href="<?= routeTo($invoice->image) ?>" target="_blank">Lihat Bukti Pembayaran</a></span>
                    </div>
                <?php else : ?>

                    <div class="col-sm-10">
                        <span class="form-control-plaintext text-danger">Pembeli belum melakukan pembayaran</span>
                    </div>
                <?php endif  ?>
            </div>

            <div class="form-group row mb-2">

            <?php if ($invoice->status != 'Finished') : ?>
                <form action="" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3 col-3">
                        <label for="status" class="form-label">Update Status</label>
                        <select name="status" id="" class="form-control">
                            <option value="">--Pilih Status--</option>
                            <option value="Waiting for Payment">Waiting for Payment</option>
                            <option value="Confirm">Confirm</option>
                            <option value="Finished">Finished</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-outline-primary">Simpan</button>
                </form>
            <?php endif ?>



        </div>




        <div class=" table-hover table-sales">
            <table class="table table-bordered datatable-crud" style="width:100%">
                <thead>
                    <tr>
                        <th width="20px">#</th>
                        <?php
                        foreach ($fields as $field) :
                            $label = $field;
                            if (is_array($field)) {
                                $label = $field['label'];
                            }
                            $label = _ucwords($label);
                        ?>
                            <th><?= $label ?></th>
                        <?php endforeach ?>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?php get_footer() ?>