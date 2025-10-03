{{-- resources/views/aluno/timeline.blade.php --}}
@extends('layouts.app')
@section('title', 'Linha do Tempo')
@section('content')
  <h4>Linha do Tempo — {{ $aluno->nome }}</h4>

  @foreach($events->groupBy(fn($e) => $e->created_at->format('Y')) as $year => $byYear)
    <h5>{{ $year }}</h5>
    @foreach($byYear->groupBy(fn($e) => $e->created_at->format('Y-m')) as $month => $byMonth)
      <h6 class="text-muted">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</h6>
      <ul class="list-group mb-3">
        @foreach($byMonth as $event)
          <li class="list-group-item">
            <div class="d-flex justify-content-between">
              <div>
                <strong>{{ ucfirst($event->evento) }}</strong>
                <small class="text-muted"> — {{ $event->created_at->format('d/m/Y H:i') }}</small>
                <div class="mt-1">
                  {{-- mostre parte dos dados --}}
                  @if(is_array($event->dados) && isset($event->dados['tecnicos']))
                    <small>Técnicos: {{ collect($event->dados['tecnicos'])->map(fn($v,$k)=>"$k:$v")->implode(', ') }}</small>
                  @else
                    <small>Snapshot: {{ \Illuminate\Support\Str::limit(json_encode($event->dados), 180) }}</small>
                  @endif
                </div>
              </div>
              <div>
                @if($event->user)
                  <small class="text-muted">por {{ $event->user->name }}</small>
                @endif
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    @endforeach
  @endforeach

  {{ $events->links() }}
@endsection
