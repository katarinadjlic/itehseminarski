<?php
include "autoload.php";
?>
<!DOCTYPE HTML>

<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Grammy glasanje</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<link href="https://fonts.googleapis.com/css?family=Merriweather:300,400|Montserrat:400,700" rel="stylesheet">
	
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/icomoon.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/bootstrap.css">

	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">

	<link rel="stylesheet" href="css/style.css">

	<script src="js/modernizr-2.6.2.min.js"></script>
	<script src="js/respond.min.js"></script>

	</head>
	<body>
		
	<div class="gtco-loader"></div>
	
	<div id="page">

		<nav class="gtco-nav" role="navigation">
			<div class="gtco-container">
				
				<div class="row">
					<div class="col-sm-2 col-xs-12">
						<div id="gtco-logo"><a href="index.php"><img src="images/logo.png" class="img img-responsive"> </div>
					</div>
					<?php include 'meni.php' ?>
				</div>
				
			</div>
		</nav>

		<div class="gtco-section">
			<div class="gtco-container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 gtco-heading text-center">
                        <input type="hidden" id="korisnikiD" value="<?php echo $_SESSION['ulogovaniKorisnik']->getKorisnikID() ?>">
						<h2>Glasaj, broj preostalih glasova je <span style="color: darkred; font-weight: bold" id="brojGlasovaOstalo"></span></h2>
					    <p id="objasnjenje"></p>
                    </div>
				</div>
			</div>
            <div class="container">
                <div class="row">
                    <label for="osoba">Nominovani</label>
                    <select id="osoba" class="form-control">

                    </select>
                    <label for="kategorija">Kategorija</label>
                    <select id="kategorija" class="form-control">

                    </select>
                    <hr>
                    <button id="glasajDugme" class="btn-success btn-lg" onclick="glasaj()">Glasaj</button>
                    <h2 id="rez"></h2>
                </div>
            </div>
		</div>

        <?php include 'futer.php'; ?>

	</div>

	<div class="gototop js-top">
		<a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
	</div>
	
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.easing.1.3.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/main.js"></script>
    <script>
        $.ajax({
            url: 'kontroler.php?funkcija=kategorije',
            success: function (data) {
                let output = "";
                let nizPodataka = JSON.parse(data);
                
                $.each(nizPodataka,function (i,podatak) {
                    output += "<option value='"+podatak.kategorijaID+"'>";
                    output +=  podatak.nazivKategorije ;
                    output += "</option>";
                });

                $("#kategorija").html(output);
            }
        });
        $.ajax({
            url: 'kontroler.php?funkcija=osobe',
            success: function (data) {
                let output = "";
                let nizPodataka = JSON.parse(data);

                $.each(nizPodataka,function (i,podatak) {
                    output += "<option value='"+podatak.osobaID+"'>";
                    output +=  podatak.imePrezime ;
                    output += "</option>";
                });

                $("#osoba").html(output);
            }
        })
        
        
        function ostaloGlasova() {
            let korisnikID = $("#korisnikiD").val();

            $.ajax({
                url: 'flight/brojGlasovaZaKorisnika/'+korisnikID,
                success: function (data) {
                    let brojGlasovaZaKorisnika = parseInt(data);

                    let ostaloGlasova = 3 - brojGlasovaZaKorisnika;

                    if(ostaloGlasova < 1){
                        $('#glasajDugme').hide();
                        $("#objasnjenje").html("Iskoristili ste sva tri glasa. Mi dozvoljavamo 3 glasa po korisniku");
                    }

                    $("#brojGlasovaOstalo").html(ostaloGlasova);
                }
            })
        }
        ostaloGlasova();
        
        function glasaj() {
            let korisnikID = $("#korisnikiD").val();
            let osobaID = $("#osoba").val();
            let kategorijaID = $("#kategorija").val();

            var arr = { "korisnikID": korisnikID, "osobaID": osobaID, "kategorijaID": kategorijaID };

            $.ajax({
                url: 'flight/glasaj',
                type: 'POST',
                data: JSON.stringify(arr),
                success: function (data) {
                    $("#rez").html(data);
                    ostaloGlasova();
                }
            })
        }

    </script>

	</body>
</html>

