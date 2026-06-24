@extends('layouts.core.admin')

@section('scripts')
    @parent
    <script src="{{asset('js/admin-basic-forms.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/cropper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/preview.js')}}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-2 col-lg-6 col-lg-offset-3">
            <div class="page-header">
                <h3><strong>Configs & Settings</strong></h3>
            </div>
            <hr/>

            @foreach($groups as $group)
                <div class="panel panel-default">
                    <div class="panel-heading"><h4 style="color: #000;">{{strtoupper(substr($group, 2))}}</h4></div>

                    <div class="panel-body">
                        <form class="ajax-form" method="post" action="{{route('core.admin.setting.update')}}">
                            {{csrf_field()}}
                            @php $needs_saving = false @endphp
                            @foreach($settings[$group] as $setting)
                                @include('_components.setting-field')
                                @if($setting->form_type != 'image')
                                    @php $needs_saving = true @endphp
                                @endif
                            @endforeach

                            @if($needs_saving)
                                <div class="form-group">
                                    <div class="col-sm-9 col-sm-offset-3">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes to {{strtoupper(substr($group, 2))}}</button>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    @include('_components.image-previewer')
@endsection
