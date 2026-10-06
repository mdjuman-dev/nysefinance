@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="@guest py-120 mt-5 mt-md-0 @endguest">
        <div class="@guest container @endguest">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="d-flex flex-between flex-wrap align-items-center">
                        <h5 class="title mb-0">
                            @php echo $myTicket->statusBadge; @endphp
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h5>
                        <div>
                            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                <button type="button" class="btn btn--danger close-button btn--sm confirmationBtn outline"
                                    type="button" data-question="@lang('Are you sure to close this ticket?')"
                                    data-action="{{ route('ticket.close', $myTicket->id) }}">
                                    <i class="las la-times-circle"></i> @lang('Close Ticket')
                                </button>
                            @endif
                            @auth
                                <a href="{{ route('ticket.index') }}" class="btn btn--base btn--sm outline ms-1">
                                    <i class="las la-list"></i> @lang('My Tickets')
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mx-auto">














                    <div class="container py-5 px-4">


                        <div class="row rounded-lg overflow-hidden shadow">
                            <!-- Users box-->

                            <!-- Chat Box-->
                            <div class="col-12 px-0">
                                <div class="px-4 py-5 chat-box bg-white">

                                    @foreach ($messages as $message)
                                        @if ($message->admin_id == 0)
                                            <!-- Reciever Message-->
                                            <div class="media w-50 ml-auto mb-3 each-chat" data-id="{{ $message->id }}"
                                                style="margin-left: auto">
                                                <div class="media-body">
                                                    <div class="bg-primary rounded py-2 px-3 mb-2">
                                                        <p class="text-small mb-0 text-white">
                                                            {{ $message->message }}

                                                            @if ($message->attachments->count() > 0)
                                                                <span class="flex flex-wrap gap-2 ml-2">
                                                                    @foreach ($message->attachments as $k => $image)
                                                                        <a target="_blank"
                                                                            href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                                            class="text-xs text-link-red text-gray-600 hover:text-gray-900 flex items-center gap-1">
                                                                            <i class="fa fa-paperclip"></i>
                                                                            @lang('Attachment') {{ ++$k }}
                                                                        </a>
                                                                    @endforeach
                                                                </span>
                                                            @endif

                                                        </p>
                                                    </div>
                                                    <p class="small text-muted">{{ $message->ticket->name }} |
                                                        {{ $message->created_at->format('H:i') }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Sender Message-->
                                            <div class="media w-50 mb-3 each-chat" data-id="{{ $message->id }}">
                                                <img src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg"
                                                    alt="user" width="30" class="rounded-circle">
                                                <div class="media-body ml-3">
                                                    <div class="bg-light rounded py-2 px-3 mb-2">
                                                        <p class="text-small mb-0 text-muted">{{ $message->message }}

                                                            @if ($message->attachments->count() > 0)
                                                                <span class="flex flex-wrap gap-2 ml-2">
                                                                    @foreach ($message->attachments as $k => $image)
                                                                        <a target="_blank"
                                                                            href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                                            class="text-xs text-link-red text-blue-100 hover:text-white flex items-center gap-1">
                                                                            <i class="fa fa-paperclip"></i>
                                                                            @lang('Attachment') {{ ++$k }}
                                                                        </a>
                                                                    @endforeach
                                                                </span>
                                                            @endif
                                                        </p>
                                                    </div>

                                                    <p class="small text-muted"> {{ $message->admin->name }} |
                                                        {{ $message->created_at->format('H:i') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>


                            <!-- Typing area -->
                            <form action="{{ route('ticket.reply', $myTicket->id) }}" method="POST"
                                enctype="multipart/form-data" class="bg-light" id="chatForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group" style="background: #efefef;padding: 8px;">
                                            <input type="text" placeholder="Type a message"
                                                aria-describedby="button-addon2" name="message"
                                                class="form-control rounded-0 border-0 py-2 bg-light">
                                            <i class="fa fa-link trigger-file-at"
                                                style="position: relative;left: -25px; color: red; top: 12px; cursor: pointer;"></i>
                                            <input type="file" style="display: none" class="hd_file"
                                                name="attachments[]">
                                            <div class="input-group-append">
                                                <button id="button_addon" type="button" class="btn btn-primary"> <i
                                                        class="fa fa-paper-plane"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>










                <div class="flex flex-col h-screen max-w-4xl mx-auto bg-gray-50 d-none">
                    <!-- Chat Messages Container -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        @foreach ($messages as $message)
                            @if ($message->admin_id == 0)
                                <!-- User Message - Right Side -->
                                <div class="flex justify-end mb-4">
                                    <div class="flex flex-col items-end">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="text-sm text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                                            <span class="font-medium text-sm">{{ $message->ticket->name }}</span>
                                        </div>
                                        <div class="bg-blue-500 text-white rounded-lg rounded-tr-none p-3 max-w-[80%]">
                                            <p class="text-sm">{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                            class="text-xs text-blue-100 hover:text-white flex items-center gap-1">
                                                            <i class="fa fa-paperclip"></i>
                                                            @lang('Attachment') {{ ++$k }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Staff Message - Left Side -->
                                <div class="flex justify-start mb-4">
                                    <div class="flex flex-col items-start">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-medium text-sm">{{ $message->admin->name }}</span>
                                            <span
                                                class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded">@lang('Staff')</span>
                                            <span
                                                class="text-sm text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="bg-white shadow-md rounded-lg rounded-tl-none p-3 max-w-[80%]">
                                            <p class="text-sm text-gray-700">{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                            class="text-xs text-gray-600 hover:text-gray-900 flex items-center gap-1">
                                                            <i class="fa fa-paperclip"></i>
                                                            @lang('Attachment') {{ ++$k }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Message Input Form -->
                    <div class="border-t bg-white p-4">
                        <form action="{{ route('ticket.reply', $myTicket->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <textarea name="message"
                                            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none h-24"
                                            placeholder="@lang('Type your message...')" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="addAttachment flex items-center gap-2 text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100">
                                        <i class="fa fa-paperclip"></i>
                                        <span>@lang('Add Attachment')</span>
                                    </button>

                                    <button type="submit"
                                        class="flex items-center gap-2 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                        <i class="fa fa-paper-plane"></i>
                                        <span>@lang('Send')</span>
                                    </button>
                                </div>

                                <div class="text-xs text-gray-500">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                    .@lang('pdf'), .@lang('doc'), .@lang('docx').
                                    <small class="text--danger">
                                        @lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}
                                    </small>
                                </div>

                                <div class="row fileUploadsContainer"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <x-confirmation-modal isCustom="true" />
        @endsection

        @push('style')
            <style>
                .input-group-text:focus {
                    box-shadow: none !important;
                }

                .text-link-red:hover {
                    color: red !important;
                }

                .text-link-red {
                    color: red !important;
                }

                ::-webkit-scrollbar {
                    width: 5px;
                }

                ::-webkit-scrollbar-track {
                    width: 5px;
                    background: #f5f5f5;
                }

                ::-webkit-scrollbar-thumb {
                    width: 1em;
                    background-color: #ddd;
                    outline: 1px solid slategrey;
                    border-radius: 1rem;
                }

                .text-small {
                    font-size: 0.9rem;
                }

                .messages-box,
                .chat-box {
                    height: 510px;
                    overflow-y: scroll;
                }

                .rounded-lg {
                    border-radius: 0.5rem;
                }

                input::placeholder {
                    font-size: 0.9rem;
                    color: #999;
                }

                p.small {
                    font-size: 11px !important;
                }
            </style>
        @endpush
        @push('script')
            <script>
                $(document).on('click', '.trigger-file-at', function(e) {

                    $('.hd_file').trigger('click');

                });

                $(document).on('click', '#button_addon', function(e) {
                    e.preventDefault();

                    $('#chatForm').submit();

                });


                $(document).ready(function() {



                    $('#chatForm').on('submit', function(e) {
                        e.preventDefault(); // Prevent form submission

                        let formData = new FormData(this);
                        let chatBox = $('.chat-box'); // Chatbox container
                        $('#button-addon2').attr('disabled', 'disabled');

                        $.ajax({
                            url: $(this).attr('action'),
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.status == 'success') {
                                    let message = response.data.message;
                                    let attachments = response.data.attachments;

                                    // Construct new message HTML
                                    // let newMessage = `
                        //         <div class="media w-50 ml-auto mb-3" style="margin-left: auto">
                        //             <div class="media-body">
                        //                 <div class="bg-primary rounded py-2 px-3 mb-2">
                        //                     <p class="text-small mb-0 text-white">${message}</p>
                        //                     ${attachments && attachments.length > 0 ? '<div class="flex flex-wrap gap-2 ml-2">' : ''}
                        //                         ${attachments.map((file, index) => `
                                    //                                                     <a target="_blank" href="${file.url}" class="text-xs text-link-red text-gray-600 hover:text-gray-900 flex items-center gap-1">
                                    //                                                         <i class="fa fa-paperclip"></i> Attachment ${index + 1}
                                    //                                                     </a>`).join('')}
                        //                     ${attachments.length > 0 ? '</div>' : ''}
                        //                 </div>
                        //                 <p class="small text-muted">You | ${new Date().toLocaleTimeString()}</p>
                        //             </div>
                        //         </div>
                        //     `;

                                    fetchNewMessages();

                                    // chatBox.append(newMessage); // Append message to chatbox
                                    chatBox.scrollTop(chatBox.prop("scrollHeight")); // Scroll to bottom
                                    $('input[name="message"]').val(' '); // Clear input
                                    $('input[name="attachments[]"]').val(' '); // Clear file input

                                    $('#button-addon2').removeAttr('disabled');

                                } else {
                                    $('#button-addon2').removeAttr('disabled');


                                    alert(response.error);
                                }
                            },
                            error: function(xhr) {
                                $('#button-addon2').removeAttr('disabled');


                                alert("Error sending message. Please try again.");
                            }
                        });
                    });




            function fetchNewMessages() {
                const last_id = $('.each-chat').last().attr('data-id') || 0; // Get the last message ID

                $.ajax({
                    type: 'GET',
                    url: "{{ route('ticket.new.chat', $myTicket->id) }}",
                    data: { last_id: last_id },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.chat-box').append(res.data);
                            $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
                        }
                    }
                });
            }

            // Run the function every 15 seconds
            setInterval(fetchNewMessages, 15000);
            });







                            function formatTime(timestamp) {
                                let date = new Date(timestamp);
                                return date.toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });
                            }


                            (function($) {
                                "use strict";
                                var fileAdded = 0;
                                $('.addAttachment').on('click', function() {
                                    fileAdded++;

                                    if (fileAdded == 5) {
                                        $(this).attr('disabled', true)
                                    }
                                    $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger text-white"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `);
                                });

                                $(document).on('click', '.removeFile', function() {
                                    $('.addAttachment').removeAttr('disabled', true)
                                    fileAdded--;
                                    $(this).closest('.removeFileInput').remove();
                                });
                            })(jQuery);

                            ///customize confirmation modal
                            window.addEventListener('DOMContentLoaded', function(e) {
                                let confirmationModal = $('#confirmationModal');
                                if (confirmationModal.length > 0) {
                                    $(confirmationModal).find('.btn--primary').addClass('btn--base btn--sm').removeClass(
                                        'btn--primary');
                                    $(confirmationModal).find('.btn--dark').addClass('btn--sm');
                                }
                            });
            </script>
        @endpush
