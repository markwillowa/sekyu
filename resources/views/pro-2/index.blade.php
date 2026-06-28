@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-100 px-3 py-3 sm:px-4 sm:py-8">
        <div class="mx-auto max-w-3xl">
            <div class="mb-3 rounded-2xl bg-slate-900 p-4 text-center text-white shadow-lg sm:mb-5 sm:rounded-3xl sm:p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-400 sm:text-sm sm:tracking-[0.25em]">
                    SEKYU PRO
                </p>

                <h1 class="mt-1 text-2xl font-extrabold tracking-tight sm:mt-2 sm:text-3xl">
                    Guard Time-In
                </h1>

                <p class="mt-1 text-sm text-slate-300 sm:text-base">
                    Face verification before going on duty.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-xl sm:rounded-3xl sm:p-8">
                <div class="mx-auto max-w-xl">
                    <div class="relative mx-auto aspect-square w-[280px] max-w-full overflow-hidden rounded-full border-4 border-slate-200 bg-black shadow-inner sm:w-full sm:max-w-md sm:border-8">
                        <video
                            id="camera"
                            class="h-full w-full object-cover"
                            autoplay
                            muted
                            playsinline
                        ></video>

                        <canvas
                            id="overlay"
                            class="absolute inset-0 h-full w-full"
                        ></canvas>

                        <div class="pointer-events-none absolute inset-4 rounded-full border-2 border-dashed border-amber-400/80 sm:inset-6 sm:border-4"></div>
                    </div>

                    <p class="mt-3 text-center text-xs font-semibold text-slate-500 sm:mt-4 sm:text-sm">
                        Keep your face inside the circle.
                    </p>

                    <div class="mt-3 rounded-2xl bg-amber-50 p-4 text-center sm:mt-5 sm:rounded-3xl sm:p-5">
                        <p
                            id="step-label"
                            class="text-xs font-bold uppercase tracking-wider text-amber-700 sm:text-sm"
                        >
                            Step 1 of 5
                        </p>

                        <h2
                            id="instruction"
                            class="mt-2 text-2xl font-black leading-tight text-slate-900 sm:mt-3 sm:text-5xl"
                        >
                            LOADING CAMERA
                        </h2>

                        <p
                            id="step-description"
                            class="mt-2 text-sm font-semibold leading-6 text-slate-600 sm:mt-3 sm:text-lg sm:leading-8"
                        >
                            Please wait while the system prepares your camera.
                        </p>

                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200 sm:mt-5 sm:h-3">
                            <div
                                id="progress-bar"
                                class="h-full rounded-full bg-amber-500 transition-all duration-300"
                                style="width: 0%;"
                            ></div>
                        </div>

                        <p
                            id="progress-text"
                            class="mt-2 text-xs font-bold text-slate-500 sm:text-sm"
                        >
                            0%
                        </p>
                    </div>

                    <div
                        id="success"
                        class="mt-4 hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center sm:mt-5 sm:rounded-3xl sm:p-6"
                    >
                        <h3 class="text-2xl font-black text-emerald-700 sm:text-3xl">
                            TIME-IN SUCCESSFUL
                        </h3>

                        <p id="success-message" class="mt-2 text-sm font-semibold text-emerald-700 sm:mt-3 sm:text-base">
                            Guard attendance has been verified.
                        </p>

                        <p class="mt-4 text-xs font-semibold text-emerald-700 sm:mt-5 sm:text-sm">
                            Returning to login in
                        </p>

                        <div
                            id="countdown"
                            class="mt-2 text-6xl font-black tracking-tight text-emerald-700 sm:mt-3 sm:text-8xl"
                        >
                            5
                        </div>
                    </div>

                    <form
                        id="auto-logout-form"
                        method="POST"
                        action="{{ route('pro-2.logout') }}"
                        class="hidden"
                    >
                        @csrf
                    </form>

                    <form method="POST" action="{{ route('pro-2.logout') }}" class="mt-4 sm:mt-6">
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-slate-300 px-5 py-3 text-base font-bold text-slate-700 transition hover:bg-slate-50 sm:py-4 sm:text-lg"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

    <script>
        const video = document.getElementById('camera');
        const overlay = document.getElementById('overlay');
        const instruction = document.getElementById('instruction');
        const stepLabel = document.getElementById('step-label');
        const stepDescription = document.getElementById('step-description');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const success = document.getElementById('success');
        const countdown = document.getElementById('countdown');

        let currentStep = 'center';

        function setStep(label, title, description, progress = null) {
            stepLabel.textContent = label;
            instruction.textContent = title;
            stepDescription.textContent = description;

            if (progress !== null) {
                progressBar.style.width = `${progress}%`;
                progressText.textContent = `${progress}%`;
            }
        }

        async function startCamera() {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                },
                audio: false,
            });

            video.srcObject = stream;
        }

        async function loadModels() {
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68TinyNet.loadFromUri('/models');
        }

        function getNoseDirection(landmarks) {
            const nose = landmarks.getNose();
            const jaw = landmarks.getJawOutline();

            const noseTip = nose[3];
            const leftJaw = jaw[0];
            const rightJaw = jaw[16];

            const faceWidth = rightJaw.x - leftJaw.x;
            const noseRatio = (noseTip.x - leftJaw.x) / faceWidth;

            if (noseRatio < 0.43) {
                return 'left';
            }

            if (noseRatio > 0.57) {
                return 'right';
            }

            return 'center';
        }

        function getLocation() {
            return new Promise((resolve) => {
                if (! navigator.geolocation) {
                    resolve(null);
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => resolve(position),
                    () => resolve(null),
                    {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 0,
                    },
                );
            });
        }

        async function completeAttendance() {
            setStep(
                'Step 4 of 5',
                'GETTING LOCATION',
                'Please wait while we verify your device location.',
                80
            );

            const position = await getLocation();

            setStep(
                'Step 5 of 5',
                'RECORDING TIME-IN',
                'Please wait while we record your attendance.',
                100
            );

            const now = new Date();

            success.classList.remove('hidden');

            setStep(
                'Complete',
                'VERIFICATION COMPLETE',
                position
                    ? `Location confirmed. Time recorded at ${now.toLocaleString('en-PH', {
                        dateStyle: 'medium',
                        timeStyle: 'medium',
                    })}.`
                    : `Location unavailable. Time recorded at ${now.toLocaleString('en-PH', {
                        dateStyle: 'medium',
                        timeStyle: 'medium',
                    })}.`,
                100
            );

            startLogoutCountdown();
        }

        function startLogoutCountdown() {
            let seconds = 5;

            countdown.textContent = seconds;

            const timer = setInterval(() => {
                seconds--;

                countdown.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(timer);

                    document
                        .getElementById('auto-logout-form')
                        .submit();
                }
            }, 1000);
        }

        async function detectLoop() {
            const displaySize = {
                width: video.clientWidth,
                height: video.clientHeight,
            };

            faceapi.matchDimensions(overlay, displaySize);

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks(true);

            const context = overlay.getContext('2d');

            context.clearRect(0, 0, overlay.width, overlay.height);

            if (! detection) {
                setStep(
                    'Step 1 of 5',
                    'MOVE CLOSER',
                    'Place your face inside the circle.',
                    0
                );

                requestAnimationFrame(detectLoop);
                return;
            }

            const resized = faceapi.resizeResults(detection, displaySize);
            const direction = getNoseDirection(resized.landmarks);

            if (currentStep === 'center') {
                setStep(
                    'Step 1 of 5',
                    'LOOK STRAIGHT',
                    'Look directly at the camera.',
                    20
                );

                if (direction === 'center') {
                    currentStep = 'left';
                }
            } else if (currentStep === 'left') {
                setStep(
                    'Step 2 of 5',
                    'TURN LEFT',
                    'Slowly turn your head to the left.',
                    40
                );

                if (direction === 'left') {
                    currentStep = 'right';
                }
            } else if (currentStep === 'right') {
                setStep(
                    'Step 3 of 5',
                    'TURN RIGHT',
                    'Slowly turn your head to the right.',
                    60
                );

                if (direction === 'right') {
                    currentStep = 'done';

                    await completeAttendance();

                    return;
                }
            }

            requestAnimationFrame(detectLoop);
        }

        async function boot() {
            try {
                setStep(
                    'Preparing',
                    'LOADING FACE CHECK',
                    'Please wait while the system prepares verification.',
                    0
                );

                await loadModels();

                setStep(
                    'Preparing',
                    'STARTING CAMERA',
                    'Please allow camera permission when asked.',
                    0
                );

                await startCamera();

                video.addEventListener('playing', () => {
                    setStep(
                        'Step 1 of 5',
                        'LOOK STRAIGHT',
                        'Look directly at the camera.',
                        20
                    );

                    detectLoop();
                });
            } catch (error) {
                console.error(error);

                setStep(
                    'Camera Error',
                    'CAMERA FAILED',
                    'Please allow camera permission and refresh the page.',
                    0
                );
            }
        }

        boot();
    </script>
@endsection
