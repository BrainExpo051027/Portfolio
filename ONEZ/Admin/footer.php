<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style type="text/css">
	footer {
		width: 100%;
		background-color: black;
		padding: 20px;
		box-sizing: border-box;
	}
	
	.fa {
		margin: 0px 5px;
		padding: 8px;
		font-size: 18px;
		width: 35px;
		height: 35px;
		text-align: center;
		text-decoration: none;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		transition: opacity 0.3s ease;
	}
	
	.fa:hover {
		opacity: .7;
		transform: scale(1.1);
	}
	
	.fa-facebook {
		background: #3B5998;
		color: white;
	}
	
	.fa-twitter {
		background: #55ACEE;
		color: white;
	}
	
	.fa-google {
		background: #dd4b39;
		color: white;
	}
	
	.fa-instagram {
		background: #125688;
		color: white;
	}
	
	.fa-yahoo {
		background: #400297;
		color: white;
	}
	
	.social-container {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		gap: 10px;
		margin: 20px 0;
	}
	
	footer h3 {
		color: white;
		text-align: center;
		margin-bottom: 20px;
		font-size: 1.2rem;
	}
	
	footer p {
		color: white;
		text-align: center;
		line-height: 1.6;
		margin: 0;
		padding: 10px;
	}
	
	.contact-info {
		margin-top: 20px;
	}
	
	/* Responsive design */
	@media screen and (max-width: 768px) {
		footer {
			padding: 15px;
		}
		
		footer h3 {
			font-size: 1.1rem;
		}
		
		.fa {
			width: 30px;
			height: 30px;
			font-size: 16px;
			margin: 0px 3px;
		}
		
		footer p {
			font-size: 14px;
		}
	}
	
	@media screen and (max-width: 480px) {
		.social-container {
			gap: 8px;
		}
		
		.fa {
			width: 28px;
			height: 28px;
			font-size: 14px;
		}
		
		footer h3 {
			font-size: 1rem;
		}
		
		footer p {
			font-size: 13px;
			padding: 8px;
		}
	}
	</style>
</head>
<body>
<footer>
	<h3>Contact us through social media</h3>
	
	<div class="social-container">
		<a href="#" class="fa fa-facebook" aria-label="Facebook"></a>
		<a href="#" class="fa fa-twitter" aria-label="Twitter"></a>
		<a href="#" class="fa fa-google" aria-label="Google"></a>
		<a href="#" class="fa fa-instagram" aria-label="Instagram"></a>
		<a href="#" class="fa fa-yahoo" aria-label="Yahoo"></a>
	</div>

	<div class="contact-info">
		<p>
			Email: onezjoshuajames@gmail.com<br>
			Mobile: 09090909099
		</p>
	</div>
</footer>
</body>
</html>