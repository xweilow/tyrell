<!DOCTYPE html>
<html>
<head>
    <title>Card Distribution</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
</head>

<body>
<div class="container">
    <h1>Card Distribution</a></h1>
    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
    <form id="ajaxform">
        <div class="form-group">
            <label>Number of Player:</label>
            <input type="number" name="person" class="form-control" placeholder="" required="" step="1" min="0">
        </div>
        <div class="form-group">
            <button class="btn btn-success save-data">Save</button>
        </div>
    </form>
    <div class="card">

    </div>
</div>
</body>
</html>

<script>

    $(".save-data").click(function (event) {
        event.preventDefault();

        let person = $("input[name=person]").val();
        let _token = $('meta[name="csrf-token"]').attr('content');

        if (isNaN(person)) {
            $('.card').html('Input value does not exist or value is invalid');
            return false;
        }
        if (person < 0) {
            $('.card').html('Invalid value');
            return false;
        }

        $.ajax({
            url: "/",
            type: "POST",
            data: {
                person: person,
                _token: _token
            },
            success: function (response) {
                if (response.code == 200) {
                    $('.card').html(response.data);
                    $("#ajaxform")[0].reset();
                }
            },
            error: function (error) {
                var data = error.responseJSON;
                $('.card').html(data.error_message);
            }
        });
    });
</script>
