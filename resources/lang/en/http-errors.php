<?php
declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Status Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during aborts.
    |
    */

    '400' => 'Bad Request! The server cannot process the request due to client error.<br/>
              (e.g., malformed request syntax, size too large, invalid request message 
              framing, or deceptive request routing).',

    '403' => "Unauthorized! It seems you have wondered off to the wrong place.<br/>Don't worry, we've got you.",

    '404' => "Page Not Found! Sorry, we could not find the page you are looking.<br/>Don't worry, we've got you.",

    '500' => "Oops! Something snapped. Don't worry, it's all under control.",

    '503' => "<p>".config('app.name')." is currently unavailable as we are performing some very important updates.</p>
              <p>We regret any inconvenience caused.</p>
              <p>Thank you and kindly exercise patience as we will be back soon.</p>",

];
