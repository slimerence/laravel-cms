@component('mail::message')
# A new wholesaler account has been created!

@component('mail::panel')
<table>
    <tr>
        <td style="width: 40%;"><h4>{{ $wholesaler->registered_name_of_applicant }}</h4></td>
        <td style="width: 60%;">
            <p style="text-align: right;">
                Trading Name:
                <b style="color: #409EFF;">{{ $wholesaler->trading_name_of_applicant }}</b>
            </p>
        </td>
    </tr>
    <tr>
        <td><h5 style="padding-left: 12px; font-weight: bold;">Status</h5></td>
        <td><span style="color: red;">Pending</span></td>
    </tr>
    <tr>
        <td>
            <h5 style="padding-left: 12px; font-weight: bold;">Name</h5>
        </td>
        <td>{{ $user->name }}</td>
    </tr>
    <tr>
        <td>
            <h5 style="padding-left: 12px; font-weight: bold;">Address</h5>
        </td>
        <td>{{ $user->addressText() }}</td>
    </tr>
    <tr>
        <td>
            <h5 style="padding-left: 12px; font-weight: bold;">ABN</h5>
        </td>
        <td>{{ $wholesaler->abn }}</td>
    </tr>
    <tr>
        <td>
            <h5 style="padding-left: 12px; font-weight: bold;">ACN</h5>
        </td>
        <td>{{ $wholesaler->acn }}</td>
    </tr>
</table>
<br>
@endcomponent

@component('mail::button', ['url' => $loginUrl, 'color' => 'green'])
View Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
