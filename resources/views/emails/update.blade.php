<!-- resources/views/emails/update.blade.php -->

<html>
<head>
	<title>Client.fmf.ru Update</title>
	<link href="/css/style.css" rel="stylesheet" type="text/css"/>
	<link href='http://fonts.googleapis.com/css?family=Alegreya:400,700|Roboto+Condensed' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container clients">
	<h2>Клиенты:</h2>
	@if (isset($clients['created']))
		<div class="quote-container">
			<h3>Добавлено:</h3>
			@foreach ($clients['created'] as $client)
				<p>{{ $client }}</p>
			@endforeach
		</div>
	@endif
	@if (isset($clients['updated']))
		<div class="quote-container">
			<h3>Обновлено:</h3>
			@foreach ($clients['updated'] as $client)
				<p>{{ $client }}</p>
			@endforeach
		</div>
	@endif
	@if (isset($clients['deleted']))
		<div class="quote-container">
			<h3>Удалено записей: {{ $clients['deleted'] }}</h3>
		</div>
	@endif
	@if (!isset($clients['created']) && !isset($clients['updated']) && !isset($clients['deleted']))
		<div class="quote-container">
			<h3>Изменения отсутствуют</h3>
		</div>
	@endif
</div>
<div class="container documents">
	<h2>Документы:</h2>
	@if (isset($documents['created']))
		<div class="quote-container">
			<h3>Добавлено:</h3>
			@foreach ($documents['created'] as $document)
				<p>№ СЧ-ФАКТУРА: {{ $document['invoice_number'] }} / № АКТ: {{ $document['act_number'] }}</p>
			@endforeach
		</div>
	@endif
	@if (isset($documents['updated']))
		<div class="quote-container">
			<h3>Обновлено:</h3>
			@foreach ($documents['updated'] as $document)
				<p>№ СЧ-ФАКТУРА: {{ $document['invoice_number'] }} / № АКТ: {{ $document['act_number'] }}</p>
			@endforeach
		</div>
	@endif
	@if (isset($documents['deleted']))
		<div class="quote-container">
			<h3>Удалено записей: {{ $documents['deleted'] }}</h3>
		</div>
	@endif
	@if (!isset($documents['created']) && !isset($documents['updated']) && !isset($documents['deleted']))
		<div class="quote-container">
			<h3>Изменения отсутствуют</h3>
		</div>
	@endif
</div>
</body>
</html>