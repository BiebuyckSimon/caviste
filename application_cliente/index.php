<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title> Vins </title>

	<!-- CSS -->
	<link rel="stylesheet" media="screen" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" media="screen" type="text/css" href="css/librairies/bootstrap.css"/>
	<link rel="stylesheet" media="screen" type="text/css" href="css/librairies/jquery-ui.css"/>
	
	<!-- JS -->
	<script src="js/librairies/jquery-1.12.0.min.js"></script>
	<script src="js/librairies/jquery-ui.js"></script>
</head>
<body>

	<div class="container">
		<div class="row">


			<div class="col-xs-12 col-sm-5">

				<!-- Formulaire de recherche -->
				<form id="frmSearch" action="#" method="get">
					<fieldset>
						<input type="text" name="search" id="search"/>
						<button type="submit" name="btSearch" id="btSearch" class="btn btn-default btn-sm" >Search</button>
					</fieldset>	
				</form>

				<!-- Liste des vins -->
				<ul id="listeVins"></ul>
				<div id="affichageSearch"></div>	

			</div>


			<div class="col-xs-12 col-sm-7">

				<!-- Formulaire d'ajout/modification/suppression -->
				<form id="frmChange" action="#" method="post">
					<fieldset>

						<div class="row">

							<div class="col-xs-12 col-sm-6">
								<button type="submit" name="btAdd" id="btAdd" class="btn btn-default btn-sm">New Wine</button></br>

								<label for="id">Id:</label>
								<input type="text" name="id" id="id" readonly="readonly" class="form-control form-group"/>

								<label for="name">Name:</label>
								<input type="text" name="name" id="name" class="form-control form-group"/>

								<label for="grapes">Grapes:</label>
								<input type="text" name="grapes" id="grapes" class="form-control form-group"/>

								<label for="country">Country:</label>
								<input type="text" name="country" id="country" class="form-control form-group"/>

								<label for="region">Region:</label>
								<input type="text" name="region" id="region" class="form-control form-group"/>

								<label for="year">Year:</label>
								<input type="text" name="year" id="year" class="form-control form-group"/>
							</div>

							<div class="col-xs-12 col-sm-6" id="bottom-align" >
								<img src="pics/default.jpg" alt="Vin" id="picture"/>

								<label for="description">Notes:</label>
								<textarea name="description" id="description" class="form-control form-group"></textarea>								
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12" >
								<button type="submit" name="btSave" id="btSave" class="btn btn-default btn-sm">Save</button>

								<button type="submit" name="btDelete" id="btDelete" class="btn btn-default btn-sm">Delete</button>

								<div id="affichageChange"></div>
							</div>
						</div>

					</fieldset>	
				</form>

			</div>	

		</div>
	</div>


	<!-- JS -->
	<script src="js/codejQuery.js"></script>

</body>
</html>