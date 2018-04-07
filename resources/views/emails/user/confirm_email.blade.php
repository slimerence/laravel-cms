@component('mail::message')
# Hi {{ $user->name }},

@component('mail::panel')
Your account has been created! Here below is your login detail: <br>
<table>
    <tr>
        <td style="width: 40%;"><h5 style="padding-left: 12px; font-weight: bold;">URL</h5></td>
        <td><a style="text-decoration: none;" href="{{ $url }}" target="_blank"><span style="color: cornflowerblue;">{{ $url }}</span></a></td>
    </tr>
    <tr>
        <td>
            <h5 style="padding-left: 12px; font-weight: bold;">Login name</h5>
        </td>
        <td>{{ $user->email }}</td>
    </tr>
    <tr>
        <td>
            <h5 style="padding-left: 12px; font-weight: bold;">Password</h5></td>
        <td>{{ $password }}</td>
    </tr>
</table>
<br>
@component('mail::button', ['url' => $url, 'color' => 'green'])
Login Now
@endcomponent

@endcomponent

Thanks!<br>
@endcomponent
