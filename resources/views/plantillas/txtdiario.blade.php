@component('mail::message')
# Se envía la Base de Oficinas al {{$data['fecha']}}

Si desea revisar la info de forma más detallada, ingrese en la extranet.


@component('mail::button', ['url' => $data['url']])
Ir a la extranet
@endcomponent

Gracias,<br>
{{ config('app.name') }}

<small>Este correo solo se usa para el envío de correo, cualquier consulta envíanos un correo a Eficiencia@cce.com.pe</small>
@endcomponent
