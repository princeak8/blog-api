@component('mail::message')
# NEW CONTACT MESSAGE

<h2>{{$message->name}}</h2>

<p>
    {{$message->message}}
</p>

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

