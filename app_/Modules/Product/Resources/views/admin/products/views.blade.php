@extends('admin::layout')
@component('admin::components.page.header')
    @slot('title', 'Mostly Viewed Products')

    <li class="active">{{ trans('product::products.products') }}</li>
@endcomponent
@component('admin::components.page.index_table')
<table class="table table-striped table-hover dataTable no-footer">
	<thead>
		<tr role='row'>
			<th>#</th>
			<th>User Name</th>
			<th>User Email</th>
			<th>Product Slug</th>
			<th>Number Of times Viewed</th>
			<th>Send Email</th>
		</tr>
	</thead>
	<tbody>
		@foreach($productData as $data)
		<tr>
			<td><input type="checkbox" class="select-all"></td>
			<td>{{ $data->first_name.' '.$data->last_name}}</td>
			<td>
				{{ $data->email}}
			</td>
			<td>
				{{ $data->slug}}
			</td>
			<td>
				{{ $data->views}}
			</td>
			<td>
				<a href='javaScrip:void()' title="Email" data-user='{{ $data->id }}'  data-product='{{ $data->product_id }}' class="email"><i class="fa fa-envelope"></i></a>
			</td>
			
		</tr>
		@endforeach 
	</tbody>
</table>
@endcomponent
@push('scripts')
<script type="text/javascript">
	$('.email').click(function(){

		var user_id = $(this).attr('data-user');
		var product_id = $(this).attr('data-product');
			console.log(user_id)
		$.ajax({
			url: route('admin.products.email'),
	        type: "POST",
	        data: { id : user_id , product_id : product_id },
	        success: function(data){
	            console.log(data)
            }
		})
	})
</script>
@endpush