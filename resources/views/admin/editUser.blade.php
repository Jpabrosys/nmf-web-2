<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Edit User</h3>
                            </div>
                            <div class="uploads-container row px-1">
                                <form method="post" action="{{ asset('alluserslist/edit') }}/{{ $user->id }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row ">
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text"
                                                value="{{ $user->name }}" name="name" id="name">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            @error('name')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text"
                                                value="{{ $user->url_name }}" name="url_name" id="url_name">
                                            <label for="name">Url Name(Name In English) <span
                                                    class="text-danger">*</span></label>
                                            @error('url_name')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('url_name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class=" input-field col-md-6">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="role">
                                                <option value="0">Select Role <span class="text-danger">*</span>
                                                </option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" <?php if ($user->role == $role->id) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $role->role_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('role') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-6">
                                            <input type="file" value="{{ old('image') }}" id="image"
                                                name="image">
                                            <label for="name">Upload Image</label>
                                            {{ isset($file->file_name) ? $file->file_name : '' }}
                                            @error('image')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('image') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {{-- Twitter Link --}}
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="twitter_link"
                                                value="{{ $user->twitter_link }}" id="twitter_link"
                                                oninput="clearError('twitter_link-error')">
                                            <label for="twitter_link">Twitter Link</label>

                                            @error('twitter_link')
                                                <div class="input-group-append" id="twitter_link-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1">
                                                            <i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('twitter_link') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="email" name="email"
                                                value="{{ $user->email }}" id="email">
                                            <label for="email">Email <span class="text-danger">*</span></label>

                                            @error('email')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1">
                                                            <i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('email') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>




                                    <div class="form-group row">
                                        <div class=" input-field mb-4">
                                            <label for="name">Description</label>
                                            <textarea class="txtar" name="description" id="description" rows="10" class="form-control">{{ $user->description }}</textarea>
                                            @error('description')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('description') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button class="--btn btn-submit mt-4 " type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection