@extends('layouts.app')

@php $title = strtoupper('Category List'); @endphp

@section('content-header-left')
    <h3 class="content-header-title mb-2">{{ $title }}</h3>
    <div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Category</li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </div>
    </div>
@endsection
@section('content-header-right')
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
        @can('editor')
            <a class="btn btn-secondary" href="{{ route('category_create') }}">Add Category</a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title">Category list</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Category Name</th>
                                <th class="text-center">Slug</th>
                                <th class="text-center">code</th>
                            </tr>
                            @if (count($datas))
                                @foreach ($datas as $item)
                                    <tr>
                                        <td  class="text-center"><a href="{{ route('category_create', ['id' => $item->id]) }}"> {{  $loop->iteration  }} </a></td>
                                        <td  class="text-center"><a href="{{ route('category_create', ['id' => $item->id]) }}">{{  $item->title  }}</a></td>
                                        <td  class="text-center"><a href="{{ route('category_create', ['id' => $item->id]) }}">{{  $item->slug  }}</a></td>
                                        <td  class="text-center"><a href="{{ route('category_create', ['id' => $item->id]) }}">{{  $item->code  }}</a></td>
                                    </tr>
                                @endforeach  
                            @else
                                <tr>
                                    <td class="text-center" colspan="99">
                                        {{"ไม่มีข้อมูล"}}
                                    </td>
                                </tr>   
                            @endif
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')

@endsection
