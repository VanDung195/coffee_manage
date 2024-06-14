@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">    <!-- chia thành 12 cột -->
        <div class="card">
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> <!--vì sao lại dùng tHead các thứ thì sau này dùng mấy cái thư viện gì đó thì hai cái T này quan trọng vãi-->
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>Ngay sinh</th>
                            <th>CCCD</th>
                            <th>So dien thoat</th>
                            <th>chuc vu</th>
                            <th>sua</th>
                            <th>xoa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{route("admin.user.show", $user)}}">
                                        {{$user->id}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->name}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->birthdate}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->CCCD}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->phone}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->role}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    
@endsection