<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<script>
		window.Laravel = {
			auth: {
                user: @php
                    $user = auth()->user();
                    echo $user ? json_encode($user->only('id', 'client_id', 'email', 'isAdmin','username')) : 'null';
                @endphp
            }
		};
	</script>
	@vite('resources/js/app.js')
	@vite('resources/css/app.css')

	@inertiaHead
	@routes
</head>

<body class="bg-gray-100">
	
	@inertia
</body>

</html>