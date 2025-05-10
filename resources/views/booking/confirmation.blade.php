<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking Confirmation</title>
	<style>
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			line-height: 1.6;
			color: #333;
			background-color: #f7f9fc;
			margin: 0;
			padding: 0;
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.confirmation-container {
			max-width: 650px;
			width: 90%;
			background-color: #ffffff;
			border-radius: 12px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
			overflow: hidden;
			text-align: center;
			position: relative;
		}

		.header {
			background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
			padding: 30px 20px;
			color: white;
		}

		.check-icon {
			width: 90px;
			height: 90px;
			background-color: white;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 20px;
		}

		.check-icon svg {
			width: 50px;
			height: 50px;
			color: #43cea2;
		}

		h1 {
			margin: 0;
			font-size: 28px;
			font-weight: 600;
		}

		.content {
			padding: 40px 30px;
		}

		p {
			font-size: 18px;
			color: #555;
			margin-bottom: 30px;
			line-height: 1.7;
		}

		.home-button {
			display: inline-block;
			background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
			color: white;
			text-decoration: none;
			padding: 14px 40px;
			border-radius: 50px;
			font-size: 16px;
			font-weight: 600;
			transition: all 0.3s ease;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
			border: none;
			cursor: pointer;
		}

		.home-button:hover {
			transform: translateY(-3px);
			box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
		}

		.footer {
			padding: 20px;
			font-size: 14px;
			color: #888;
			border-top: 1px solid #eee;
		}

		@media (max-width: 768px) {
			.confirmation-container {
				width: 95%;
			}

			h1 {
				font-size: 24px;
			}

			p {
				font-size: 16px;
			}
		}
	</style>
</head>

<body>
	<div class="confirmation-container">
		<div class="header">
			<div class="check-icon">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
					stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="20 6 9 17 4 12"></polyline>
				</svg>
			</div>
			<h1>Booking Confirmed!</h1>
		</div>

		<div class="content">
			<p>Thank you for your booking! We've received your request and will be in touch shortly to confirm your
				appointment details. A confirmation email has been sent to your provided email address.</p>

			<p>We're looking forward to welcoming you!</p>

			<a href="/" class="home-button">Return to Home</a>
		</div>

		<div class="footer">
			If you have any questions, <a href="tel:+254110407501">please contact us</a>.
		</div>
	</div>
</body>

</html>