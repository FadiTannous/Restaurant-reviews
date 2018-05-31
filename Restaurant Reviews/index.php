
<?php
session_start();
define("REVIEW_PATH",     "restaurant_reviews.xml");
$saveSuccess = false;
extract($_POST);
$xml = simplexml_load_file(REVIEW_PATH);
if (isset($drpRestaurants) ) {
    $selectedRestaurant = $xml->xpath('/restaurants/restaurant[@name="' . $drpRestaurants . '"]');
	$address = $selectedRestaurant[0]->address->location . " " .
        $selectedRestaurant[0]->address->city . " " .
        $selectedRestaurant[0]->address->province . " " .
        $selectedRestaurant[0]->address->postalcode;
    $summary = $selectedRestaurant[0]->summary;
}
if (isset($_POST['submitSave'])) {
    $xml = simplexml_load_file(REVIEW_PATH);
    $selectedRestaurant = $xml->xpath('/restaurants/restaurant[@name="' . $drpRestaurants . '"]');
    $selectedRestaurant[0]->summary = $_POST['summary'];
    $selectedRestaurant[0]->rating = intval($rating);
    $xml->asXML(REVIEW_PATH);
    $saveSuccess = true;
    $address = $selectedRestaurant[0]->address->location . " " .
        $selectedRestaurant[0]->address->city . " " .
        $selectedRestaurant[0]->address->province . " " .
        $selectedRestaurant[0]->address->postalcode;
    $summary = $selectedRestaurant[0]->summary;
}
?>

<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
<center>
<head>
    <title>Online Restaurant Review</title>
    <script>
        function onRestaurantChanged()
        {
            var reviewForm = document.getElementById('index');
            reviewForm.submit();
        }
    </script>
</head>
    
<div class="container">
    <h1>Online Restaurant Reviews</h1><br>
<form action="index.php" method="post" id="index">

    
    <label for="drpPassenger">Restaurant:</label>&nbsp;&nbsp;
   
        <select name="drpRestaurants" onchange="onRestaurantChanged();">
            <option>Select...</option>
        <?php
            foreach($xml->restaurant as $node) {
                foreach ($node->attributes() as $name) {
                    if ($_POST["drpRestaurants"] == $name) {
                        echo('<option value="'. $name . '" SELECTED>' .$name . "</option>");
                    } else {
                        echo('<option value="'. $name . '" >' . $name . "</option>");
                    }
                }
            }
        ?>
        </select><br><br>


    <div style="width:50%";>
    <?php
        if(isset($address) && isset($summary)) {
            echo "<label for='address'>Address:</label><br>";
            echo "<textarea id='address' readonly='readonly' rows='5' cols='30'>$address</textarea> ";
            echo "<br><br>";
            echo "<label for='summary'>Summary:</label><br>";
            echo "<textarea id='summary' name='summary' rows='5' cols='30'>$summary</textarea> ";
            echo '<br><br>';
            echo '<label for=\'rating\'>Rating:</label>&nbsp;&nbsp;';
            echo "<select id=\"rating\" name='rating'>";
            for ($i = 1; $i < 6; $i++) {
                if ($selectedRestaurant[0]->rating == $i) {
                    echo"<option value='$i' selected>$i</option>";
                } else {
                    echo"<option value='$i'>$i</option>";
                }
            }
            echo '</select>';
        echo '<br><br><br><button name="submitSave" type="submit">Save Changes</button>';
        if($saveSuccess){
            echo "<p>Save successful</p>";
        }
        }
    ?>
    </div>
</form>
</div>
</body>
</center>
</html>

