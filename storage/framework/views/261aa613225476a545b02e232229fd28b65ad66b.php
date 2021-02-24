<?php $__env->startSection('title', __('lang_v1.product_sell_report')); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1><?php echo e(__('lang_v1.product_sell_report'), false); ?></h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <div class="row">
        <div class="col-md-12">
            <?php $__env->startComponent('components.filters', ['title' => __('report.filters')]); ?>
              <?php echo Form::open(['url' => action('ReportController@getStockReport'), 'method' => 'get', 'id' => 'product_sell_report_form' ]); ?>

                <div class="col-md-3">
                    <div class="form-group">
                    <?php echo Form::label('search_product', __('lang_v1.search_product') . ':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="hidden" value="" id="variation_id">
                            <?php echo Form::text('search_product', null, ['class' => 'form-control', 'id' => 'search_product', 'placeholder' => __('lang_v1.search_product_placeholder'), 'autofocus']);; ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('customer_id', __('contact.customer') . ':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <?php echo Form::select('customer_id', $customers, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']);; ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('location_id', __('purchase.business_location').':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <?php echo Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']);; ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('product_sr_date_filter', __('report.date_range') . ':'); ?>

                        <?php echo Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'product_sr_date_filter', 'readonly']);; ?>

                    </div>
                </div>
                <div class="col-md-3">
                    <?php echo Form::label('product_sr_start_time', __('lang_v1.time_range') . ':'); ?>

                    <?php
                        $startDay = Carbon::now()->startOfDay();
                        $endDay   = $startDay->copy()->endOfDay();
                    ?>
                    <div class="form-group">
                        <?php echo Form::text('start_time', \Carbon::createFromTimestamp(strtotime($startDay))->format('H:i'), ['style' => __('lang_v1.select_a_date_range'), 'class' => 'form-control width-50 f-left', 'id' => 'product_sr_start_time']);; ?>

                        <?php echo Form::text('end_time', \Carbon::createFromTimestamp(strtotime($endDay))->format('H:i'), ['class' => 'form-control width-50 f-left', 'id' => 'product_sr_end_time']);; ?>

                    </div>
                </div>
                <?php echo Form::close(); ?>

            <?php echo $__env->renderComponent(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#psr_detailed_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list" aria-hidden="true"></i> <?php echo app('translator')->getFromJson('lang_v1.detailed'); ?></a>
                    </li>
                    <li>
                        <a href="#psr_detailed_with_purchase_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list" aria-hidden="true"></i> <?php echo app('translator')->getFromJson('lang_v1.detailed_with_purchase'); ?></a>
                    </li>
                    <li>
                        <a href="#psr_grouped_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo app('translator')->getFromJson('lang_v1.grouped'); ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="psr_detailed_tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" 
                            id="product_sell_report_table">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->getFromJson('sale.product'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('product.sku'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.customer_name'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('lang_v1.contact_id'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.invoice_no'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('messages.date'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.qty'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.unit_price'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.discount'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.tax'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.price_inc_tax'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.total'); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="bg-gray font-17 footer-total text-center">
                                        <td colspan="6"><strong><?php echo app('translator')->getFromJson('sale.total'); ?>:</strong></td>
                                        <td id="footer_total_sold"></td>
                                        <td></td>
                                        <td></td>
                                        <td id="footer_tax"></td>
                                        <td></td>
                                        <td><span class="display_currency" id="footer_subtotal" data-currency_symbol ="true"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="psr_detailed_with_purchase_tab">
                        <div class="table-responsive">
                            <?php if(session('business.enable_lot_number')): ?>
                                <input type="hidden" id="lot_enabled">
                            <?php endif; ?>
                            <table class="table table-bordered table-striped" 
                            id="product_sell_report_with_purchase_table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->getFromJson('sale.product'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('product.sku'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.customer_name'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.invoice_no'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('messages.date'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('lang_v1.purchase_ref_no'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('lang_v1.lot_number'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('lang_v1.supplier_name'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.qty'); ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="psr_grouped_tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" 
                            id="product_sell_grouped_report_table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->getFromJson('sale.product'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('product.sku'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('messages.date'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('report.current_stock'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('report.total_unit_sold'); ?></th>
                                        <th><?php echo app('translator')->getFromJson('sale.total'); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="bg-gray font-17 footer-total text-center">
                                        <td colspan="4"><strong><?php echo app('translator')->getFromJson('sale.total'); ?>:</strong></td>
                                        <td id="footer_total_grouped_sold"></td>
                                        <td><span class="display_currency" id="footer_grouped_subtotal" data-currency_symbol ="true"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <script src="<?php echo e(asset('js/report.js?v=' . $asset_v), false); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\HP\Project\FreshKtm\Freshktm-\resources\views/report/product_sell_report.blade.php ENDPATH**/ ?>