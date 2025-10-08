@extends('user.layout.master')

@section('content')
    <section class="gift-container p-3">
        <div class="d-flex justify-items-center align-items-center flex-column mb-5 pb-5">
            <iconify-icon
                icon="ph:gift-thin"
                width="6rem"
                height="6rem"
            ></iconify-icon>
            <h3 class="text-center">
                {!! $contentData->title ?? 'Select Your Gift Box' !!}
            </h3>
        </div>

        <!-- Loading Spinner -->
        <div id="loading" class="text-center" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only"></span>
            </div>
            <p class="mt-2"></p>
        </div>

        <!-- Gift Items -->
        <div class="gift-items" id="giftItems">
            <!-- Dynamic gift boxes will be loaded here -->
        </div>

        <!-- Success Message -->
        <div id="successMessage" class="text-center mt-4" style="display: none;">
            <div class="alert alert-success">
                <h5 id="selectedGiftText"></h5>
                <p>Redirecting</p>
                <div class="countdown-timer">
                    <span id="countdown">5</span>
                </div>
            </div>
        </div>

        <div class="mt-2 d-flex align-items-center justify-content-center" id="closeButton">
            <iconify-icon
                icon="zondicons:close-solid"
                width="2em"
                height="2em"
                style="color: white; background-color: black; border-radius: 50%; cursor: pointer;"
            ></iconify-icon>
        </div>
    </section>

    <style>
        .gift-items {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .gift-box {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
            position: relative;
            margin-top: 20px;
        }

        .gift-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #007bff;
        }

        .gift-box.active-gift {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-color: #0056b3;
            animation: pulse 2s infinite;
        }

        .gift-box.selected {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border-color: #28a745;
        }

        .gift-box.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .gift-body img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .countdown-timer {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }

        .loading-box {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            min-width: 200px;
        }
    </style>

