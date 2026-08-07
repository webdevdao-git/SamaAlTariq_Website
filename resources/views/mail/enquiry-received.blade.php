{{-- Blade escapes by default, so visitor-supplied values cannot inject markup. --}}
<div style="font-family:Arial,Helvetica,sans-serif;color:#171717;line-height:1.6">
    <h2 style="color:#3fa7b3;margin:0 0 16px">New website enquiry</h2>

    <table cellpadding="6" style="border-collapse:collapse">
        <tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">Name</td><td>{{ $enquiry->name }}</td></tr>
        <tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">Email</td><td>{{ $enquiry->email }}</td></tr>
        <tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">Phone</td><td>{{ $enquiry->phone ?: '—' }}</td></tr>
        <tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">Property type</td><td>{{ $enquiry->project_type ?: '—' }}</td></tr>
        <tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">Location</td><td>{{ $enquiry->location ?: '—' }}</td></tr>
        <tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">Project brief</td><td>{!! nl2br(e($enquiry->project_brief ?: '—')) !!}</td></tr>
    </table>

    <p style="margin-top:16px;color:#6b6b6b;font-size:13px">
        Received {{ $enquiry->created_at->format('d M Y, H:i') }} UTC
    </p>
</div>
