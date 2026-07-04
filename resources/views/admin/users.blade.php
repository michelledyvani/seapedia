@extends('layouts.app')
@section('title', 'Monitoring User')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">👥 Semua User</h1>
    <div class="overflow-x-auto">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>Nama</th><th>Username</th><th>Email</th><th>Roles</th><th>Bergabung</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td class="font-mono text-xs">{{ $u->username }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @foreach($u->userRoles as $r)
                        <span class="badge badge-sm badge-outline capitalize">{{ $r->role }}</span>
                        @endforeach
                    </td>
                    <td class="text-xs">{{ $u->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
