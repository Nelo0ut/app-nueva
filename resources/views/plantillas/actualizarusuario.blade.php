@component('mail::message')
# Se actualizo el usuario: {{ $data['usuario'] }}

{{ $data['nombre'] }} Le avisamos que el usuario {{ $data['usuario'] }} ha sido actualizado en la extranet, por favor ingresar.

@component('mail::button', ['url' => $data['url']])
Ir a la extranet
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent