<?php $__env->startSection('title', __( 'lang_v1.customer_groups' )); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo app('translator')->getFromJson( 'lang_v1.customer_groups' ); ?></h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.all_your_customer_groups' )]); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customer.create')): ?>
            <?php $__env->slot('tool'); ?>
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="<?php echo e(action('CustomerGroupController@create'), false); ?>" 
                        data-container=".customer_groups_modal">
                        <i class="fa fa-plus"></i> <?php echo app('translator')->getFromJson( 'messages.add' ); ?></button>
                </div>
            <?php $__env->endSlot(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customer.view')): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="customer_groups_table">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->getFromJson( 'lang_v1.customer_group_name' ); ?></th>
                            <th><?php echo app('translator')->getFromJson( 'lang_v1.calculation_percentage' ); ?></th>
                            <th><?php echo app('translator')->getFromJson( 'messages.action' ); ?></th>
                        </tr>
                    </thead>
                </table>
                </div>
        <?php endif; ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="modal fade customer_groups_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\HP\Project\FreshKtm\Freshktm-\resources\views/customer_group/index.blade.php ENDPATH**/ ?>