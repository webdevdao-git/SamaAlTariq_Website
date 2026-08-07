<div style="font-family:Arial,Helvetica,sans-serif;color:#171717;line-height:1.6">
    <h2 style="color:#3fa7b3;margin:0 0 16px">Your client portal access</h2>

    <p>Hello {{ $client->name }},</p>
    <p>Your account for the Sama Al Tariq client portal is ready.</p>

    <table cellpadding="6" style="border-collapse:collapse">
        <tr><td style="font-weight:600">Portal</td><td><a href="{{ route('login') }}">{{ route('login') }}</a></td></tr>
        <tr><td style="font-weight:600">Username</td><td>{{ $client->username ?: $client->email }}</td></tr>
        <tr><td style="font-weight:600">Temporary password</td><td><code>{{ $password }}</code></td></tr>
    </table>

    <p style="margin-top:16px">You will be asked to choose a new password when you first sign in.</p>
</div>
