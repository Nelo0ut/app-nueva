@component('mail::message')
# Se cargó el documento: {{ $data['title'] }}

{{ $data['nombre'] }} Le avisamos que se ha cargado documentos en la extranet, por favor ingresar.

@if (count($data['documentos']) > 0)
Lista de documentos cargados:<br>
@for ($i = 0; $i < count($data['documentos']); $i++)
    - {{$data['documentos'][$i]}}<br>
@endfor
@endif

@component('mail::button', ['url' => $data['url']])
Ir a la extranet
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
