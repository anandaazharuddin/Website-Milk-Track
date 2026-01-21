@extends('layouts.app')

@section('title','Manajemen User')

@section('content')
<div class="container mt-4">
    <h2>Manajemen User</h2>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Tambah User</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td><td>{{ $user->name }}</td><td>{{ $user->username }}</td>
                <td>{{ $user->email ?? '-' }}</td><td>{{ ucfirst($user->role) }}</td>
                <td>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada user</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
