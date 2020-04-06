<?php 

$servername = "localhost";
$username = "user";
$password = "pass";
$db = "books";

$conn = new mysqli($servername, $username, $password, $db);


if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if(!empty($_POST)){
	$buyerNameKey = array_keys($_POST)[0];
	$buyerName = $_POST[$buyerNameKey];
	$bookId = explode("-", $buyerNameKey)[1];

	$sql = "UPDATE wishlist SET shipper_name='$buyerName' WHERE id='$bookId'";
	if ($conn->query($sql) === TRUE) {
		//echo "New record created successfully";
	} else {
		//echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

echo '<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
<section class="section">
    <div class="container">
        <h1 class="title">
            My Wish List
        </h1>
        <p class="subtitle">
            Where my friends know about my literature wish list.
        </p>
    </div>
</section>

<!-- lit table -->
<section class="section">

    <!-- table header -->
    <div class="container is-hidden-mobile">
        <div class="columns">
            <div class="column"> -</div>
            <div class="column"> Book</div>
            <div class="column"> Author</div>
            <div class="column"> Translator</div>
            <div class="column"> Publisher</div>
            <div class="column"> Who Buys</div>
            <div class="column"></div>
        </div>
    </div> ';

    if($result = $conn->query("SELECT * FROM wishlist ORDER BY id;")){
	    if($result->num_rows >0){
		while($row = $result->fetch_assoc()){
			$hasBuyer = isset($row["shipper_name"]);
			if($hasBuyer){
				echo '<div class="box has-background-warning">';
			}else echo '<div class="box">';
			echo '<div class="container">';
			if(!$hasBuyer){
			    echo '<form action="index.php" method="POST">';
			}
			echo '			<div class="columns"> <div class="column">';
			if($hasBuyer) echo '<span>(Someone has already registered)</span>';
			echo '</div>';
			echo <<<EOT
			    <div class="column"><span class="is-hidden-desktop">Book: </span>
				{$row["name"]}
			    </div>
			    <div class="column"><span class="is-hidden-desktop">Author: </span>
				{$row["author"]}
			    </div>
			    <div class="column"><span class="is-hidden-desktop">Translator: </span>
				{$row["translator"]}
			    </div>
			    <div class="column"><span class="is-hidden-desktop">Publisher: </span>
				{$row["publisher"]}
			    </div>
			    <div class="column">
EOT;
				if(!$hasBuyer)
				echo <<<EOT
				<div class="field">
				    <label for="buyer{$row[" id"]}" class="is-hidden-desktop">Who buys? </label>
				    <div class="control">
				        <input class="input" type="text" id="buyer-{$row[" id"]}"
				        name="buyer-{$row["id"]}" placeholder="write your name" >
				    </div>
				</div>
EOT;
				else echo <<<EOT
				<span>{$row["shipper_name"]}</span>
EOT;
				echo '
			    </div>
			    <div class="column"><input class="button is-success" type="submit"
				                       value="save">
			    </div>
			</div> <!-- container -->
';
			if(!$hasBuyer){
			    echo '</form>';
	  	        }
			echo '</div></div>';
		}
	    }
	    $result -> free_result();
    }


echo '</section>
<footer class="footer">
  <div class="content has-text-centered">
    <p>
      M.Rahimy <br/> Check <a href="cv.mrahimy.ir">my CV</a>
      <br/>mail: <a href="mailto:mojtaba.rahimy@chmail.ir">mojtaba.rahimy@chmail.ir</a>
    </p>
  </div>
</footer>
</body></html>';


$conn->close();

?>
