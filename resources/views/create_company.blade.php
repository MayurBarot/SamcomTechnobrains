@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Companies</div>
                <div class="pull-right">
                <a class="btn btn-primary" style="margin: 10px;" href="{{ route('companies.index') }}"> Back</a>
            </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">{{ isset($company) ? 'Edit A Company' : 'Create New Company' }} </div>
                            <div class="card-body">
                                @if(isset($company) && !empty($company))
                                    <form id="master-module" action="{{ route('companies.update',$company->id) }}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                @else
                                    <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                                @endif
                                    @csrf

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', isset($company->name) ? $company->name : '') }}" autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', isset($company->email) ? $company->email : '') }}"  autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">website</label>

                                        <div class="col-md-6">
                                        <input id="website" type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website', isset($company->website) ? $company->website : '') }}">
                                        </div>
                                    </div> 
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">website</label>

                                        <div class="col-md-6">
                                        <input id="logo" type="file" name="logo" value="{{old('logo')}}" class="form-control form-control-user @error('logo') is-invalid @enderror">
                                            @if(isset($company) && $company->logo_path)
                                                <img src="{{ url($company->logo_path) }}" value="{{ old('logo', isset($company) ? $company->logo : '') }}" alt="Image" width="200px" />
                                                @endif
                                            @error('logo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                               Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
