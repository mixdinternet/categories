@extends('mixdinternet/admix::index')

@section('title')
    Listagem de categorias
@endsection

@section('btn-insert')
    @if((!checkRule('admin.' . $categoryType . '.categories.create')) && (!$trash))
        @include('mixdinternet/admix::partials.actions.btn.insert', ['route' => route('admin.' . $categoryType . '.categories.create')])
    @endif
    @if((!checkRule('admin.' . $categoryType . '.categories.trash')) && (!$trash))
        @include('mixdinternet/admix::partials.actions.btn.trash', ['route' => route('admin.' . $categoryType . '.categories.trash')])
    @endif
    @if($trash)
        @include('mixdinternet/admix::partials.actions.btn.list', ['route' => route('admin.' . $categoryType . '.categories.index')])
    @endif
@endsection

@section('btn-delete-all')
    @if((!checkRule('admin.' . $categoryType . '.categories.destroy')) && (!$trash))
        @include('mixdinternet/admix::partials.actions.btn.delete-all', ['route' => route('admin.' . $categoryType . '.categories.destroy')])
    @endif
@endsection

@section('search')
    {!! Form::model($search, ['route' => ($trash) ? 'admin.' . $categoryType . '.categories.trash' : 'admin.' . $categoryType . '.categories.index', 'method' => 'get', 'id' => 'form-search'
        , 'class' => '']) !!}
    <div class="row">
        <div class="col-md-3">
            {!! BootForm::select('status', 'Status', ['' => '-', 'active' => 'Ativo', 'inactive' => 'Inativo'], null
                , ['class' => 'jq-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! BootForm::text('name', 'Nome') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="{{ route(($trash) ? 'admin.' . $categoryType . '.categories.trash' : 'admin.' . $categoryType . '.categories.index') }}"
                   class="btn btn-default btn-flat">
                    <i class="fa fa-list"></i>
                    <i class="fs-normal hidden-xs">Mostrar tudo</i>
                </a>
                <button class="btn btn-success btn-flat">
                    <i class="fa fa-search"></i>
                    <i class="fs-normal hidden-xs">Buscar</i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('table')
    @if (count($categories) > 0)
        <table class="table table-striped table-hover table-action jq-table-rocket">
            <thead>
            <tr>
                @if((!checkRule('admin.' . $categoryType . '.categories.destroy')) && (!$trash))
                    <th>
                        <div class="checkbox checkbox-flat">
                            <input type="checkbox" id="checkbox-all">
                            <label for="checkbox-all">
                            </label>
                        </div>
                    </th>
                @endif
                <th>{!! columnSort('#', ['field' => 'id', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Nome', ['field' => 'name', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Status', ['field' => 'status', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Destaque', ['field' => 'star', 'sort' => 'asc']) !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    @if((!checkRule('admin.' . $categoryType . '.categories.destroy')) && (!$trash))
                        <td>
                            @include('mixdinternet/admix::partials.actions.checkbox', ['row' => $category])
                        </td>
                    @endif
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>@include('mixdinternet/admix::partials.label.status', ['status' => $category->status])</td>
                    <td>@include('mixdinternet/admix::partials.label.yes-no', ['yesNo' => $category->star])</td>
                    <td>
                        @if((!checkRule('admin.' . $categoryType . '.categories.edit')) && (!$trash))
                            @include('mixdinternet/admix::partials.actions.btn.edit', ['route' => route('admin.' . $categoryType . '.categories.edit', ['category' => $category->id])])
                        @endif
                        @if((!checkRule('admin.' . $categoryType . '.categories.destroy')) && (!$trash))
                            @include('mixdinternet/admix::partials.actions.btn.delete', ['route' => route('admin.' . $categoryType . '.categories.destroy'), 'id' => $category->id])
                        @endif
                        @if($trash)
                            @include('mixdinternet/admix::partials.actions.btn.restore', ['route' => route('admin.' . $categoryType . '.categories.restore', ['categories' => $category->id]), 'id' => $category->id])
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        @include('mixdinternet/admix::partials.nothing-found')
    @endif
@endsection

@section('pagination')
    {!! $categories->appends(request()->except(['page']))->render() !!}
@endsection

@section('pagination-showing')
    @include('mixdinternet/admix::partials.pagination-showing', ['model' => $categories])
@endsection