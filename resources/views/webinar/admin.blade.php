@extends('layouts.base') 
@section('content')

<div id="page-wrapper" class="no-pad">
    <div class="graphs">
    <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
    

<style>
	h1 {
		margin-top: 10px;
	}
	body {
		background: #fff;
		color: #000000;
	}
	a:hover,
	a {
		color: #000000;
	}
	.container {
		/*width: 95%;*/
	}
	.grid-view .filters input {
		width:95%;
	}

	.grid-view table.items th {
		background: #212121;
	}

</style>
	<?php 
        if($isMobile){?>
            @include('mobile_header')
    <?php } ?>

		<div class="container">


			<h1>Manage Webinars</h1>

			<div style="margin-top: 30px;">
				<a href="{{URL('webinar/create')}}" class="button">Add Webinar</a>
			</div>

			<table class="items">
				<thead>
					<tr>
						<th id="webinar-grid_c0"><a class="sort-link" href="{{URL('/webinar/admin?sort=name')}}">Name</a></th>
						<th id="webinar-grid_c1"><a class="sort-link" href="{{URL('/webinar/admin?sort=date')}}">Date</a></th>
						<th class="button-column" id="webinar-grid_c2">&nbsp;</th>
					</tr>
					<tr class="filters">
						<td><input name="Webinar[name]" type="text" maxlength="255"></td>
						<td><input name="Webinar[date]" type="text"></td><td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($webinars as $webinar): ?>	
					<tr>
						<td><?php echo $webinar->name;?></td>
						<td><?php echo $webinar->date;?></td>
						<td class="button-column">
							<a class="view" title="View" href="{{URL('/webinar/'.$webinar->id)}}">
								<img src="{{URL::asset('/assets/1c609167/gridview/view.png')}}" alt="View">
							</a> 
							<a class="update" title="Update" href="{{URL('/webinar/update/'.$webinar->id)}}">
								<img src="{{URL::asset('/assets/1c609167/gridview/update.png')}}" alt="Update">
							</a> 
							<a class="delete" title="Delete" href="{{URL('/webinar/delete/'.$webinar->id)}}">
								<img src="{{URL::asset('/assets/1c609167/gridview/delete.png')}}" alt="Delete">
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

		</div>

		<?php echo $webinars->render();?>

	</div>
  </div>
</div>
@endsection 