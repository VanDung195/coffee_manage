@extends('layout.master')
@push('css')
    <style>
    </style>
@endpush
@section('content')
    <div class="form-row">
        <div class="form-group col-3">
            <label for="">Ngày: </label>
            <p class="form-control">{{ $date }}</p>
        </div>
        <div class="form-group col-3">
            <label for="">Ca: </label>
            <p class="form-control">{{ $shift }}</p>
        </div>
    </div>
    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <table class="table table-bordered table-centered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>
                            <label>
                                <input 
                                    type="radio" 
                                    value="1" 
                                    name="statuses[{{ $user->id }}]"
                                    @if (isset($statuses[$user->id]) && $statuses[$user->id] == 1)
                                        checked
                                    @endif
                                >
                                Có đi
                            </label>
                            <label>
                                <input 
                                    type="radio" 
                                    value="2" 
                                    name="statuses[{{ $user->id }}]"
                                    @if (isset($statuses[$user->id]) && $statuses[$user->id] == 2)
                                        checked
                                    @endif
                                >
                                Không đi
                            </label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-secondary">Diem danh</button>
    </form>
@endsection
@push('js')
    
@endpush