<div class="row m-0 p-0 d-flex justify-content-around mt-2" style="height: 40vh;">
    <div class="col-lg-7 col-md-12 mh-100 overflow-auto p-0 m-0">
        <table class="table table-light table-striped h-100 ">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Vitesse</th>
                    <th>Orientation(Deg)</th>
                    <th>Orientation(cardinal)</th>
                    <th>Lieu</th>
                </tr>
            </thead>
            
            <tbody>
                <?php if($result_historique): ?>
                    <?php foreach($result_historique as $key => $value): ?>
                    <tr>
                        <td><?=$value['id']?></td>
                        <td><?=$value['datetime']?></td>
                        <td><?=$value['wind_speed']?></td>
                        <td><?=$value['deg']?></td>
                        <td><?=$value['cardinal']?></td>
                        <td><?=$value['place']?></td>
                    </tr>
                    <?php endforeach;?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <ul class="list-group col-lg-4 mt-1">
        <li class="list-group-item active text-center" aria-current="true">Monitoring:</li>
        <li class="list-group-item">Date: <?=date('d-m-Y H:i');?></li>
        <li class="list-group-item">Vitesse du vent (km/h): <?=$result_monitoring["wind_speed"];?></li>
        <li class="list-group-item">Orientation (Deg): <?=$result_monitoring["deg"];?></li>
        <li class="list-group-item">Orientation (Cardinal): <?=$result_monitoring["cardinal"];?></li>
        <li class="list-group-item">Mesure (kW): <?=$result_monitoring["production"];?></li>
    </ul>
    
    <div class="col-7 mt-2">
        <canvas id="myChart"></canvas>
    </div>

    <div class="col-4"> <br>
        <div class="row rounded p-2 border">

            <form method="post" id="form_reference">
                <label for="alert_slider">Valeur de référance du module sécurité :</label>
                <input type="range" min="0" max="300" value="<?=$valref?>" name="alert_slider" id="alert_slider">
                <span id="value"><?=$valref?></span>
                <p id="message"></p>
                <?=$data_sanitizer->generate_csrf_input()?>
                <div class="text-center"> <button class="btn btn-dark col-md-3">Envoyer</button></div> 

                <script type="text/javascript" src="./js/SliderValue.js"></script>  
            </form>
        </div>
        <div id="cont_c9f60d79d41ed4767c2d7e00f4cd81bb" class="mt-5"><script type="text/javascript" async src="https://www.theweather.com/wid_loader/c9f60d79d41ed4767c2d7e00f4cd81bb"></script></div> 
        <button class="btn btn-primary mt-3 ms-3"><a class="text-light" href="./logout" style="text-decoration: none;">Se déconnecter</a></button>
    </div>

</div>

<script> // Graph
    const labels = <?=$datetime?>;
        const data = {
            labels: labels,
            datasets:[
                {
                    label:'Vitesse du vent (Km/h)',
                    lineTension:0.2,
                    backgroundColor:'rgb(138, 234, 146)',
                    borderColor: 'rgb(138, 234, 146)',
                    yAxisID: 'y',
                    data: <?=$wind_speed?>
                    
                },
                {
                    label:'Production (W)',
                    lineTension:0.2,
                    backgroundColor:'rgb(255, 132, 0)',
                    borderColor: 'rgb(255, 132, 0)',
                    yAxisID: 'y1',
                    data: <?=$production?>
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            stacked: false,
            options: {
                responsive: true,
                plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                title: {
                    display: true,
                    text: 'Historique capteur'
                }
                },
                hover: {
                mode: 'index',
                intersec: false
                },
                scales: {
                    x: {
                        min: 0,
                        max: 70,
                        ticks: {
                        autoSkipPadding: 20
                        }
                    },
                    y: {
                        min: 0,
                        ticks: {
                        autoSkip: true
                        }
                    },
                    y1: {
                    min: 0,
                    position: 'right',
                    ticks: {
                    autoSkip: true
                    }
                },
                }
            }
        };


        var myChart = new Chart(
            document.getElementById('myChart'),
            config
        )
</script>

<script>//Ajax
    var input = document.getElementById("alert_slider");
    var form = document.getElementById("form_reference");
    

    form.addEventListener('submit', function(event){
        event.preventDefault();
        sendData();
    });

    function sendData(value, message)
    {
        var message = document.getElementById("message");
        var xhr = new XMLHttpRequest();
        var formData = new FormData(form);

        //Si la requête c'est déroulé avec succès :
        xhr.addEventListener("load", function(event){
            console.log(event.target.responseText);
            message.innerHTML = event.target.responseText;
        })

        xhr.addEventListener("error", function(event){
            console.log(event);
        })

        xhr.open("POST", "http://localhost/BTS/",  true); 
        xhr.send(formData); 
    }
</script>