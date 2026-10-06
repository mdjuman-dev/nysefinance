<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/iziToast_custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">


    @stack('style')
</head>

<body>
    @yield('content')



    <script>
        window.my_pusher = {
            'app_key': "{{ base64_encode(config('app.PUSHER_APP_KEY')) }}",
            'app_cluster': "{{ base64_encode(config('app.PUSHER_APP_CLUSTER')) }}",
            'base_url': "{{ route('home') }}"
        }
        window.allow_decimal = "{{ gs('allow_decimal_after_number')}}";
    </script>

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/bootstrap-toggle.min.js') }}"></script>


    @include('partials.notify')
    @stack('script-lib')

    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>

    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>

    {{-- LOAD NIC EDIT --}}
    <script>
        "use strict";
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });
        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });

            $('.breadcrumb-nav-open').on('click', function() {
                $(this).toggleClass('active');
                $('.breadcrumb-nav').toggleClass('active');
            });

            $('.breadcrumb-nav-close').on('click', function() {
                $('.breadcrumb-nav').removeClass('active');
            });

            if ($('.topTap').length) {
                $('.breadcrumb-nav-open').removeClass('d-none');
            }
        })(jQuery);
    </script>

    <script>


        {{--const notificationSound = new Audio('{{asset('core/public/sound/livechat-129007.mp3')}}');--}}

        $(document).ready(function() {
            // Create an audio element for notification sound
            const notificationSound = new Audio('{{asset('core/public/sound/livechat-129007.mp3')}}');


            // Function to handle playing sound after user interaction
            function enableAudioPlayback() {
                // Try to play the audio muted, then unmute and play normally
                notificationSound.muted = true;
                notificationSound.play().then(() => {
                    notificationSound.pause();
                    notificationSound.muted = false; // Unmute for actual playback later
                }).catch(error => {
                    console.log('Muted audio playback failed:', error);
                });

                // Remove the event listener after enabling audio playback
                $(document).off('click', enableAudioPlayback);
            }

            // Attach the click event listener to enable audio playback
            $(document).on('click', enableAudioPlayback);

            function fetchNotifications() {
                const last_notification_id = $('.ex-notification').first().attr('data-time');

                $.ajax({
                    type: 'GET',
                    url: '{{route('admin.new.notification')}}',
                    data: {
                        time: last_notification_id
                    },
                    success: function(res) {
                        if(res.status == 'success') {
                            let html = '';

                            $.each(res.data, function(index, value){
                                html += `<a href="${value.route}" class="dropdown-menu__item ex-notification" data-time="${value.id}">
                                    <div class="navbar-notifi">
                                        <div class="navbar-notifi__right">
                                            <h6 class="notifi__title">${value.title}</h6>
                                            <span class="time"><i class="far fa-clock"></i> ${value.created_at}</span>
                                        </div>
                                    </div>
                                </a>`;
                            });

                            $('.appendNewNotification').prepend(html);

                            // Play the notification sound
                            notificationSound.play().catch(error => {
                                console.log('Audio playback error:', error);
                            });
                        }
                    }
                });
            }

            // Initial fetch of notifications
            // fetchNotifications();

            // Set an interval to fetch notifications every 30 seconds
            setInterval(fetchNotifications, 20000);
        });






    </script>

    @stack('script')


</body>

</html>
