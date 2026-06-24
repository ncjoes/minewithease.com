@extends('layouts.core.client')

@section('content')
    <h3 class="page-header">My Referrals <small>(Total - {{count($referrals)}})</small></h3>
    <hr/>

    <div class="panel panel-success">
        <div class="panel-body">
            <div class="form-group">
                <label for="referral-link-to-home">Referral Link to Homepage</label>
                <div class="input-group">
                    <input readonly value="{{$user->referralLinkToHome()}}" id="referral-link-to-home" style="cursor: pointer;" class="form-control">
                    <p class="input-group-addon p-0">
                        <button class="btn btn-primary copy-btn m-0" type="button" data-clipboard-target="#referral-link-to-home">Copy <i class="fa fa-copy"></i></button>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="referral-link">Referral Link to Registration Page</label>
                <div class="input-group">
                    <input readonly value="{{$user->referralLink()}}" id="referral-link" style="cursor: pointer;" class="form-control">
                    <p class="input-group-addon p-0">
                        <button class="btn btn-primary copy-btn m-0" type="button" data-clipboard-target="#referral-link">Copy <i class="fa fa-copy"></i></button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="ajax-form" method="post" action="{{route('core.client.referral.manage')}}">
                {{csrf_field()}}
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" border="0"
                           class="table table-striped table-bordered table-hover"
                           id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="2%">##</th>
                            <th>Account UUID</th>
                            <th width="10%">Country</th>
                            <th>Name</th>
                            <th>Total-Investments</th>
                            <th width="10%">Downlines</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = end_sn($referrals) @endphp
                        @forelse($referrals as $referral)
                            <tr>
                                <td>{{$sn--}}</td>
                                <td>{{$referral->uuid}}</td>
                                <td>{{$referral->country->name}}</td>
                                <td>{{$referral->name}}</td>
                                <td class="money">{{$referral->getTotalPortfoliosStr()}}</td>
                                <td>{{$referral->referrals()->count()}}</td>
                                <td>{{$referral->status()}}</td>
                                <td>{{date_time_for_humans($referral->created_at)}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    <strong>No referrals yet.</strong>
                                    <p>
                                        Invite traders and investors to register with your referral link
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="justify-content-center text-center">{{$referrals->links()}}</div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script>
        $(function () {
            new ClipboardJS('.copy-btn');
        })
    </script>
@endsection