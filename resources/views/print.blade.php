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
            padding-bottom: 5px;
        }

        .answer-badge {
            background-color: var(--color1);
            border: 1px solid black;
            padding: 2px 5px 2px 5px;
            border-radius: 25px;
            font-weight: bold;
        }

        table,
        td,
        th {
            border: 1px solid;
            text-align: center;
        }

        th {
            width: 50%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<?php
$test_tables = [
    'mcq' => [
        'title' => 'qzTitle',
        'description' => 'qzDescription',
        'total_points' => 'qzTotal',
    ],
    'tf' => [
        'title' => 'tfTitle',
        'description' => 'tfDescription',
        'total_points' => 'tfTotal',
    ],
    'mtf' => [
        'title' => 'mtfTitle',
        'description' => 'mtfDescription',
        'total_points' => 'mtfTotal',
    ],
]
?>

<body>
    @if(in_array($test_type, ['mcq', 'tf', 'mtf']))
    <div class="test-description">
        <p>Title: {{ $test->{$test_tables[$test_type]['title']} }}</p>
        <p>Description: {{ $test->{$test_tables[$test_type]['description']} }}</p>
        <p>Total Points: {{ $test->{$test_tables[$test_type]['total_points']} }}</p>
    </div>
    @foreach($questions as $question)
    <div class="test-questions">
        <p>{{$loop->index + 1}}.</p>
        <p>{{ $question->itmQuestion }}</p>
        @if($question->itmImage)
        <img src="/user_upload_images/{{$question->itmImage}}">
        @endif
        <ul>
            @for($i = 1; $i <= 10; $i++) @if($question->{'itmOption' . $i})
                <li>
                    <p>
                        {!! $question->{'itmOption' . $i} !!}
                        @if($question->itmAnswer == $i)
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
    @endif
    @if($test_type == 'tm')
    <div class="test-description">
        <p>Title: {{ $test->tmTitle }}</p>
        <p>Description: {{ $test->tmDescription }}</p>
        <p>Total Points: {{ $test->tmTotal }}</p>
    </div>
    @if(!$mcq_questions->isEmpty())
    <div>
        <h1>Multiple Choice Questions</h1>
        @foreach($mcq_questions as $question)
        <div class="test-questions">
            <p>{{$loop->index + 1}}. {{ $question->itmQuestion }}</p>
            @if($question->itmImage)
            <img src="/user_upload_images/{{$question->itmImage}}">
            @endif
            <ul>
                @for($i = 1; $i <= 10; $i++) @if($question->{'itmOption' . $i})
                    <li>
                        <p>
                            {!! $question->{'itmOption' . $i} !!}
                            @if($question->itmAnswer == $i)
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
    </div>
    @endif

    @if(!$tf_questions->isEmpty())
    <div>
        <h1>True or False</h1>
        @foreach($tf_questions as $question)
        <div class="test-questions">
            <p>{{$loop->index + 1}}. {{ $question->itmQuestion }}</p>
            @if($question->itmImage)
            <img src="/user_upload_images/{{$question->itmImage}}">
            @endif
            <ul>
                @for($i = 1; $i <= 10; $i++) @if($question->{'itmOption' . $i})
                    <li>
                        <p>
                            {!! $question->{'itmOption' . $i} !!}
                            @if($question->itmAnswer == $i)
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
    </div>
    @endif

    @if(!$mtf_questions->isEmpty())
    <div>
        <h1>Modified True or False</h1>
        @foreach($mtf_questions as $question)
        <div class="test-questions">
            <p>{{$loop->index + 1}}. {{ $question->itmQuestion }}</p>
            @if($question->itmImage)
            <img src="/user_upload_images/{{$question->itmImage}}">
            @endif
            <ul>
                @for($i = 1; $i <= 10; $i++) @if($question->{'itmOption' . $i})
                    <li>
                        <p>
                            {!! $question->{'itmOption' . $i} !!}
                            @if($question->itmAnswer == $i)
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
    </div>
    @endif
    @if(!$essay_questions->isEmpty())
    <div>
        <h1>Essay</h1>
        @foreach($essay_questions as $question)
        <div class="test-questions">
            <p>{{$loop->index + 1}}. {{ $question->essQuestion }}</p>
            @if($question->essImage)
            <img src="/user_upload_images/{{$question->essImage}}">
            @endif
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Point(s)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $question->essCriteria1 }}</td>
                        <td>{{ $question->essScore1 }}</td>
                    </tr>
                    @if($question->essCriteria2)
                    <tr>
                        <td>{{ $question->essCriteria2 }}</td>
                        <td>{{ $question->essScore2 }}</td>
                    </tr>
                    @endif
                    @if($question->essCriteria3)
                    <tr>
                        <td>{{ $question->essCriteria3 }}</td>
                        <td>{{ $question->essScore3 }}</td>
                    </tr>
                    @endif
                    @if($question->essCriteria4)
                    <tr>
                        <td>{{ $question->essCriteria4 }}</td>
                        <td>{{ $question->essScore4 }}</td>
                    </tr>
                    @endif
                    @if($question->essCriteria5)
                    <tr>
                        <td>{{ $question->essCriteria5 }}</td>
                        <td>{{ $question->essScore5 }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endif
    @if(!$matching_tests->isEmpty())
    <div>
        <h1>Matching</h1>
        @foreach($matching_tests as $test)
        <div class="test-questions">
            <p>{{$loop->index + 1}}. {{ $test->mtDescription }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Item Text</th>
                        <th>Answer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matching_questions as $question)
                    @if($question->mtID == $test->mtID)
                    <tr>
                        <td>{{ $question->itmQuestion }}</td>
                        <td>{{ $question->itmAnswer }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endif
    @if(!$enumeration_tests->isEmpty())
    <div>
        <h1>Enumeration</h1>
        @foreach($enumeration_tests as $test)
        <div class="test-questions">
            <p>{{$loop->index + 1}}. {{ $test->etDescription }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Answer</th>
                        <th>Case Sensitive</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enumeration_questions as $question)
                    @if($question->etID == $test->etID)
                    <tr>
                        <td>{{ $question->itmAnswer }}</td>
                        <td>@if( $question->itmIsCaseSensitive)
                            Yes
                            @else
                            No
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endif
    @endif

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>