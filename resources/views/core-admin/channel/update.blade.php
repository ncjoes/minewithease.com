@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
    <script src="{{asset(mix('js/photo-cropper.js'))}}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Update Payment Channel
                    <a href="{{route('core.admin.channel.manage')}}" class="btn btn-sm btn-primary pull-right">Manage Channels</a>
                </div>
                <div class="panel-body pt-0">
                    <form class="form-horizontal ajax-form" method="post" action="{{$channel->edit_url}}">
                        <div class="form-group">
                            <div class="pic-container text-center">
                                <div class="padd-xs">
                                    <img class="img-responsive center-block cropper-destination photo"
                                         itemprop="image" src="{{$channel->photo_url}}" alt="{{$channel->name}}"/>
                                </div>
                                <div class="after text-center" style="padding-top: 2em;">
                                    <label class="btn btn-primary" for="cover_photo">
                                        <span class="fa fa-camera"></span>
                                        <strong>
                                            @if(empty($channel->photo))
                                                Upload Image
                                            @else
                                                Change Image
                                            @endif
                                        </strong>
                                    </label>
                                    <input class="hidden cropper-source" id="cover_photo" type="file"
                                           data-handler="{{route('core.admin.channel.image', ['channel' => $channel->id])}}"
                                           data-width="320" data-height="320" data-attribute="photo" data-preview="{{$channel->photo_url}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="currency" class="col-sm-3 control-label">Local Currency</label>
                            <div class="col-sm-9">
                                <select name="currency" id="currency" class="form-control form-control-sm" required>
                                    @foreach($currencies as $currency)
                                        <option @if($currency->id==$channel->currency_id) selected @endif value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{$channel->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="website" class="col-sm-3 control-label">Website</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="website" name="website" placeholder="Payment processor's website" value="{{$channel->website}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" required class="form-control">{{$channel->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_wallet" class="col-sm-3 control-label">Payment Wallet</label>
                            <div class="col-sm-9">
                                <input id="payment_wallet" name="payment_wallet" required class="form-control" value="{{$channel->payment_wallet}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_amount" class="col-sm-3 control-label">Min. Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="min_amount" name="min_amount" placeholder="100" value="{{$channel->min_amount}}" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_amount" class="col-sm-3 control-label">Max. Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="max_amount" name="max_amount" placeholder="10000" value="{{$channel->max_amount}}" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="split_amount" class="col-sm-3 control-label">Split Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="split_amount" name="split_amount" placeholder="10" value="{{$channel->split_amount}}"
                                       step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="is_active" value="1"
                                           @if($channel->is_active) checked @endif>
                                    <i class="fa"></i>
                                    Is Active?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="for_inflow" value="1" @if($channel->for_inflow) checked @endif>
                                    <i class="fa"></i> For Inflow?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="for_outflow" value="1" @if($channel->for_outflow) checked @endif>
                                    <i class="fa"></i> For Outflow?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-success">Update Payment Channel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('_components.image-previewer')
@endsection
