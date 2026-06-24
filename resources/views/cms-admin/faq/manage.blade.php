@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3 class="clearfix">
            FAQs <small>(Frequently Asked Questions)</small>
            <a href="{{route('cms.admin.faq.create')}}" class="btn btn-success pull-right">New FAQ...</a>
        </h3>
    </div>

    <form class="ajax-form" method="post" action="{{route('cms.admin.faq.manage')}}">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="2%">##</th>
                            <th>Category</th>
                            <th>Question</th>
                            <th>Status</th>
                            <th width="10%">&hellip;</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = start_sn($faqs) @endphp
                        @forelse($faqs as $faq)
                            <tr class="@if($faq->trashed()) danger @endif">
                                <td>{{$sn++}}</td>
                                <td>{{$faq->category->title}}</td>
                                <td>
                                    <label for="i-{{$faq->id}}">{{$faq->question}}</label>
                                <!--{!! clamp($faq->answer,200) !!}-->
                                </td>
                                <td>{{$faq->isPublished()?'Published':'Draft'}}</td>
                                <td class="center no-wrap"><a href="{{$faq->edit_url}}"><i class="fa fa-edit"></i> Edit</a></td>
                                <td class="center"><input type="checkbox" name="ids[]" value="{{$faq->id}}" id="i-{{$faq->id}}"></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <strong>No FAQs yet.</strong>
                                    <p><a href="{{route('cms.admin.faq.create')}}">Add FAQ</a></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">{{$faqs->links()}}</div>
                <div class="pull-right">
                    <select class="form-control d-inline btn btn-sm text-left" style="width: auto;" name="action">
                        <option value="trash">Trash</option>
                        <option value="recycle">Recycle</option>
                        <option value="delete">Delete Forever</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
        </div>
    </form>
@endsection
