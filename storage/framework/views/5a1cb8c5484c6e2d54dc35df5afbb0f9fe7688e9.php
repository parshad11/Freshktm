<div class="table-responsive">
    <table class="table table-bordered table-striped table-text-center" id="profit_by_customer_table">
        <thead>
            <tr>
                <th><?php echo app('translator')->getFromJson('contact.customer'); ?></th>
                <th><?php echo app('translator')->getFromJson('lang_v1.gross_profit'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 footer-total">
                <td><strong><?php echo app('translator')->getFromJson('sale.total'); ?>:</strong></td>
                <td><span class="display_currency footer_total" data-currency_symbol ="true"></span></td>
            </tr>
        </tfoot>
    </table>
</div><?php /**PATH C:\Users\HP\Project\FreshKtm\Freshktm-\resources\views/report/partials/profit_by_customer.blade.php ENDPATH**/ ?>