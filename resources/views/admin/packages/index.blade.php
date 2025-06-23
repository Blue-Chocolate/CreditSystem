@extends('layouts.admin')

@section('content')
    <style>
        .btn {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        form.inline {
            display: inline;
        }

        .form-box {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f8f8f8;
        }

        input[type="text"], input[type="number"] {
            padding: 6px;
            width: 100%;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }
    </style>

    <h2>Credit Packages</h2>

    <button onclick="document.getElementById('addForm').style.display='block'" class="btn">+ Add Package</button>

    {{-- Add Package Form --}}
    <div id="addForm" class="form-box" style="display: none;">
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            <label>Name</label>
            <input type="text" name="name" required>

            <label>Price</label>
            <input type="number" name="price" step="0.01" required>

            <label>Credit Points</label>
            <input type="number" name="credits" required>

            <label>Reward Points</label>
            <input type="number" name="reward_points" required>

            <button type="submit" class="btn">Save</button>
        </form>
    </div>

    {{-- Packages Table --}}
    <table>
        <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Credit Points</th>
                <th>Reward Points</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ $package->id }}</td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->price }}</td>
                    <td>{{ $package->credits }}</td>
                    <td>{{ $package->reward_points }}</td>
                    <td>
                        <a href="{{ route('packages.edit', $package->id) }}" class="btn">Edit</a>
                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background-color: red;">Delete</button>
                        </form>
                        <a href="{{ route('packages.show', $package->id) }}" class="btn" style="background-color: gray;">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
