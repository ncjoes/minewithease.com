<div class="popup_wrap popup_registration bg_tint_light" id="popup_registration">
    <a class="popup_close" href="#"></a>
    <div class="form_wrap">
        <form class="popup_form registration_form ajax-form" id="registration_form" method="post" name="registration_form" action="{{route('auth.register')}}">
            @csrf
            <input name="redirect_to" type="hidden" value="#">
            <div class="logo">
                <a href="{{route('cms.home')}}"><img alt="" class="logo_login" src="{{asset('images/logo_dark.png')}}"></a>
            </div>
            <div class="popup_form_field login_field iconed_field">
                <select name="country" id="country" required style="width: 100%; color: #a9a9a9; background-color: #292a2e;">
                    <option>Country</option>
                    @foreach($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="popup_form_field login_field iconed_field">
                <input id="first_name" name="first_name" placeholder="First name" required type="text" value="">
            </div>
            <div class="popup_form_field login_field iconed_field">
                <input id="last_name" name="last_name" placeholder="Last name" required type="text" value="">
            </div>
            <div class="popup_form_field login_field iconed_field">
                <input id="phone" name="phone" placeholder="Phone number" required type="text" value="">
            </div>
            <div class="popup_form_field email_field iconed_field">
                <input id="email" name="email" placeholder="E-mail Address" required type="email" value="">
            </div>
            <div class="popup_form_field password_field iconed_field">
                <input id="password" name="password" placeholder="Password" required type="password" value="">
            </div>
            <div class="popup_form_field password_field iconed_field">
                <input id="password_confirmation" name="password_confirmation" required placeholder="Confirm Password" type="password" value="">
            </div>
            <div class="popup_form_field agree_field">
                <input id="terms_and_conditions" name="terms_and_conditions" required type="checkbox" value="1">
                <label for="terms_and_conditions">&nbsp;&nbsp;I agree with</label> <a href="#">Terms &amp; Conditions</a>
            </div>
            <div class="popup_form_field submit_field">
                <button class="submit_button" type="submit">Create Account</button>
            </div>
        </form>
        <div class="result message_block"></div>
    </div><!-- /.registration_wrap -->
</div><!-- /.user-popUp -->
