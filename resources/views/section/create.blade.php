@extends('layout.admin.master')

@section('admincontent')
    <div class="container mt-4" style="padding: 60px">
        <h2>Add Section</h2>

        <form action="{{ route('sections.store', $subject->subject_id) }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Section Description</label>
                <textarea class="form-control" name="description" rows="2"></textarea>
            </div>
            <br>
            <br>
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>Save</button>
            <a href="{{ route('sections.index', $subject->subject_id) }}" class="btn btn-secondary"><i
                    class="fa fa-arrow-left"></i>Cancel</a>
        </form>

    </div>
@endsection
