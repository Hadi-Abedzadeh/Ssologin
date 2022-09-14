@extends('systems::layout')
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
                        <h2>{{__('systems::systems.edit')}} {{ __('systems::systems.system')  }}</h2>
                    </div>
                </div>
            </div>
            <br>
{{--            @if (count($errors) > 0)--}}
{{--                <div class="alert alert-danger">--}}
{{--                    {{ __('systems.error-validation') }}<br><br>--}}
{{--                    <ul>--}}
{{--                        @foreach ($errors->all() as $error)--}}
{{--                            <li>{{ $error }}</li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            @endif--}}

            <form action="{{route('ssoSystem.store')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems::systems.name')}}:</strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems::systems.title')}}:</strong>
                            {!! Form::text('title', null, array('placeholder' => 'title', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems::systems.description')}}:</strong>
                            {!! Form::text('description', null, array('placeholder' => 'description', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems::systems.icon')}}:</strong>
                            {!! Form::file('icon', array('placeholder' => 'icon', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems::systems.link')}}:</strong>
                            {!! Form::text('link', null, array('placeholder' => 'link', 'class' => 'form-control')) !!}
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{__('systems::systems.permissions')}}:</strong>
                            <table class="">
                                @foreach($permissions->chunk(4) as $permission)
                                    <tr>
                                    @foreach($permission as $perm)
                                        <td>
                                            {{ Form::checkbox('access_'.$perm->name, $perm->name, true, ['id'=> $perm->name]) }}
                                            <label for="{{$perm->name}}">{{$perm->name}}</label>
                                        </td>
                                    @endforeach
                                    </tr>
                            @endforeach
                            </table>
                        </div>
                    </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">{{__('systems::systems.submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
