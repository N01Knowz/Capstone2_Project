<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Declaring CSS variables in :root */
        :root {
            --color1: #F0A202;
            --color2: #F18805;
            --color3: #D95D39;
            --color4: #0E1428;
            --color5: #7B9E89;
        }

        body {
            font-family: "Source Sans Pro", sans-serif;
        }

        li p {
            display: inline;
            /* Display the paragraph elements inline */
        }

        .answer-badge {
            display: inline;
            /* Display the answer badge inline */
        }

        img {
            max-width: 150px;
            max-height: 150px;
            clear: both;
            display: block;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .test-description {
            border-bottom: 1px solid #cccccc;
        }

        .test-questions {
            border-bottom: 1px solid #cccccc;
        }

        .answer-badge {
            background-color: var(--color1);
            border: 1px solid black;
            padding: 2px 5px 2px 5px;
            border-radius: 25px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="test-description">
        <p>Title: {{$test->test_title}}</p>
        <p>Instruction: {{$test->test_instruction}}</p>
        <p>Total Points: {{$test->test_total_points}}</p>
    </div>
    @foreach($questions as $question)
    <div class="test-questions">
        <p>{{$loop->index + 1}}.</p>
        <p>{{ $question->item_question }}</p>
        @if($question->question_image)
        <img src="/user_upload_images/{{$question->question_image}}">
        @endif
        <ul>
            @for($i = 1; $i <= 10; $i++) @if($question->{'option_' . $i})
                <li>
                    <p>
                        {!! $question->{'option_' . $i} !!}
                        @if($question->question_answer == $i)
                        <span class="answer-badge">Answer</span>
                        @endif
                    </p>
                </li>
                <br>
                @endif
                @endfor
        </ul>
    </div>
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>