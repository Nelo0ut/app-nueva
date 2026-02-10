@component('mail::message')
# Hola {{$data['nombre']}},

Mediante este correo se envía su contraseña para que pueda acceder a la extranet.

Contraseña: {{$data['contrasena']}}


@component('mail::button', ['url' => $data['url']])
Ir a la extranet
@endcomponent

Gracias,<br>
{{ config('app.name') }}

<small>Este correo solo se usa para el envío de correo, cualquier consulta envíanos un correo a Eficiencia@cce.com.pe</small>
@endcomponent