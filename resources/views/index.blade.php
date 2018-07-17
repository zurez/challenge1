@extends("layout")
@section("content")
	<div class="row">
		<div class="col-sm-12">
		<form id="form">
			<div class="form-group">
			<label for="product_name">Product Name</label>
			<input type="text" class="form-control" id="product_name" required="required" placeholder="Product Name">
			
			</div>

			<div class="form-group">
			<label for="quantity">Quantity in Stock</label>
			<input type="number" class="form-control" id="quantity" required="required" placeholder="Quantity">
			</div>
			<div class="form-group">
			<label for="price">Price per Item</label>
			<input type="text" class="form-control" id="price" required="required" placeholder="Price">
			</div>
			<button type="button" class="btn btn-primary" id="submit">Submit</button>
		</form>
		</div>
	</div>	
	<br>
	{{-- Display Area for Records --}}
	<div class="row">
		<div class="col-sm-12">
			<table class="table">
					<tr>
					
						<th>Product&nbsp;Name</th>
						<th>Quantity&nbsp;in&nbsp;Stock</th>
						<th>Price&nbsp;per&nbsp;Item</th>
						<th>DateTime</th>
						<th>Total&nbsp;Value&nbsp;Number</th>
					</tr>
				</thead>
				<tbody id="tbody"></tbody>
				<tfoot>
					<tr>
						<th>
							Total
						</th>
						<th>
							<span id="total"></span>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
@stop
@section("script")

<script type="text/javascript">
	var rows=[];
	var total=0;
	var filename="{{url('fetch/json')}}";
	function load_from_file() {
		url="{{url("fetch/json")}}";
		type="GET";
		
		success=(r)=>{
			for (var i = r.length - 1; i >= 0; i--) {
				row=r[i]
				write_to_row(row);
			}
		}
		error=()=>alert("Couldn't connect to server")
		$.ajax({
			url,
			type,
			success,
			error
		})
	}
	function save_to_file(){
		url="{{url('save/json')}}";
		type="POST";
		data={
			rows,
			"_token": "{{ csrf_token() }}",
		}
		success=(r)=>{
			if (r.status=="failure") {alert("Failed to save")}
		}
		error=()=>alert("Couldn't connect to server")
		$.ajax({
			url,
			type,
			data,
			success,
			error
		})
	}

	function write_to_row(data){
		
		amount=data.price*data.quantity;
		total+=amount;
		$("#total").text(total);
		row=`

			<tr>
				<td>
					`+data.name+`
				</td>
				<td>
					`+data.quantity+`
				</td>
				<td>
					`+data.price+`
				</td>
				<td>
					`+data.datetime+`
				</td>
				<td>
					`+amount+`
				</td>
			</tr>
		`;

		rows.push(data)
		$("#tbody").append(row);

		save_to_file();
	}
	$(document).ready(function(){
		load_from_file()
		$("#submit").click(function(e){
			// Convert data to json
			e.preventDefault();
			price=$("#price").val();
			quantity=$("#quantity").val();
			name=$("#product_name").val();
			datetime=new Date();

			$("#price").val("");
			$("#quantity").val("");
			$("#name").val("");
			data={
				price,
				quantity,
				name,
				datetime
			};

			write_to_row(data);
		})
	})
</script>
@stop