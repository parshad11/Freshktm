<?php
    $transaction_types = [];
    if(in_array($contact->type, ['both', 'supplier'])){
        $transaction_types['purchase'] = __('lang_v1.purchase');
        $transaction_types['purchase_return'] = __('lang_v1.purchase_return');
    }

    if(in_array($contact->type, ['both', 'customer'])){
        $transaction_types['sell'] = __('sale.sale');
        $transaction_types['sell_return'] = __('lang_v1.sell_return');
    }

    $transaction_types['opening_balance'] = __('lang_v1.opening_balance');
?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group">
                <?php echo Form::label('ledger_date_range', __('report.date_range') . ':'); ?>

                <?php echo Form::text('ledger_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']);; ?>

            </div>
        </div>
        <div class="col-md-9 text-right">
            <button data-href="<?php echo e(action('ContactController@getLedger'), false); ?>?contact_id=<?php echo e($contact->id, false); ?>&action=pdf" class="btn btn-default btn-xs" id="print_ledger_pdf"><i class="fas fa-file-pdf"></i></button>

            <button type="button" class="btn btn-default btn-xs" id="send_ledger"><i class="fas fa-envelope"></i></button>
        </div>
    </div>
    <div id="contact_ledger_div"></div>
</div><?php /**PATH C:\Users\HP\Project\FreshKtm\Freshktm-\resources\views/contact/partials/ledger_tab.blade.php ENDPATH**/ ?>