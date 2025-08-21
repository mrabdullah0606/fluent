@component('mail::message')
    # 📢 Zoom Meeting Invitation

    Hello Student,

    You are invited to join a Zoom meeting.

    **Topic:** {{ $meeting->topic }}
    **Start Time:** {{ \Carbon\Carbon::parse($meeting->start_time)->format('d M Y, h:i A') }}
    **Duration:** {{ $meeting->duration }} minutes

    @component('mail::button', ['url' => $meeting->join_url])
        Join Meeting
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
