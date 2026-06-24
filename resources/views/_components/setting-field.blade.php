@if($setting->form_type == 'image')
    @php
        $key = $setting->key();
        $props = $setting->form_options
    @endphp
    <div class="col-md-6">
        <div class="form-group">
            <div class="pic-container text-center">
                <div class="padd-lg">
                    <img class="img-responsive center-block cropper-destination {{$key}}" itemprop="image" src="{{$setting->getImageUrl('value')}}"
                         alt="{{$setting->getImageUrl('value')}}" style="max-width: {{$props['width']}} !important;max-height: {{$props['height']}};"/>
                </div>
                <div class="after text-center" style="padding-top: 2em;">
                    <label class="btn btn-primary" for="{{$key}}">
                        <span class="fa fa-camera"></span>
                        <strong>
                            @if(empty($setting->value))
                                Upload {{strtoupper($key)}} ({{$props['width']}}x{{$props['height']}} px)
                            @else
                                Change {{strtoupper($key)}} ({{$props['width']}}x{{$props['height']}} px)
                            @endif
                        </strong>
                    </label>
                    <input class="hidden cropper-source" id="{{$key}}" type="file" data-handler="{{route('core.admin.setting.image', ['setting'=> $setting->key()])}}"
                           data-width="{{$props['width']}}" data-height="{{$props['height']}}" data-attribute="value"
                           data-preview="{{$setting->getImageUrl('value')}}" data-viewmode="0" accept=".png,.jpg,.jpeg"/>
                </div>
            </div>
        </div>
    </div>

@else

    <div class="row mb-3">
        <label for="{{$setting->key()}}" class="col-sm-3 col-form-label">{{$setting->form_label}}</label>
        <div class="col-sm-9">
            @if(in_array($setting->form_type, ['text', 'number', 'tel', 'email', 'password']))
                <input type="{{$setting->form_type}}" class="form-control" id="{{$setting->key()}}" name="{{$setting->key()}}" value="{{$setting->value}}"
                       @if($setting->required) required @endif placeholder="{{$setting->form_placeholder}}">
            @elseif(in_array($setting->form_type, ['textarea','json']))
                <textarea class="form-control" id="{{$setting->key()}}" name="{{$setting->key()}}" @if($setting->required) required
                          @endif  placeholder="{{$setting->form_placeholder}}">{{$setting->value}}</textarea>
            @elseif(in_array($setting->form_type,['boolean', 'select']))
                <select class="form-control" id="{{$setting->key()}}" name="{{$setting->key()}}" @if($setting->required) required @endif>
                    @foreach($setting->form_options as $key=>$value)
                        <option @if($setting->value == $key) selected @endif value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            @elseif(in_array($setting->form_type,['options']))
                <select class="form-control chosen-select" id="{{$setting->key()}}" name="{{$setting->key()}}[]" multiple @if($setting->required) required @endif>
                    @foreach($setting->form_options as $key=>$value)
                        <option @if(in_array($key,$setting->getValue())) selected @endif value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            @endif
            @if(strlen((string)$setting->description))
                <span class="text-muted small">{{$setting->description}}</span>
            @endif
        </div>
    </div>
@endif