<link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" crossorigin="anonymous" />
<script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.min.js" crossorigin="anonymous"></script>

<div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- /.col -->
                <div class="col-sm-3">
{{--                    {{ Breadcrumbs::render('systems.index') }}--}}
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
                        <h2>{{ __('systems::systems.Systems Management') }}</h2>
                    </div>
                    <div class="pull-left">
                        <a class="btn btn-success" href="{{ route('sso-system.create') }}">{{__('systems::systems.create')}}</a>
                    </div>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                <tr>
                   <th class="col-lg-1"> {{__('systems::systems.name')}} </th>
                   <th class="col-lg-2"> {{__('systems::systems.title')}} </th>
                   <th class="col-lg-2"> {{__('systems::systems.description')}} </th>
                   <th class="col-lg-1"> {{ __('systems::systems.icon') }} </th>
                   <th class="col-lg-2"> {{__('systems::systems.link')}} </th>
                   <th class="col-lg-1"> {{__('systems::systems.key')}}</th>
                   <th class="col-lg-1"> {{ __('systems::systems.created_at')}} </th>
                   <th class="col-lg-2"> {{ __('systems::systems.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($systems as $system)
                    <tr>
                        <td>{{ $system->name }}</td>
                        <td>{{ $system->title }}</td>
                        <td>{{ $system->description }}</td>
                        <td>{{ $system->icon }}</td>
                        <td>{{ $system->link }}</td>
                        <td>{{ $system->key }}</td>
                        <td>{{ jdate($system->created_at)->format('Y/m/d') }}</td>
                        <td>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @can('user-edit')
                                            <a class="btn btn-primary" href="{{ route('systems.edit',$system->id) }}">{{__('systems.edit')}}</a>
                                        @endcan
                                        @can('user-destroy')
                                            {!! Form::open(['method' => 'DELETE','route' => ['systems.destroy', $system->id],'style'=>'display:inline']) !!}
                                            {!! Form::submit(__('systems::systems.delete'), ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
