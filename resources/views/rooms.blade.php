<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Rooms</title>
</head>
<body>
<main class="container mt-5">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            @foreach($locations as $key => $location)
                <a class="nav-item nav-link {{$key === 0 ?'active':''}}" id="nav-{{$location->location_id}}-tab" data-toggle="tab" href="#nav-{{$location->location_id}}" role="tab" aria-controls="nav-{{$location->location_id}}" aria-selected="true">{{$location->name}}</a>
            @endforeach
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        @foreach($locations as $key => $location)
            <div class="tab-pane fade show {{$key === 0 ?'active':''}}" id="nav-{{$location->location_id}}" role="tabpanel" aria-labelledby="nav-{{$location->location_id}}-tab">
                @foreach($location->polls as $poll_key => $poll)
                    <div class="col-md-12 m-3 text-center">
                        @if(in_array($poll->id, [99, 103, 101, 102]))
                            <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus={{$poll->id}}" class="btn btn-primary btn-lg">ПОСЛЕ {{$poll->name}}</a>
                        @elseif(in_array($poll->id, [10, 11, 12, 13]))
                            <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus={{$poll->id}}" class="btn btn-primary btn-lg">ДО {{$poll->name}}</a>
                        @else
                            <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus={{$poll->id}}" class="btn btn-primary btn-lg">{{$poll->name}}</a>
                        @endif
                    </div>
                    @if($poll_key === (count($location->polls) - 1))
                        <div class="col-md-12 m-3 text-center">
                            <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus=100" style="font-size:24px;padding: 10px; padding-left: 15%;padding-right: 15%;" class="btn btn-warning">Оценка сессии</a>
                            <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus=0" style="font-size:24px;padding: 10px;padding-left: 15%;padding-right: 15%;" class="btn btn-danger">Скрыть</a>
                        </div>
                    @endif
                @endforeach
                @if(count($location->polls) === 0)
                    <div class="col-md-12 m-3 text-center">
                        <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus=100" style="font-size:24px;padding: 10px; padding-left: 15%;padding-right: 15%;" class="btn btn-warning">Оценка сессии</a>
                        <a href="https://live.proofix.tv/shnaider/interactive-control.php?event=shnaider&id_room={{$key+1}}&setstatus=0" style="font-size:24px;padding: 10px;padding-left: 15%;padding-right: 15%;" class="btn btn-danger">Скрыть</a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</main>
</body>
</html>
