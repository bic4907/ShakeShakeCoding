    <input type="hidden" name="_method" value="put"/>

    {{ csrf_field() }}

    <h2>프로필</h2>
    <hr class="mb-4">
    <div class="form-group">
        <label>이름</label>
        <p>
            {{$name}}
        </p>
        @if ($errors->has('name'))
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>
    <hr>
    <div class="form-group">
        <label>이메일</label>
        <p>{{$email}}</p>
    @if ($errors->has('email'))
            <div class="invalid-feedback">
                {{ $errors->first('email') }}
            </div>
        @endif
    </div>
    <hr>
    <div class="form-group">
        <label>교수 / 학생</label>
        <p>
            @if($usertype == App\Enums\UserType::Professor)
                교수
                @else
                학생
            @endif
        </p>
        @if ($errors->has('email'))
            <div class="invalid-feedback">
                {{ $errors->first('email') }}
            </div>
        @endif
    </div>
