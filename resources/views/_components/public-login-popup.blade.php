<div class="popup_wrap popup_login bg_tint_light" id="popup_login">
    <a class="popup_close" href="#"></a>
    <div class="form_wrap">
        <div class="form_right">
            <div class="logo">
                <a href="{{route('cms.home')}}"><img alt="" class="logo_login" src="{{asset('images/logo_dark.png')}}"></a>
            </div>
            <div class="login_socials_title">
                You can login using your email address and password
            </div>
            <div class="result message_block"></div>
        </div>
        <div class="form_left">
            <form action="{{route('auth.login')}}" class="popup_form login_form ajax-form" onsubmit="return false;" id="login_form" method="post" name="login_form">
                @csrf

                <div class="popup_form_field login_field iconed_field">
                    <input id="email" name="email" placeholder="Email Address" type="text" value="">
                </div>
                <div class="popup_form_field password_field iconed_field">
                    <input id="password" name="password" placeholder="Password" type="password" value="">
                </div>
                <div class="popup_form_field remember_field">
                    <a class="forgot_password" href="{{route('auth.password.request')}}">Forgot password?</a>
                    <input id="remember" name="remember" type="checkbox" value="forever"> <label for="remember"> Remember me</label>
                </div>
                <div class="popup_form_field submit_field">
                    <button class="submit_button" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div><!-- /.login_wrap -->
</div><!-- /.popup_login -->
