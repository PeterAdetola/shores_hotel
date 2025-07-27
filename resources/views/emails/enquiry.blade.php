<x-mail::message>
    # Hello {{ config('app.name') }}, you have a new enquiry

    <h3>Name: {{ $data['name'] }}</h3>
    <h3>Phone: {{ $data['phone'] }}</h3>
    <h3>Email: {{ $data['email'] }}</h3>
    <h3>Company: {{ $data['company'] }}</h3>
    <p>Message: {{ $data['message'] }}</p>

    <x-mail::button :url="'https://www.thejupiterlegal.com'">
    Visit our website
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
