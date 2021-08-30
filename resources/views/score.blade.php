<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Setting up the accrual of points</title>
</head>
<body>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Correct answer, points</th>
            <th scope="col">Wrong answer, points</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actions as $action)
                <tr class="item-{{$action->id}}">
                    <th scope="row">{{$action->id}}</th>
                    <td>{{$action->name}}</td>
                    <td>
                        <input type="number" value="{{$action->score_correct}}" class="form-control number" id="score_correct_{{$action->id}}">
                    </td>
                    <td>
                        <input type="number" value="{{$action->score_wrong}}" class="form-control number" id="score_wrong_{{$action->id}}">
                    </td>
                    <td>
                        <form method="POST" data-id="{{$action->id}}">
                            <input type="hidden" value="{{$action->id}}" name="id">
                            <input type="hidden" value="{{$action->score_correct}}" name="score_correct">
                            <input type="hidden" value="{{$action->score_wrong}}" name="score_wrong">
                            <button class="btn btn-sm btn-primary update mt-1" type="submit">Update</button>
                        </form>
                    </td>
                </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready( () => {
        $('form').on("submit", function(e) {
            e.preventDefault();
            console.log('emit', this, $(this));
            let edit_id = $(this).data('id');

            let score_correct = $('#score_correct_'+edit_id).val();
            let score_wrong = $('#score_wrong_'+edit_id).val();

            let data = {
                score_correct: score_correct,
                score_wrong: score_wrong,
                id: edit_id
            };
            console.log(data);
            if(score_wrong !== '' && score_correct !== ''){
                $.ajax({
                    url: "{{env('APP_URL').'/api/score/update'}}",
                    type: 'post',
                    data: data,
                    success: function(response) {
                        console.log(response);
                    }
                });
            }else{
                alert('Fill all fields!');
            }
        });
    });
</script>
</body>
</html>