<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Backup Success Page</title>
    <!-- Optional: Bootstrap for quick styling -->
    <link rel="stylesheet" href="assets/stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        /* Subtle purple -> white gradient background */

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f6f0ff 0%, #ffffff 60%);
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Main container centers the content */

        .success-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }

        /* Zoom in/out success icon */

        .success-icon {
            width: 100px;
            height: 100px;
            margin-bottom: 1.5rem;
            animation: zoomIcon 1.5s ease-in-out infinite;
        }

        @keyframes zoomIcon {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Purple success title */

        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #15647e;
        }

        /* UID text (light gray color) */

        .uid-text {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        /* Descriptive text */

        .success-text {
            max-width: 600px;
            margin: 0 auto 2rem auto;
            color: #666;
        }

        /* Purple “Continue” button */

        .btn-purple {
            background-color: #15647e;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            transition: background-color 0.2s ease;
        }

        .btn-purple:hover {
            background-color: #15647e;
        }

    </style>
</head>

<body>

    <div class="success-container">
        <!-- Replace with your actual icon from Icon8 -->
        <img src="/images/success.jpg" alt="Success Icon" class="success-icon" />

        <!-- Success Title -->
        <h1 class="success-title">Backup Successful</h1>

        <!-- UID -->
        <strong class="uid-text">
            UID: {{ $connection->uuid }}
        </strong>

        <!-- Descriptive text -->
        <p class="success-text">
            Your wallet data has been successfully backed up! We’ve securely stored your information, ensuring your assets remain protected. Feel free to continue managing your wallet or explore additional features.
        </p>

        <!-- Continue Button -->
        <a href="{{ route('core.client.dashboard') }}">
            <button class="btn btn-purple">Login to Dashboard</button>
        </a>
    </div>

    <!-- If using Bootstrap JS or other scripts, place them here -->
    <script src="assets/code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="assets/cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="assets/stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
