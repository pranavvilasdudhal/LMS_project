@extends('layout.admin.master')

@section('admincontent')
    <div class="container mt-4"style="padding: 30px">
        <h2>Add subject</h2>

        <form action="{{route('subject.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

           <div class="form-group">
                    <label for="comment">Subject Description</label>
                    <textarea class="form-control" id="comment" name="description" rows="2"></textarea>
                </div>
<br>
           <div class="form-group">
                    <label for="exampleFormControlFile1">Subject Image</label>
                    <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1" />
                </div>
                <br>

            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>Save</button>
            <a href="{{ route('subject.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Cancel</a>
        </form>
    </div>
@endsection
