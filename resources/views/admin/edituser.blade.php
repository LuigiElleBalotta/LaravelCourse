@extends('templates.admin')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-auto col-sm-6">
            <h1>Manage user</h1>
            @if($user->id)
            <form action="{{route('users.update', $user->id)}}" method="post">
                @method('PATCH')
            @else
            <form action="{{route('users.store')}}" method="post">
            @endif
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="user's name" value="{{ old('name', $user->name)  }}" />
                    @error('name')
                    <div class="alert alert-danger">
                        @foreach($errors->get('name') as $error)
                            {{ $error }}<br/>
                        @endforeach
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="user's email" value="{{ old('email', $user->email) }}" />
                    @error('email')
                    <div class="alert alert-danger">
                        @foreach($errors->get('email') as $error)
                            {{ $error }}<br/>
                        @endforeach
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="user_role">
                        <option value="">SELECT</option>
                        <option value="admin" {{ old('user_role', $user->user_role) == "admin" ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('user_role', $user->user_role) == "user" ? 'selected' : '' }}>User</option>
                    </select>
                    @error('user_role')
                    <div class="alert alert-danger">
                        @foreach($errors->get('user_role') as $error)
                            {{ $error }}<br/>
                        @endforeach
                    </div>
                    @enderror
                </div>

                <div class="form-group d-flex justify-content-center">
                    <button class="btn btn-info m-2 w-25" type="reset">RESET</button>
                    <button class="btn btn-primary m-2 w-25">SAVE</button>
                </div>
                @csrf
            </form>
        </div>
    </div>
@endsection
