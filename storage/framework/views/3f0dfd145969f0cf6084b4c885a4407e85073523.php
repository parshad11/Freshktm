<?php
	$subtype = '';
?>
<?php if(!empty($transaction_sub_type)): ?>
	<?php
		$subtype = '?sub_type='.$transaction_sub_type;
	?>
<?php endif; ?>

<?php if(!empty($transactions)): ?>
	<table class="table table-slim no-border">
		<?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr class="cursor-pointer" 
	    		title="Customer: <?php echo e(optional($transaction->contact)->name, false); ?> 
		    		<?php if(!empty($transaction->contact->mobile) && $transaction->contact->is_default == 0): ?>
		    			<br/>Mobile: <?php echo e($transaction->contact->mobile, false); ?>

		    		<?php endif; ?>
	    		" >
				<td>
					<?php echo e($loop->iteration, false); ?>.
				</td>
				<td>
					<?php echo e($transaction->invoice_no, false); ?> (<?php echo e(optional($transaction->contact)->name, false); ?>)
					<?php if(!empty($transaction->table)): ?>
						- <?php echo e($transaction->table->name, false); ?>

					<?php endif; ?>
				</td>
				<td class="display_currency">
					<?php echo e($transaction->final_total, false); ?>

				</td>
				<td>
					<a href="<?php echo e(action('SellPosController@edit', [$transaction->id]).$subtype, false); ?>">
	    				<i class="fas fa-pen text-muted" aria-hidden="true" title="<?php echo e(__('lang_v1.click_to_edit'), false); ?>"></i>
	    			</a>
	    			
	    			<a href="<?php echo e(action('SellPosController@destroy', [$transaction->id]), false); ?>" class="delete-sale" style="padding-left: 20px; padding-right: 20px"><i class="fa fa-trash text-danger" title="<?php echo e(__('lang_v1.click_to_delete'), false); ?>"></i></a>

	    			<a href="<?php echo e(action('SellPosController@printInvoice', [$transaction->id]), false); ?>" class="print-invoice-link">
	    				<i class="fa fa-print text-muted" aria-hidden="true" title="<?php echo e(__('lang_v1.click_to_print'), false); ?>"></i>
	    			</a>
				</td>
			</tr>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</table>
<?php else: ?>
	<p><?php echo app('translator')->getFromJson('sale.no_recent_transactions'); ?></p>
<?php endif; ?><?php /**PATH C:\Users\HP\Project\FreshKtm\Freshktm-\resources\views/sale_pos/partials/recent_transactions.blade.php ENDPATH**/ ?>