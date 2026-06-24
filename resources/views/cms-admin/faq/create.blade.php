@extends('layouts.core.admin')

@section('scripts')
    <script type="text/javascript" src="{{asset('js/admin-basic-forms.js')}}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Create FAQ &amp; Answer
                    <a href="{{route('cms.admin.faq.manage')}}" class="btn btn-sm btn-primary pull-right">All FAQs</a>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal ajax-form" method="post" action="{{route('cms.admin.faq.create')}}">
                        <div class="form-group">
                            <label for="category" class="col-md-3 control-label">Category</label>
                            <div class="col-md-9">
                                <select class="form-control" id="category" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="question" class="col-md-3 control-label">Question</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="question" name="question" required placeholder="Question here...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="answer" class="col-md-3 control-label">Answer</label>
                            <div class="col-md-9">
                                <textarea id="answer" name="answer" required class="form-control" rows="6" placeholder="Answer here..."></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cardinality" class="col-md-3 control-label">Cardinality</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" required value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="published" class="col-md-3 control-label">Published?</label>
                            <div class="col-md-9">
                                <label class="cr-styled">
                                    <input type="checkbox" id="published" name="published" value="1"/>
                                    <i class="fa"></i>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button type="submit" class="btn btn-primary">Add FAQ</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
