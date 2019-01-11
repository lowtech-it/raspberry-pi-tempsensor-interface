<?php

    echo 'RUN';
	//phpinfo();
	bcscale(2);

	$servername = "localhost";
	$username = "temperature";
	$password = "IBouPQLTY1L7aciC";
    $contents = '';
    $hostname = 'temperature.ash.to';
	
	$temperature = 0;

	// Create connection
	$conn = mysqli_connect($servername, $username, $password);

	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	/* change db to world db */
	$conn->select_db("temperature");

	// get contents of a file into a string
	$filename = "/sys/bus/w1/devices/28-5c7bbc116461/w1_slave";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

    if($contents != '') {
        $temperature = substr($contents, strpos($contents, 't=',5)+2);
        $temperature = $temperature+0;
        $temperature = bcdiv($temperature,'1000',2);

        $conn->query("INSERT INTO temperatures  (temp_val) VALUES ($temperature)") or die("Query non valida: " . mysql_error());

        $conn->close();
    }

    $tempcheck = file_get_contents('http://'.$hostname.'/index.php?tempcheck=1') //chiamo la pagina index.php passando tempcheck=1 per invocare il controllo temperatura e l'eventuale invio email di warning
?>
