
<?php 
//enters recipe details
	//temporaliry!
$username = 'kokolina';
require_once('functions.php');
connect_database($connect);
if (!empty($_GET)) {
	//recipe_id
	$id_rec = $_GET['id_rec'];
	//num products
	$num = $_GET['num'];
} else {
	$id_rec= "";
	$num = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>	
	<form method="post" action="enter_recipe_details.php">
		<input type="hidden" name="id_rec" value="<?php echo $id_rec?>">
		<input type="hidden" name="num" value="<?php echo $num?>">
		<!--recipe products and measures-->
		<?php 
		for ($i=0; $i < $num; $i++) { 
			$q = "SELECT * FROM `products` ORDER BY `product`";
			$result = mysqli_query($connect, $q);
			$pr = $i+1;
			echo "<p><label for='prod'".$i."'>Продукт".$pr."</label>";
			echo "<select name='product[]' id='prod".$i."'>";
			if (mysqli_num_rows($result)>0) {
				while ($row = mysqli_fetch_assoc($result)) {	
					$product = $row['product'];
					$id_prod = $row['id'];				
					echo "<option value='$id_prod'>$product</option>";
				}
			}
			echo "</select></p>";	

			echo "<label for='quant'>Количество</label>";
			echo "<input type='number' name='quantity[]' id='quant'>";

			$q_m = "SELECT * FROM `measures` ORDER BY `measure`";
			$result_m = mysqli_query($connect, $q_m);
			echo "<p><label for='meas'".$i."'>мерна единица</label>";
			echo "<p><select name='measure[]' id='meas".$i."'>";
			if (mysqli_num_rows($result_m)>0) {
				while ($row_m = mysqli_fetch_assoc($result_m)) {
					$measure = $row_m['measure'];
					$id_meas = $row_m['id'];						
					echo "<option value='$id_meas'>$measure</option>";
				}
			}
			echo "</select></p>";
			
					}

		?>
		<label for="description">Начин на приготвяне на рецептата</label>
		<textarea name="description" id="description"></textarea>
		<input type="submit" value="submit" name="submit">
	</form>
	<?php
	if (isset($_POST['submit'])) {
		$id_rec = $_POST['id_rec'];
		$quantity =  $_POST['quantity'];
		$measure = $_POST['measure'];
		$product = $_POST['product'];
		$description = $_POST['description'];
	//num of prod
		$num = $_POST['num'];
		$flag = 0;
		$flag_des = 0;
		for ($k = 0; $k < $num; $k++) { 
				//ид продукт
			$id_prod = $product[$k];
			//ид мерна единица
			$id_meas = $measure[$k];
			//количество
			$quant = $quantity[$k];			
			$q_r = "INSERT INTO `recipe_products_quantities`
			(`recipe_id`, `product_id`, `measures_id`, `quantity`) 
			VALUES ($id_rec, $id_prod, $id_meas, $quant)";
			if (mysqli_query($connect, $q_r)) {
				$flag = 1;
			} else {
				$flag = 0;
			}
		}
		//запис на описанието на рецептата
		$q_des = "	UPDATE `recipes` 
		SET `description`='$description'
		WHERE `id` = $id_rec ";
		if (mysqli_query($connect, $q_des)) {
			$flag_des = 1;
		} else {
			$flag_des = 0;
		}
		if ($flag == 1 && $flag_des == 1) {
			echo "Успешно записахте всички продукти и описание!";
		} else {
			echo "Опитайте отново!";
		}

	}


	?>
	<a href="delete_recipe_details.php?id_rec=<?php echo $id_rec?>">Изтрий</a>
	<a href="update_recipe_details.php?id_rec=<?php echo $id_rec?>&num=<?php echo $num; ?>">Промени</a>

</body>
</html>