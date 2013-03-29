<?php
	require("facebook-php-sdk/src/facebook.php");

	$facebook = new Facebook(array(
		'appId'  => '256584601136224',
		'secret' => 'efe46a61e56f6828f6b484ae453d91c2',
	));

	// Get User ID
	$user = $facebook->getUser();

	if ($user) {
		try {
    		// Récupèration des infos
			$user_profile = $facebook->api('/me?fields=id,name,friends.fields(id,name,gender)');
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		}
	}

	// Connection ou deconnection
	if ($user) {
		$logoutUrl = $facebook->getLogoutUrl();
	} else {
		$loginUrl = $facebook->getLoginUrl();
	}
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<title>Design</title>
	<link rel="stylesheet" href="css/design.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
</head>
<body>
	<?php if ($user): ?>
		<img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
		<a href="<?php echo $logoutUrl; ?>">Logout</a>
	<?php else: ?>
	<div>
		Connectez-vous en utilisant OAuth 2.0 gérées par le SDK PHP :
		<a href="<?php echo $loginUrl; ?>">Connexion avec Facebook</a>
	</div>
	<?php endif ?>
	<hr>
	<h1>Avez-vous plus d'amis filles ou gar&ccedil;ons ?</h1>
	<?php if ($user): ?>
		<button name="stats">Afficher</button>
		<div class="global">
		<?php
			// Traitement
			$friends_list = $user_profile["friends"];
			$friends = $friends_list["data"];
			
			// On récuèpère tous les amis, en parcourant les autres pages
			while(isset($friends_list["paging"]) && isset($friends_list["paging"]["next"])){
				$url = str_replace("https://graph.facebook.com/", "/", $friends_list["paging"]["next"]);	
				$friends_list["paging"] = $facebook->api($url);
				$friends = $friends + $friends_list["data"];
			}

			// on récupère le nombre de garçon et le nombre de fille
			$nbFemales = 0;
			$nbMales = 0;
			foreach ($friends as $friend) {
				if($friend["gender"] == "female")
					$nbFemales++;
				if($friend["gender"] == "male")
					$nbMales++;
			}
		?>
			<div class="box boys">
				<div class="text">
					<?php
						if($nbMales != 0)
							echo intval(($nbMales/($nbMales+$nbFemales))*100)."%";
						else
							echo "0%";
					?>
				</div>
				<div class="pictures slider">
					<div class="case">
					<?php
						// on affiche que les 5 premiers
						$i = 0;
						$nb = 0;
						while($i<sizeof($friends) && $nb<6){
							if($friends[$i]["gender"] == "male"){
								echo '<a href="http://www.facebook.com/'.$friends[$i]["id"].'" target="_blank"><img src="https://graph.facebook.com/'.$friends[$i]["id"].'/picture" title="'.$friends[$i]["name"].'" /></a>';
								$nb++;
							}
							$i++;
						}
					?>
					</div>
				</div>
			</div>
			<div class="box girls">
				<div class="text">
					<?php
						if($nbFemales != 0)
							echo intval(($nbFemales/($nbMales+$nbFemales))*100)."%";
						else
							echo "0%";
					?>
				</div>
				<div class="pictures slider">
					<?php
						// on affiche que les 5 premiers
						$i = 0;
						$nb = 0;
						while($i<sizeof($friends) && $nb<6){
							if($friends[$i]["gender"] == "female"){
								echo '<a href="http://www.facebook.com/'.$friends[$i]["id"].'" target="_blank"><img src="https://graph.facebook.com/'.$friends[$i]["id"].'/picture" title="'.$friends[$i]["name"].'" /></a>';
								$nb++;
							}
							$i++;
						}
					?>
				</div>
			</div>
		</div>
		<div class="globalParty">
			<hr>
			<h1><?php echo htmlentities("On fait la fête chez qui ce week-end ?",ENT_QUOTES, "UTF-8"); ?></h1>
			<button name="party">Afficher</button>
			<div class="partyResult">
				<?php
				// on ne sélectionne qu'un ami pour faire la fête
				$theOne = rand(0,sizeof($friends));
				$friendPicture = $facebook->api($friends[$theOne]["id"]."?fields=picture.height(300)");
				$friendPictureUrl = $friendPicture["picture"]["data"]["url"];
				echo "<h2>".htmlentities($friends[$theOne]["name"],ENT_QUOTES, "UTF-8")."</h2>";
				echo '<a href="http://www.facebook.com/'.$friends[$theOne]["id"].'" title="'.$friends[$theOne]["name"].'" target="_blank"><img src="'.$friendPictureUrl.'" height="300"></a>';
				?>
			</div>
		</div>
	<?php else: ?>
		<strong><em>Vous n'&ecirc;tes pas connect&eacute; :/</em></strong>
	<?php endif ?>
</body>
</html>