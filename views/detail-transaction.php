<?php get_header() ?>
<style>
    table td img {
        max-width: 150px;
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
                <label for="staticEmail" class="col-sm-1 col-form-label">Invoice</label>
                <div class="col-sm-11">
                    <span class="form-control-plaintext"><?= $invoice->code; ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-1 col-form-label">Name</label>
                <div class="col-sm-11">
                    <span class="form-control-plaintext"><?= $invoice->user_name ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-1 col-form-label">Total Amount</label>
                <div class="col-sm-11">
                    <span class="form-control-plaintext">Rp. <?= number_format($invoice->total_amount); ?></span>
                </div>
            </div>

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