@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
            loadGiftBoxes();

            function loadGiftBoxes() {
                $('#loading').show();
                $('#giftItems').empty();

                $.ajax({
                    url: "{{ route('get-gift-box-data') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#loading').hide();

                        if (response.success) {
                            // displayGiftBoxes(response.gift_boxes);
                            loadDisplayGiftBoxes(response.gift_boxes);
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        showError('something went wrong!!!');
                    }
                });
            }

            function loadDisplayGiftBoxes(giftBoxes) {
                let html = '';

                if (giftBoxes.length === 0) {
                    html = '<div class="text-center"><p>No Box Found.</p></div>';
                } else {
                    giftBoxes.forEach(function(box, index) {

                        html += `
                    <div class="gift-box " data-box-id="${box.id}" data-index="${index}">
                        <h5></h5>
                        <div class="gift-body">
                            <img src="{{ asset('assets/images/partials/gift-box.png') }}" alt="gift box" />
                        </div>
                    </div>
                `;
                    });
                }

                $('#giftItems').html(html);

                // Gift box click event
                $('.gift-box').on('click', function() {
                    if ($(this).hasClass('disabled')) {
                        return;
                    }

                    let boxId = $(this).data('box-id');
                    let boxIndex = $(this).data('index');

                    selectGiftBox(boxId, boxIndex, $(this));
                });
            }

            function loadGiftBoxesAfterClick() {
                $('#loading').show();
                $('#giftItems').empty();

                $.ajax({
                    url: "{{ route('get-gift-box-data') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#loading').hide();

                        if (response.success) {
                            displayGiftBoxes(response.gift_boxes);
                            // loadDisplayGiftBoxes(response.gift_boxes);
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        showError('something went wrong!!!');
                    }
                });
            }

            function displayGiftBoxes(giftBoxes, success_display_text, fail_display_text_1, fail_display_text_2) {
                let html = '';
                const failTexts = [fail_display_text_1, fail_display_text_2];
                let failIdx = 0; // active নয় এমন বক্সগুলোর জন্য ক্রমানুসারে fail টেক্সট নেবে

                if (!giftBoxes || giftBoxes.length === 0) {
                    html = '<div class="text-center"><p>No Box Found.</p></div>';
                } else {
                    giftBoxes.forEach(function(box) {
                        const activeClass = box.is_active ? 'active-gift' : '';
                        let displayText;

                        if (box.is_active) {
                            displayText = success_display_text;
                        } else {
                            // যে বক্স active নয় তাদের জন্য fail টেক্সটগুলা ক্রমানুসারে বসাও
                            displayText = (failTexts[failIdx] !== undefined) ? failTexts[failIdx] : 'Better luck next time!';
                            failIdx++;
                        }

                        html += `
                <div class="gift-box ${activeClass}" data-box-id="${box.id}">
                    <p>${displayText}</p>
                    <div class="gift-body">
                        <img src="{{ asset('assets/images/partials/gift-box.png') }}" alt="gift box" />
                    </div>
                </div>
            `;
                    });
                }

                $('#giftItems').html(html);

                // click bind
                $('.gift-box').on('click', function() {
                    if ($(this).hasClass('disabled')) return;
                    const boxId = $(this).data('box-id');
                    selectGiftBox(boxId, null, $(this));
                });
            }

            function selectGiftBox(boxId, boxIndex, clickedElement) {
                // Disable all boxes
                $('.gift-box').addClass('disabled');

                $.ajax({
                    url: "{{ route('select-gift-box') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'gift_box_id': boxId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // New box list render from response
                            displayGiftBoxes(
                                response.gift_boxes,
                                response.success_display_text,
                                response.fail_display_text_1,
                                response.fail_display_text_2
                            );

                            // redirect after 5 sec delay
                            setTimeout(function () {
                                    showSuccessAndRedirect(response.success_display_text);
                            }, 2);
                        } else {
                            showError(response.message);
                            $('.gift-box').removeClass('disabled');
                        }
                    },
                    error: function(xhr, status, error) {
                        showError('something went wrong!!!');
                        $('.gift-box').removeClass('disabled');
                    }
                });
            }

            function showSuccessAndRedirect(giftText) {
                disableAllBoxes();
                $('#selectedGiftText').text(giftText);
                $('#successMessage').show();
                $('#closeButton').hide();

                // Countdown timer
                let countdown = 5;
                let timer = setInterval(function() {
                    countdown--;
                    $('#countdown').text(countdown);

                    if (countdown <= 0) {
                        clearInterval(timer);
                        window.location.href = "{{ route('user-setoff') }}";
                    }
                }, 1000);
            }

            function showError(message) {
                // alert(message);
                // toastr.error(message);
                toastr.error(message);
            }

            function disableAllBoxes() {
                $('.gift-box').css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
            }
            // ✅ 1. Close button click করলে শেষের gift box select হবে
            $(document).on('click', '#closeButton', function() {
                const lastBox = $('.gift-box').last();
                if (lastBox.length > 0 && !lastBox.hasClass('disabled')) {
                    const boxId = lastBox.data('box-id');
                    selectGiftBox(boxId, null, lastBox);
                }
            });
            // ✅ 2. Gift box এর বাইরের অংশে (background বা অন্য জায়গায়) click করলে শেষের gift box select হবে
            $(document).on('click', function(e) {
                // gift-box বা closeButton এ ক্লিক হলে return করবে (মানে কিছু করবে না)
                if ($(e.target).closest('.gift-box').length > 0 || $(e.target).closest('#closeButton').length > 0) {
                    return;
                }

                // অন্য যেকোনো জায়গায় ক্লিক করলে শেষের gift box select হবে
                const lastBox = $('.gift-box').last();
                if (lastBox.length > 0 && !lastBox.hasClass('disabled')) {
                    const boxId = lastBox.data('box-id');
                    selectGiftBox(boxId, null, lastBox);
                }
            });
        });
    </script>
@endpush
