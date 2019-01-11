<?php

	if(!$_GET) {

?>
        <!DOCTYPE HTML>
        <html>
            <head>
                <meta charset="UTF-8">
        
                <script src="./script/jquery-1.6.4.min.js" type="text/javascript"></script>
                <script src="./node_modules/moment/min/moment.min.js"></script>
                <script src="./node_modules/chart.js/dist/Chart.js"></script>
            </head>
            <body>
                <div style="text-align: center;">
                    <h1>TEMPERATURE</h1>
                    <div style="color: #FFFFFF; background-color: #FF0000; font-size: 1.5em; font-weight: bold; width: 600px; align: center; margin: auto;" id="alarm"></div>
                </div>
            <div class="content" id="id1" style="margin-top: 10px;">
                <div class="chart-container" id="div1" style="position: relative; height:30vh; width:80vw; margin: auto;">
                    <canvas id="myChart" style="display: block; border: 1px solid black;"></canvas>
                </div>
            </div>

            <div class="content" id="id2" style="margin-top: 10px;">
                <div id="chart-container2" id="div2" style="position: relative; height:30vh; width:80vw; margin: auto;">
                    <canvas id="myChart2" style="display: block; border: 1px solid black;"></canvas>
                </div>
            </div>
            
            <div class="content" id="id3" style="margin-top: 10px;">
                <div id="chart-container3" id="div3" style="position: relative; height:30vh; width:80vw; margin: auto;">
                    <canvas id="myChart3" style="display: block; border: 1px solid black;"></canvas>
                </div>
            </div>
            <div class="content" id="id4" style="margin-top: 10px;">
                <div id="chart-container4" id="div4" style="position: relative; height:30vh; width:80vw; margin: auto;">
                    <canvas id="myChart4" style="display: block; border: 1px solid black;"></canvas>
                </div>
            </div>
            <script src="./script/chart2.js"></script>
            </body>
        </html>



<?php
        
	} else {

        include ('config.php');
        
        $temp_last_val_Arr = array(); //array delle ultime tot letture
        $count_max = 0; //conteggio di quanti valori su $letture_overtemp considerati ha superato la soglia 
        $count_min = 0; //conteggio di quanti valori su $letture_overtemp considerati ha superato la soglia
        $last_temp = 0; //ultimo valore di temperatura rilevato
        
        $temperature = 0;
        $temp_Arr = array();
        $temp_Arr_day = array();
        $temp_Arr_week = array();
        $temp_Arr_month = array();
        $temp_Arr_year = array();
        $counter = 0;
        $now = time();
        $day = $now - 86400;
        $week = $now - 604800;
        $month = $now - 2678400;
        $year = $now - 31536000;
        

        if(isset($_GET['tempcheck']) && $_GET['tempcheck'] == '1') { //variabile di controllo per verificare se lo script e' stato chiamato da gettemp o direttamente dal browser

            $tempcheck = 1;

        } else {

            $tempcheck = 0;

        }
                
        // Create connection
        $conn = mysqli_connect($servername, $username, $password);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        /* change db to world db */
        $conn->select_db("temperature");

        $temperatures_query = $conn->query("SELECT temp_id, temp_val, temp_timestamp FROM temperatures ORDER BY temp_timestamp DESC") or die("Query non valida: " . mysql_error());

        $count = $temperatures_query->num_rows; //conteggio dei risultati
        
        while($row = mysqli_fetch_array($temperatures_query)) {

            if($counter < $letture_overtemp) { $temp_last_val_Arr[] = $row['temp_val'];} //array con i valori delle ultime $letture_overtemp letture

            if($tempcheck == 0) {
                
                $timestamp = strtotime($row['temp_timestamp']);

                if($timestamp >= $week) {

                    $temp_Arr_week[$counter][0] = strtotime($row['temp_timestamp'])*1000; 
                    $temp_Arr_week[$counter][1] = $row['temp_val'];

                }

                if($timestamp >= $day) {

                    $temp_Arr_day[$counter][0] = strtotime($row['temp_timestamp'])*1000; 
                    $temp_Arr_day[$counter][1] = $row['temp_val'];

                }

                if($timestamp >= $month) {

                    $temp_Arr_month[$counter][0] = strtotime($row['temp_timestamp'])*1000; 
                    $temp_Arr_month[$counter][1] = $row['temp_val'];

                }
                if($timestamp >= $year) {

                    $temp_Arr_year[$counter][0] = strtotime($row['temp_timestamp'])*1000; 
                    $temp_Arr_year[$counter][1] = $row['temp_val'];

                }
            }

            $counter++;
        }
        
        $conn->close();

        //conteggi per attivazione allarme temperatura
        foreach($temp_last_val_Arr as $k=>$v) {

            if($v >= $temp_max) {

                $count_max++;

            }
        }
        foreach($temp_last_val_Arr as $k=>$v) {

            if($v <= $temp_min) {
                
                $count_min++;

            }
        }

        $temp_Arr['day'] = $temp_Arr_day;
        $temp_Arr['week'] = $temp_Arr_week;
        $temp_Arr['month'] = $temp_Arr_month;
        $temp_Arr['year'] = $temp_Arr_year;
        $temp_Arr['count'] = $count;
        $temp_Arr['last_temp_val'] = $temp_last_val_Arr;
        $temp_Arr['alarm_min'] = 0;
        $temp_Arr['alarm_max'] = 0;

            
        if($count_max == $letture_overtemp) {

            $temp_Arr['alarm_max'] = 1;
            $last_temp = $temp_last_val_Arr[0];

            if($tempcheck) {

                InviaMail($last_temp, 'MAX');

            }
        } 

        if($count_min == $letture_overtemp) {
            
            $temp_Arr['alarm_min'] = 1;
            $last_temp = $temp_last_val_Arr[0];

            if($tempcheck) {

                InviaMail($last_temp, 'MIN');

            }
        }
            
        if(!$tempcheck) {
            
            $temp_Arr_json = json_encode($temp_Arr);

            print_r($temp_Arr_json);

        }
	}

    function  InviaMail($temp_val,$warning_type) {

        require_once "lib/PHPMailer/src/PHPMailer.php";
        require_once "lib/PHPMailer/src/SMTP.php";
        require_once "lib/PHPMailer/src/Exception.php";
            
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        $content_folder = 'content/';
        $lockfile = 'maillock.lock';
        $deltatime = 300; //5 min


        if(file_exists('maillock.lock')) {

            if(filemtime('maillock.lock') < (time() - $deltatime)) {

                //se il file di lock e' piu' vecchio di 5 min, lo rimuovo, ne creo uno nuovo e procedo all'invio email
                unlink('maillock.lock');

            } else {

                //non invio le mail
                return;

            }

        }

        if(!file_exists('maillock.lock')) {

            //creo il file
            $handle = fopen($lockfile, 'a') or die('Cannot open file:  '.$filename_setroom); //implicitly creates file
            fwrite($handle, '1');
            fclose($handle); 

        }

        $mail->Subject = 'Temperature Warning';
        $email_content = file_get_contents($content_folder.'content_it.html');
        $email_content = str_replace('{{temp_val}}',$temp_val,$email_content);
        $email_content = str_replace('{{warning_type}}',$warning_type,$email_content);
        
        
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6            
        
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = '587';
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = 'yourmail@gmail.com';
        //Password to use for SMTP authentication
        $mail->Password = 'mailpassword';
        //Set who the message is to be sent from
        $mail->setFrom('yourmail@gmail.com', 'TEMPERATURE LOGGER');
        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');
        //Set who the message is to be sent to

        $mail->addAddress('destination@address.net');
        //$mail->addBCC($GLOBALS['CONFIG']['MAILFROM'],$GLOBALS['CONFIG']['MAILFROM']); //invio una copia a al mittente
        //Set the subject line
        
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $mail->msgHTML($email_content, __DIR__);
        
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        
        //send the message, check for errors
        $status = $mail->send();
        $mail->ClearAddresses();
                          
    }    

?>
