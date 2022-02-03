@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Companies</div>
                <div class="pull-right">
                <a class="btn btn-success" style="margin: 10px;" href="{{ route('companies.create') }}"> Create New Company</a>
            </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif


                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Companies List</div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Name</th>
                                        <th>email</th>
                                        <th>website</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                    @if(isset($companies) && !empty($companies) && count($companies) > 0)
                                    @foreach ($companies as $key => $value)
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->website }}</td>
                                        <td>
                                             <form action="{{ route('companies.destroy',$value->id) }}" method="POST">
                                                <a class="btn btn-primary" href="{{ route('companies.edit',$value->id) }}">Edit</a>   
                                                @csrf
                                                @method('DELETE')      
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4">No Records Found</td>
                                    </tr>
                                    @endif
                                </table>  
                                @if(isset($companies) && !empty($companies) && count($companies) > 0)
                                    <span style="float:right">{{$companies->links()}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    setTimeout(function(){
      $('.alert').slideUp();
    },5000);
  </script>