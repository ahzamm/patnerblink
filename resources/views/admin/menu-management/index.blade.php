<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
	#loadingnew{
		display: none;
	}
	table.tax_table{
		margin-top: 0 !important;
	}
	.tax_table td{
		border: 1px solid #000;
	}
	.tax_table>thead>tr>th{
		position: sticky;
		top: 0;
	}
	.USMLCheckbox{
		width:25px;
		height:25px;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<section class="box">
				<div class="header_view" style="padding-top: 20px;">
					<h2 style="font-size: 26px;">User Side Menu Access Management
						<span class="info-mark" onmouseenter="popup_function(this, 'menu_usersside_access_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				<div class="content-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="menu-management">
							<thead>
								<tr>
									<th>#</th>
									<th>Menu</th>
									<th>Sub Menu</th>
									<th>Manager</th>
									<th>Reseller</th>
									<th>Contractor</th>
									<th>Trader</th>
									<th>Sub-Trader</th>
									<th>Help Desk</th>
								</tr>
							</thead>
							<tbody>
								<?php $count = 1;?>
								@foreach($menu_management as $menu)
								<tr>
									<td>{{$count}}</td>
									<td>{{$menu->menu}}</td>
									<td>{{$menu->submenu}}</td>
									<td>
										<?php
										$managerCheck = 'unchecked';
										if($menu->manager == 1){ $managerCheck = 'checked'; }
										?>
										<input type="checkbox" class="USMLCheckbox" <?= $managerCheck;?> data-id="<?= $menu->id;?>" data-col="manager">
									</td>
									<td>
										<?php
										$resellerCheck = 'unchecked';
										if($menu->reseller == 1){ $resellerCheck = 'checked'; }
										?>
										<input type="checkbox" class="USMLCheckbox" <?= $resellerCheck;?> data-id="<?= $menu->id;?>" data-col="reseller">
									</td>
									<td>
										<?php
										$dealerCheck = 'unchecked';
										if($menu->dealer == 1){ $dealerCheck = 'checked'; }
										?>
										<input type="checkbox" class="USMLCheckbox" <?= $dealerCheck;?> data-id="<?= $menu->id;?>" data-col="dealer">
									</td>
									<td>
										<?php
										$subdealerCheck = 'unchecked';
										if($menu->subdealer == 1){ $subdealerCheck = 'checked'; }
										?>
										<input type="checkbox" class="USMLCheckbox" <?= $subdealerCheck;?> data-id="<?= $menu->id;?>" data-col="subdealer">
									</td>
									<td>
										<?php
										$traderCheck = 'unchecked';
										if($menu->trader == 1){ $traderCheck = 'checked'; }
										?>
										<input type="checkbox" class="USMLCheckbox" <?= $traderCheck;?> data-id="<?= $menu->id;?>" data-col="trader">
									</td>
									<td>
										<?php
										$inhouseCheck = 'unchecked';
										if($menu->inhouse == 1){ $inhouseCheck = 'checked'; }
										?>
										<input type="checkbox" class="USMLCheckbox" <?= $inhouseCheck;?> data-id="<?= $menu->id;?>" data-col="inhouse">
									</td>
								</tr>
								<?php $count++;?>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</section>
	</section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$(document).on('change','.USMLCheckbox',function(){
		if ($(this).is(':checked')) {
			var status = 1;
		}else{
			var status = 0;		
		}
		var id = $(this).attr('data-id');
		var col = $(this).attr('data-col');
//
$.ajax({
	type: 'POST',
	url: "{{route('admin.submenu.update')}}",
	data:{
		status : status, id : id, col : col,
	},
	success:function(res){
// alert(res);
},
error:function(jhxr,status,err){
	console.log(err);
},
})
});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#menu-management').DataTable();
	} );
</script>
@endsection
<!-- Code Finalize -->