
	<!-- <meta charset="UTF-8" /> -->
	<!-- <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0" name="viewport"> -->

	<!-- Open Graph -->
	<meta property="og:title" content="Igreja Batista Emanuel - Jesus é o único caminho." />
	<meta property="og:url" content="https://igrejaemanuel.com.br" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://igrejaemanuel.com.br/images/favicon.png" />
	<meta property="og:description" content="Somos a Igreja Emanuel e estamos anunciando que Jesus Cristo é o que falta em você. Estamos construindo uma igreja com transparência financeira e que atua para transformar a cultura local de onde estivermos inseridos. Venha fazer parte, vem para Emanuel." />

	<!-- Twitter Theme -->
	<meta name="twitter:widgets:theme" content="light">
	
	<!-- Title &amp; Favicon -->
	<title>Olá, <?php echo $nomedousuario;?> - Emanuel (Igreja Batista Emanuel) - Jesus é o único caminho</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700%7CHind+Madurai:400,500&amp;subset=latin-ext" rel="stylesheet">
	
	<!-- Css -->
	<link rel="stylesheet" href="css/core.min.css" />
	<link rel="stylesheet" href="css/skin.css" />
	<link rel="stylesheet" href="mediaelement/skin/mejs-snowplayerskin.css" />

	<!--[if lt IE 9]>
    	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	
    <?php
        $url_est = $_SERVER['REQUEST_URI'];
        $url_est = explode('?', $url_est);
        $url_est = $url_est[0];
        $url_est = explode('/', $url_est);
        $url_est = $url_est[1];
        //echo $url_est;
        if($url_est === "analise" OR $url_est === 'celulaanalise'):
    ?>
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<script src="js/chart.js"></script>
    <?php
        endif;
    ?>