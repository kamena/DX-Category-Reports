<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
	
	<h2><?php _e( "DX Category Reports", 'dxcr' ); ?></h2>
	
	<?php 
		$category_id = 0;
		
		// Use 0 as a basis, still absint the remote call. 
		// That happens only in the admin.
		if ( isset( $_POST['category_id'] ) ) {
			$category_id = absint( $_POST['category_id'] );
		}
	?>
	
	<form action="" method="post">
		<div class="reports-settings">
			<p><?php _e( "Pick a category and fetch the monthly reports for it.", 'dxbase' ); ?></p>
			
			<select name="category_id">
			<?php 
			// Pull all categories. Could be redone for all terms per CPT later.
			$categories = get_categories();
	
			foreach( $categories as $category ): ?>
				<option value="<?php echo $category->term_id; ?>" <?php selected( $category_id, $category->term_id, true ); ?>><?php echo $category->name; ?></option>
			<?php endforeach; ?>
			</select>
			
			<input id="report-builder" type="submit" value="Build a report" />
		</div>
	</form>
	
	<div class="reports-body">
	
		<table id="reports-table" class="reports-table">
			<thead>
				<th><?php _e( "Date", 'dxcr' ); ?></th>
				<th><?php _e( "Published Posts", 'dxcr' ); ?></th>
			</thead>
			<tbody>
			<?php 
			if ( 0 != $category_id ):
				// Fetch the report for the selected category
				$category_report = DX_Database_Time_Manager::get_cpt_category_report( $category_id );
				
				if ( ! empty( $category_report ) ):
					foreach( $category_report as $entry ):
			?>
				<tr>
					<td><?php printf( '%d.%d', $entry->month, $entry->year ); ?></td>
					<td><?php echo $entry->count; ?></td>
				</tr>
			<?php 
					endforeach;
				endif;
			?>
			<?php 
			endif; ?>
			</tbody>
		</table>
	</div>
</div>