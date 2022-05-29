<?php $i = ($profiles->currentpage() - 1) * $profiles->perpage() + 1; ?>
@foreach($profiles as $profile)
<tr @if ($profile->profileJobs->key === "00-00") style="background-color: orange" @endif>>
    <td>{{ $i++ }}</td>
    <td>{{ $profile->name }}</td>
    <td>{{ $profile->profileJobs->key }}</td>
    <td>{{ $profile->profileStatus->name }}</td>
    <td><a href="https://docs.google.com/viewer?url={{request()->getHost()}}/uploads/profile/{{$profile->file}}" target="_blank">link</a></td>
    <td>{{ $profile->submit_date }}</td>
    <td>
        <a href="/profile/{{$profile->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
        <a href="/profile/{{$profile->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
        <a href="/profile/{{$profile->id}}/delete" onclick="return confirm('Are you sure delete profile: {{$profile->name}}?')" class="fa fa-trash text-danger" aria-hidden="true"></i>
    </td>
</tr>
@endforeach