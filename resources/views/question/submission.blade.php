@extends('layouts.light')


@section('content')

    <div class="container my-1 py-3">
        <div class="container mt-5">
            <h2>과제 제출 정보</h2>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">이름</th>
                    <th scope="col">제출 횟수</th>
                    <th scope="col">정답 여부</th>
                </tr>
                </thead>

                <tbody>
                @for($i=0;$i<sizeof($student);$i++)
                <tr>
                    <td width="40%" class="lead">
                            {{$student[$i]->student_id}}
                    </td>
                    <td width="300px" class="lead">{{$submitCount[$i]}}</td>
                    <td class="lead"> {{$correct[$i]}} </td>
                </tr>
                @endfor

                </tbody>
            </table>
        </div>
    </div>
@endsection


