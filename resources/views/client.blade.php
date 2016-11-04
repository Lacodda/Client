@extends('layouts.app')
@section('client')
	{{ $client['name'] }}
@endsection
@section('documents')
	<table class="table table-hover">
		<colgroup>
			<col width="5%">
			<col width="10%">
			<col width="62%">
			<col width="23%">
		</colgroup>
		<thead>
		<tr>
			<th>#</th>
			<th>Дата</th>
			<th>Сумма, руб</th>
			<th>Документы</th>
		</tr>
		</thead>
		<tbody>
		@if (isset($documents))
			@foreach ($documents as $document)
				<tr>
					<td>{{ $loop->index + 1 }}</td>
					<td>{{ date('d.m.Y', strtotime($document['invoice_date'])) }}</td>
					<td>{{ number_format($document['amount'], 2, ',', ' ') }}</td>
					<td>
						<div class="btn-group">
							<button type="button" class="btn btn-default"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button>
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only"></span>
							</button>
							<ul class="dropdown-menu">
								<li class="dropdown-header"><b>АКТ</b></li>
								<li><a href="{{ route('act', ['alias' => $client['alias'], 'id' => $document['id']]) }}">
										<i class="fa fa-file-text" aria-hidden="true"></i> Без печати</a>
								</li>
								<li><a href="{{ route('act_stamp', ['alias' => $client['alias'], 'id' => $document['id']]) }}">
										<i class="fa fa-certificate" aria-hidden="true"></i> С печатью</a>
								</li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header"><b>СЧЕТ-ФАКТУРА</b></li>
								<li><a href="{{ route('invoice', ['alias' => $client['alias'], 'id' => $document['id']]) }}">
										<i class="fa fa-file-text-o" aria-hidden="true"></i> Без подписи</a>
								</li>
								<li><a href="{{ route('invoice_stamp', ['alias' =>	$client['alias'], 'id' => $document['id']]) }}">
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i> С подписью</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
			@endforeach
		@else
			Документов не найдено.
		@endif
		</tbody>
	</table>
@endsection
