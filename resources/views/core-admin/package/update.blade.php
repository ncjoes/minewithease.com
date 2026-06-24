@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
    <script src="{{asset('js/photo-cropper.js')}}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Update Investment Package
                    <a href="{{route('core.admin.package.manage')}}" class="btn btn-sm btn-primary pull-right">Manage Packages</a>
                </div>
                <div class="panel-body pt-0">
                    <form class="form-horizontal ajax-form" method="post" action="{{$package->edit_url}}">
                        @csrf
                        <div class="form-group">
                            <div class="pic-container text-center">
                                <div class="padd-xs">
                                    <img class="img-responsive center-block cropper-destination photo"
                                         itemprop="image" src="{{$package->photo_url}}" alt="{{$package->name}}"/>
                                </div>
                                <div class="after text-center" style="padding-top: 2em;">
                                    <label class="btn btn-primary" for="cover_photo">
                                        <span class="fa fa-camera"></span>
                                        <strong>
                                            @if(empty($package->photo))
                                                Upload Image
                                            @else
                                                Change Image
                                            @endif
                                        </strong>
                                    </label>
                                    <input class="hidden cropper-source" id="cover_photo" type="file"
                                           data-handler="{{route('core.admin.package.image', ['package' => $package->id])}}"
                                           data-width="320" data-height="320" data-attribute="photo"
                                           data-preview="{{$package->photo_url}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{$package->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" required class="form-control">{{$package->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_interest_rate" class="col-sm-3 control-label">Min. Interest Rate</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="min_interest_rate" name="min_interest_rate" placeholder="10" step="0.001" value="{{$package->min_interest_rate}}"
                                       required>
                                <span class="text-muted small">e.g. 10 for 10% rate</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_interest_rate" class="col-sm-3 control-label">Max. Interest Rate</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="max_interest_rate" name="max_interest_rate" placeholder="10" step="0.001" value="{{$package->max_interest_rate}}"
                                       required>
                                <span class="text-muted small">e.g. 10 for 10% rate</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="interest_interval" class="col-sm-3 control-label">Interest Interval</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="interest_interval" name="interest_interval" min="1" required placeholder="1 day"
                                       value="{{$package->interest_interval}}">
                                <span class="text-muted small">In days. Amount of time for interest to be awarded</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_duration" class="col-sm-3 control-label">Min. Duration</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="min_duration" name="min_duration" min="0" placeholder="7 days" value="{{$package->min_duration}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_duration" class="col-sm-3 control-label">Max. Duration</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="max_duration" name="max_duration" placeholder="30 days" value="{{$package->max_duration}}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_amount" class="col-sm-3 control-label">Min. Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="min_amount" name="min_amount" placeholder="100" step="0.001" required
                                       value="{{$package->min_amount}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_amount" class="col-sm-3 control-label">Max. Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="max_amount" name="max_amount" placeholder="10000" step="0.001" required
                                       value="{{$package->max_amount}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="split_amount" class="col-sm-3 control-label">Split Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="split_amount" name="split_amount" placeholder="10" step="0.001" required
                                       value="{{$package->split_amount}}">
                            </div>
                        </div>

                    <!--
                        <div class="form-group">
                            <label for="service_charge_rate" class="col-sm-3 control-label">Service Charge (Rate)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="service_charge_rate" name="service_charge_rate" value="{{$package->service_charge_rate}}"
                                       placeholder="10%" step="0.001" required>
                            </div>
                        </div>
                        -->

                        <div class="form-group">
                            <label for="rbr" class="col-sm-3 control-label">Referral Bonus Rates</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="rbr" name="referral_bonus_rate" placeholder="3,2" required value="{{$package->referral_bonus_rate}}">
                                <span class="text-muted small">e.g. 3,2 for 3%,2% rate</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rbc" class="col-sm-3 control-label">Referral Bonus Count</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="rbc" name="referral_bonus_count" placeholder="1" required value="{{$package->referral_bonus_count}}">
                                <span class="text-muted small">Max number of bonuses to be awarded for each referral</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rbrt" class="col-sm-3 control-label">Referral Bonus Release Time</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="rbrt" name="referral_bonus_release_time" placeholder="10 hours"
                                       value="{{$package->referral_bonus_release_time}}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="is_active" value="1" @if($package->is_active) checked @endif>
                                    <i class="fa"></i> Is Active?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-success">Update Investment Package</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('_components.image-previewer')
@endsection
