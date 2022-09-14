@extends('layouts.dashboard.master')
@section('title')
    {{ __('breadcrumbs.edit')}} {{ __('systems.system')  }}
@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- /.col -->
                <div class="col-sm-3">
{{--                    {{ Breadcrumbs::render('systems.edit') }}--}}
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <h2>{{__('systems.edit')}} {{ __('systems.system')  }}</h2>
                    </div>
                </div>
            </div>
            <br>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    {{ __('systems.error-validation') }}<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model($system_list, ['method' => 'PATCH','route' => ['systems.update', $system_list->id]]) !!}
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{__('systems.name')}}:</strong>
                            {!! Form::text('name', $system_list->name, array('placeholder' => 'Name', 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems.title')}}:</strong>
                                {!! Form::text('title', $system_list->title, array('placeholder' => 'title', 'class' => 'form-control')) !!}
                        </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{__('systems.description')}}:</strong>
                            {!! Form::text('description', $system_list->description, array('placeholder' => 'description', 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{__('systems.icon')}}:</strong>
                            {!! Form::text('icon', $system_list->icon, array('placeholder' => 'icon', 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{__('systems.link')}}:</strong>
                            {!! Form::text('link', $system_list->link, array('placeholder' => 'link', 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{__('systems.key')}}:</strong>
                            {!! Form::text('key', $system_list->key, array('placeholder' => 'key', 'class' => 'form-control', 'disabled')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">{{__('systems.submit')}}</button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection
