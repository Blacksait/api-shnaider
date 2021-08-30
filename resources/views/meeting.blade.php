<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <title>Page for moderator</title>
</head>
<body>
<table class="table ">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Speaker</th>
            <th scope="col">User</th>
            <th scope="col">Meeting datetime</th>
            <th scope="col">Meeting status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($result as $action)
        <tr class="item-{{ $action->id }}">
            <th scope="row">{{ $action->id }}</th>
            <td>{{ $action->speaker()->name }}</td>
            <td>
                @if(empty($action->user_id))
                    User not known yet
                @else
                    {{ $action->user()->fname }} {{ $action->user()->lname }}<br>{{ $action->user()->email }}<br>{{ $action->user()->mphone }}<br>{{ $action->user()->company }}<br>{{ $action->user()->city }}
                @endif
            </td>
            <td>{{ $action->meeting_date }}<br>{{ $action->meeting_time }}</td>
            <td>
                @if((int) $action->meeting_confirm === 0)
                    Empty slot
                @elseif((int) $action->meeting_confirm === 1)
                    Confirmed
                @elseif((int) $action->meeting_confirm === 2)
                    Awaiting approval
                @endif
            </td>
            <td>
                @if((int) $action->meeting_confirm === 2 && !empty($action->user()))
                    <form data-id="{{ $action->id }}">
                        <button class="btn btn-sm btn-success update mt-1" type="button"
                                onclick="myConfirm('{{ $action->id }}', '{{ $action->user()->attendee_id }}', '1')">Confirm
                        </button>
                    </form>
                    <form data-id="{{ $action->id }}">
                        <button class="btn btn-sm btn-warning update mt-1" type="button"
                                onclick="myConfirm('{{ $action->id }}', '219492931', '0')">Cancel reservation
                        </button>
                    </form>
                @elseif((int) $action->meeting_confirm === 1)
                    <form data-id="{{ $action->id }}">
                        <button class="btn btn-sm btn-warning update mt-1" type="button"
                                onclick="myConfirm('{{ $action->id }}', '219492931', '0')">Cancel reservation
                        </button>
                    </form>
                @endif
                    {{--<form data-id="{{ $action->id }}">--}}
                        {{--<button class="btn btn-sm btn-danger update mt-1" type="button"--}}
                                {{--onclick="myDelete({{ $action->id }})">Delete--}}
                        {{--</button>--}}
                    {{--</form>--}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    function myConfirm(id, user_id, confirm) {
        let data = {
            id: id,
            confirm: confirm,
            user_id: user_id,
            role: 'moderator'
        };

        $.ajax({
            url: "{{env('APP_URL').'/api/meeting/update'}}",
            type: 'post',
            data: data,
            success: function (response) {
                console.log(response);
                location.reload();
            }
        });
    }

    {{--function myDelete(id) {--}}
        {{--if(!confirm('Are you sure? This action will remove the meeting from the event page.')) return;--}}

        {{--let data = {--}}
            {{--id: id--}}
        {{--};--}}

        {{--$.ajax({--}}
            {{--url: "{{env('APP_URL').'/api/meeting/delete'}}",--}}
            {{--type: 'post',--}}
            {{--data: data,--}}
            {{--success: function (response) {--}}
                {{--console.log(response);--}}
                {{--location.reload();--}}
            {{--}--}}
        {{--});--}}
    {{--}--}}
</script>
</body>
</html>
