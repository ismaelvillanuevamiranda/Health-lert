<?php 

 $getDisease = $_POST[disease];
if($getDisease != ''){
        $output = shell_exec("./getStats.bash $getDisease");
        //echo "=================$output===============";
        $outputExplode = explode("@", $output);
        //echo count($outputExplode[0]);
        //echo count(explode("*",$outputExplode[0]));
        if(count(explode("*",$outputExplode[0])) > 1){
              //echo "LEN: ".count($outputExplode[0])."";

              $symptoms = explode("**", $outputExplode[0]);
              //arsort($symptoms, SORT_NUMERIC, true);
              
              $length = count($symptoms);
              if($length>5){$length=5;}

              for($i=0; $i< $length; $i++){
                $temp = explode("*", $symptoms[$i]);
                  if($temp[1] != ""){
                  $TFIDF[$i] = $temp[0];
                  $SYMPTOM[$i] = $temp[1];;
                  $symptomsList = $symptomsList." '$temp[1]'";
                  $symptomsMessage = $symptomsMessage."<b>$SYMPTOM[$i]:</b> $TFIDF[$i]<br>";
                }

              }





              //+++++++++++++++++++++++++++++++++++++++++++++

              $similarDisease = explode("**", $outputExplode[1]);
              arsort($similarDisease, SORT_NUMERIC, true);

              for($i=0; $i<count($similarDisease); $i++){
                  $similarDiseaseExplode = explode("*", $similarDisease[$i]);
                  if($similarDiseaseExplode[1] != ""){
                      $similarDiseaseMessage = $similarDiseaseMessage. "<b>$similarDiseaseExplode[1]:</b> $similarDiseaseExplode[0]<br>";
                  }
              }
              //print_r($symptoms);
              //echo "<br><br>";
              //print_r($similarDisease);
              //echo $output;




              //=============================================================
              //echo $symptomsList;
              $nodeJS = shell_exec("/home/ec2-user/.nvm/versions/node/v4.4.5/bin/node gt.js $symptomsList");

              $nodeJSexploded = explode("**", $nodeJS);
              for($i=0; $i<count($nodeJSexploded); $i++){
                  $data = explode("*", $nodeJSexploded[$i]);
                if($data[1] != ""){
                  //echo "$data[0] $data[1] $data[2] $data[3]<br>";
                  //echo "<b>$getDisease</b>";
                  $message = "<center><h3><b>$data[0]<br>$getDisease</b></h3></center><hr><br><b>Symptom - TFIDF</b><br>".$symptomsMessage."<hr><br><b>Google Trends (%):</b><br>$data[1]<hr><br><b>Disease: Similarity</b><br>$similarDiseaseMessage";
                  $markersPredicted = $markersPredicted."addMarker(map,$data[2],$data[3],'$message');";
                }
              }
              //echo $markersPredicted;

              //echo "$nodeJS";
              //=============================================================



        }
}

function parser(){
  $file_lines = file('http://www.who.int/csr/don/archive/year/2018/en/');
  $markers = "";
  $limit = 11;
  $i=0;
  foreach ($file_lines as $line) {
              if (strpos($line, '<!-- date -->') !== false) {
                $lineExploded = explode(">",$line);

                $lineExploded_link = explode('"', $lineExploded[0]);
                $LINK = $lineExploded_link[1];

                $lineExploded_date = explode('<', $lineExploded[1]);
                $DATE = $lineExploded_date[0];
              } 


            if (strpos($line, '<!-- title -->') !== false) {
              $lineExploded = explode(">",$line);     

              $lineExploded_Outbreak = explode('<', $lineExploded[2]);
              $OUTBREAK = $lineExploded_Outbreak[0];      
              $OUTBREAKExploded = explode("– ",$OUTBREAK);

                    if(strpos($markers,$OUTBREAKExploded[1]) == false){
                      if($i < $limit){
                        $markers = $markers."geocodeAddress(geocoder, map,\"$OUTBREAKExploded[1]\",\"$OUTBREAKExploded[0]\",\"$LINK\");";
                        $i = $i+1;
                      }
                    }
                    echo '
                    <div class="card border-danger mb-3" style="max-width: 18rem;">
                      <div class="card-header">'.$DATE.'</div>
                      <div class="card-body text-danger">
                        <h5 class="card-title">'.$OUTBREAKExploded[0].'</h5>
                        <p class="card-text"><b>Location:</b> '.$OUTBREAKExploded[1].'</p>
                        <p class="card-text"><a href="http://www.who.int'.$LINK.'" target="_blank">+ information</a></p>
                      </div>
                    </div>
                    ';
                    //$i = $i + 1;
            }
  }
  return $markers;  
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


    <style>

      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }


      .wrapper {
        position: relative;
        width: 100%;
        height: 100%;
      }

.content {
  position: absolute;
  padding: 20px;
  top: 0px;
  left: 0px;
  width: 300px;
  height: 100%;
  background: white;
  overflow: scroll;
  font-family: 'Roboto', sans-serif;
}      

    </style>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">    
  </head>
  <body>


<div class="wrapper">
  <div id="map"></div>
  <div class="content">
    <center><h5>Search possible outbreak</h5></center>
    <hr>
    <div>
        <form action="index.php" method="post">
          <div class="form-group">
            <label for="exampleInputEmail1">Disease</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Type a disease" name="disease">
            <small id="emailHelp" class="form-text text-muted">E.g., Cholera, Influenza, etc.</small>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>    
    </div>

<hr>

    <center><h5>Latest outbreaks diseases</h5><span class="badge badge-pill badge-success">New</span></center>
    <hr>
    <div>
      <?php $m = parser(); ?>
    </div>    

  </div>
</div>

    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat:24.976099 , lng: -55.220520},
          zoom: 3
        });


        var geocoder = new google.maps.Geocoder();

        addMarker(map,-25.363,131.044);
        <?php echo $markersPredicted; ?>
        <?php echo $m; ?>
        //geocodeAddress(geocoder, map,"Saudi Arabia");



      function geocodeAddress(geocoder, resultsMap, add, message,url) {
        // var address = document.getElementById('address').value;
        setTimeout(function(){}, 2000);
        var address = add;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location,
              icon: 'https://cdn4.iconfinder.com/data/icons/32x32-free-design-icons/32/Danger.png'
            });
            attachSecretMessage(marker,message,url);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }


        function attachSecretMessage(marker, message,url) {
          var infowindow = new google.maps.InfoWindow({
            content: "<center><p><h2>"+message+"</h2></p><iframe src=\"http://www.who.int"+url+"\" width='500px' height='300px' frameborder='0'></iframe></center>"
          });

          marker.addListener('click', function() {
            infowindow.open(marker.get('map'), marker);
          });
        } 

        function attachMessage(marker, message) {
          var infowindow = new google.maps.InfoWindow({
            content: message
          });

          marker.addListener('click', function() {
            infowindow.open(marker.get('map'), marker);
          });
        }              


        function addMarker(map,latt,lonn,message){
            var marker = new google.maps.Marker({
              position: {lat: latt, lng: lonn},
              map: map,
            });
            attachMessage(marker,message);     
        }

      }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOURAPIHERE&callback=initMap"
    async defer></script>
  </body>
</html>
