@extends('layouts.core.client')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Web App</a></li>
            <li class="breadcrumb-item active">Notifications</li>
        </ol>
    </nav>

    <div class="pb-9">
        <form method="post" class="ajax-form" action="{{route('core.client.notifications')}}">
            <div class="d-flex justify-content-between">
                <h2 class="mb-7">My Notification History</h2>
                <div>
                    <select name="action" class="form-control form-control-sm d-inline w-auto">
                        <option>with selected?</option>
                        <option value="read">Mark as read</option>
                        <option value="unread">Mark as unread</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button class="btn btn-primary btn-sm fs-9 fw-normal" type="submit">Apply</button>
                </div>
            </div>

            <div class="mx-n4 mx-lg-n6 mb-9 border-bottom">
                @foreach($notifications as $notification)
                    <div class="d-flex align-items-center justify-content-between py-3 px-lg-6 px-4 notification-card border-top @if($notification->unread()) unread @endif">
                        <div class="d-flex">
                            <!--
                            <div class="avatar avatar-xl me-3"><img class="rounded-circle" src="../assets/img/team/57.webp" alt=""/></div>
                            -->
                            <div class="me-3 flex-1 mt-2">
                                <label for="item-{{$notification->id}}">
                                    <h4 class="fs-9 text-body-emphasis">{{$notification->data['title']}}</h4>
                                    <p class="fs-9 text-body-highlight">
                                        {{$notification->data['message']}}
                                        @if(!is_null($notification->data['action_url']))
                                            <a href="{{$notification->data['action_url']}}">More...</a>
                                        @endif
                                    </p>
                                    <p class="text-body-secondary fs-9 mb-0">
                                        <span class="me-1 fas fa-clock"></span><span class="fw-bold">{{time_for_humans($notification->created_at)}} </span>
                                        {{date_for_humans($notification->created_at)}}
                                    </p>
                                </label>
                            </div>
                        </div>
                        <div class="checkboxes">
                            <input type="checkbox" name="notification[]" class="form-check-input" id="item-{{$notification->id}}" value="{{$notification->id}}">
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
        <div>{{$notifications->links()}}</div>
    </div>
@endsection