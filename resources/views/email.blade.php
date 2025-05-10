<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Booking Request</title>
	<style>
		body {
			font-family: 'Helvetica Neue', Arial, sans-serif;
			line-height: 1.6;
			color: #333;
			background-color: #f9f9f9;
			margin: 0;
			padding: 0;
		}

		.container {
			max-width: 600px;
			margin: 0 auto;
			background-color: #ffffff;
			border-radius: 8px;
			padding: 20px;
		}

		.header {
			background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
			color: #ffffff;
			padding: 20px;
			text-align: center;
			border-radius: 8px 8px 0 0;
		}

		.content {
			padding: 20px;
		}

		.booking-info {
			background-color: #f5f7fa;
			border-radius: 6px;
			padding: 15px;
			margin-bottom: 20px;
		}

		.info-item {
			margin-bottom: 10px;
		}

		.label {
			font-weight: bold;
			color: #555;
		}

		.service-item {
			background-color: #ffffff;
			border-left: 3px solid #2575fc;
			padding: 10px;
			margin-bottom: 8px;
			border-radius: 4px;
		}

		.footer {
			text-align: center;
			padding: 15px;
			background-color: #f5f7fa;
			color: #6c757d;
			font-size: 14px;
			border-radius: 0 0 8px 8px;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="header">
			<h1>New Booking Request</h1>
			<p>A new booking has been received</p>
		</div>

		<div class="content">
			<div class="booking-info">
				<h2>Client Information</h2>
				<div class="info-item">
					<span class="label">Name:</span>
					{{ $first_name }} {{ $last_name }}
				</div>
				<div class="info-item">
					<span class="label">Email:</span>
					{{ $email }}
				</div>
				<div class="info-item">
					<span class="label">Phone:</span>
				<a href="tel:{{$phone}}">{{ $phone }}</a>
				</div>

				<h2>Appointment Details</h2>
				<div class="info-item">
					<span class="label">Date:</span>
					{{ $date }}
				</div>
				<div class="info-item">
					<span class="label">Time:</span>
					{{ $timetable }}
				</div>

				<h2>Selected Services</h2>
				@if(is_array($services))
					@foreach($services as $service)
						<div class="service-item">{{ $service }}</div>
					@endforeach
				@else
					<div class="service-item">{{ $services }}</div>
				@endif

				@if(!empty($order_notes))
					<h2>Additional Notes</h2>
					<div class="info-item">
						{{ $order_notes }}
					</div>
				@endif

				<a href="tel:{{$phone}}" class="info-item">
					<button style="background-color: #2575fc; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
						Call Client
					</button>
			</div>

			<p>Thank you,<br> powered by AscendSD</p>
		</div>

		<div class="footer">
			<p>© {{ date('Y') }}jacknails.</p>
		</div>
	</div>
</body>

</html>