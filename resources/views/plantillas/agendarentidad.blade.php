@component('mail::message')


# Tiene una nueva agenda: {{$data['nombre']}}

Le avisamos que ha sido agendado para una reunión en el siguiente horario:<br>


Inicio: {{$data['feini']}}<br>
Fin: {{$data['fefin']}}<br>

Tema: {{$data['nombreage']}}<br>
Descripción: {{$data['descripcion']}}<br>

@component('mail::button', ['url' => $data['google']])
Agregar a tu calendario Google
@endcomponent
@component('mail::button', ['url' => $data['webOutlook']])
Agregar a tu calendario Outlook Web
@endcomponent
@component('mail::button', ['url' => $data['yahoo']])
Agregar a tu calendario Yahoo
@endcomponent
@component('mail::button', ['url' => $data['ics']])
Agregar a tu calendario Outlook
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent