@extends('layout.admin.master')
@section('title', 'Edit Section')
@section('admincontent')

    <div class="container mt-5" style="padding: 80px;">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4 text-center">Edit Section</h3>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sections.update', $section->section_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Title</label>
                    <input type="text" name="name" value="{{ old('name', $section->sec_title) }}" class="form-control"
                        required>
                </div>

                <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $section->sec_description) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Subject</label>
                    <select name="subject_id" class="form-control" required>
                        @foreach ($subjects as $sub)
                            <option value="{{ $sub->subject_id }}" @if ($sub->subject_id == $section->subject_id) selected @endif>
                                {{ $sub->subject_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('sections.index', $section->subject_id) }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>

@endsection
