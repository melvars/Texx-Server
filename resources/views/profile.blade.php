@extends('layouts.app') @section('content')
<div class="container">

    <div class="row">
        <div id="avatarUploadSucceededAlert" class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong id="avatarUploadSucceededMessage"></strong>
        </div>
        <div id="avatarUploadFailedAlert" class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your upload - please try again.
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="profile-header-container">
            <div class="profile-header-img">
                <img id="image-preview" height="100px" width="100px" class="rounded-circle" src="/avatar/{{ $user->id }}" />
                <!-- badge -->
                <div class="rank-label-container">
                    <span class="label label-default rank-label">{{$user->name}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <form id="avatarForm">
            <div class="form-group">
                <input type="file" class="form-control-file" name="avatar" id="avatarFile" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection