<?php

//fetch_data.php

include('database_connection.php');

if(isset($_POST["action"]))
{
	$query = "SELECT products.product_name, products.price, categories.category_name, products.model_code, products.rti_reference, products.effectivity_date, products.currency_rate, fam.family from products left join categories on products.product_category = categories.cat_id left join fam on products.product_family = fam.fam_id
	";
/*	if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"]))
	{
		$query .= "
		 AND product_price BETWEEN '".$_POST["minimum_price"]."' AND '".$_POST["maximum_price"]."'
		";
	} **/
	if(isset($_POST["brand"]))
	{
		$brand_filter = implode("','", $_POST["brand"]);
		$query .= "AND category_name IN('".$brand_filter."')
		";
	}
	if(isset($_POST["ram"]))
	{
		$ram_filter = implode("','", $_POST["ram"]);
		$query .= "AND family IN('".$ram_filter."')
		";
	} 
/*	if(isset($_POST["storage"]))
	{
		$storage_filter = implode("','", $_POST["storage"]);
		$query .= "
		 AND product_storage IN('".$storage_filter."')
		";
	} **/

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();

	?>
	<table class="table table-hover">
	<thead>
	  <tr>
		<th scope="col">Product Name</th>
		<th scope="col">Product Category</th>
		<th scope="col">Product Family</th>
		<th scope="col">Model Code</th>
		<th scope="col">RTI Reference</th>
		<th scope="col">Effectivity Date</th>
		<th scope="col">Currency Rate</th>
		<th scope="col">Price</th>
	  </tr>
	</thead>
	<tbody>
	
	<?php

	$output = '';
	
	if($total_row > 0)
	{
		foreach($result as $row)
		{
			$output .= '
			<tr>
            	<td>'. $row["product_name"] .'</td>
                <td>'. $row["category_name"] .'</td>
                <td>'. $row["family"] .'</td>
                <td>'. $row["model_code"] .'</td>
                <td>'. $row["rti_reference"] .'</td>
                <td>'. $row["effectivity_date"] .'</td>
                <td>'. $row["currency_rate"] .'</td>
                <td>'. $row["price"] .'</td>
            </tr>
			</tbody>
			';
		}
	}
	else
	{
		$output = '<h3>No Data Found</h3>';
	}
	echo $output;
}

?>