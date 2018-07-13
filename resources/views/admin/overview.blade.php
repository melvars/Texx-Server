@extends('admin.layouts.app')

@section('content')
{{ $user }}
@endsection 

@section('content-header')
<h1>
    Overview
    <small>Overview of all data</small>
</h1>
<ol class="breadcrumb">
    <li>
        <a href="#">
            <i class="fa fa-dashboard"></i> Administration</a>
    </li>
    <li class="active">Overview</li>
</ol>
@endsection