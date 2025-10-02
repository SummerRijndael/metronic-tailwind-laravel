<x-guest-layout>
@push('styles')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

<style>
body {
    background-color: #1A1A1A;
    margin: 0;
    height: 100vh;
    font-family: 'Inter', sans-serif;
}

:root {
    --x: 0;
    --y: 0;
}

.page {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.content-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.1rem;
    text-align: center;
    color: #00C776;
}

.error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin-right: 1.25rem;
}

.error-number {
    font-size: 6rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
    margin: 0;
}

.ghost-container {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ghost-svg {
    width: 100%;
    height: 100%;
}

.error-message {
    margin-top: 0.8rem;
    margin-bottom: 1rem;
    font-size: 1.4rem;
    font-weight: 600;
    color: #ffffff;
}

.button-primary {
    margin-top: 0.2rem;
    width: 100%;
    text-align: center;
    background-color: #00C776;
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: background-color 0.2s;
    font-weight: 600;
}

.button-primary:hover {
    background-color: #00a763;
}

#ghost-grid {
    --x: 0;
    --y: 0;
}

#ghost-grid svg .eye,
#ghost-grid svg .dot {
    transition: translate 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    translate: calc(var(--x) * 1px) calc(var(--y) * 1px);
}

.typed-text {
    white-space: nowrap;
    min-width: 1px;
}

.caret {
    display: inline-block;
    width: 2px;
    height: 1em;
    background-color: currentColor;
    margin-left: 2px;
    animation: blink 1s step-end infinite;
}

@keyframes blink {
    50% {
        opacity: 0;
    }
}
</style>
@endpush
<div class="flex flex-col items-center justify-left grow h-full">

 <div class="page">
    <div class="content-container">
        <div class="error-container">
            <h2 class="error-number">4</h2>
            
            <div id="ghost-grid" class="ghost-container">
                <svg viewBox="0 0 14 14" class="ghost-svg">
                    <defs>
                        <rect id="pixel-dot-rect" x="0.175" y="0.175" width="0.7" height="0.7" rx="0.2" />
                        <pattern id="pixel-dot-pattern" viewBox="0 0 1 1" width="1" height="1" patternUnits="userSpaceOnUse">
                            <use fill="#00C776" href="#pixel-dot-rect" />
                        </pattern>
                        <mask id="pixel-dot-mask">
                            <rect fill="white" width="14" height="14" />
                            <path transform="translate(0 0.5)" fill="none" stroke="black"
                                d="M 0 0 h5M 9 0h5 M 0 1h3 M 11 1h3 M 0 2h2 M 12 2h2M 0 3h1 M 13 3h1M 0 4h1 M 13 4h1 M 0 5h1 M 13 5h1 M 4 12h1 M 9 12h1 M 0 13h1 M 3 13h3 M8 13h3 M 13 13h1" />
                        </mask>
                    </defs>
                    <rect mask="url(#pixel-dot-mask)" fill="url(#pixel-dot-pattern)" width="14" height="14" />
                    <g class="eye">
                        <g transform="translate(2 3)">
                            <path transform="translate(0 0.5)" fill="none" stroke="white"
                                d="M 1 0 h2 M 0 1h4 M 0 2h4 M 0 3h4 M 1 4h2" />
                            <g fill="black" class="dot">
                                <use transform="translate(1 1)" href="#pixel-dot-rect" />
                                <use transform="translate(2 1)" href="#pixel-dot-rect" />
                                <use transform="translate(1 2)" href="#pixel-dot-rect" />
                                <use transform="translate(2 2)" href="#pixel-dot-rect" />
                            </g>
                        </g>
                    </g>
                    <g class="eye">
                        <g transform="translate(8 3)">
                            <path transform="translate(0 0.5)" fill="none" stroke="white"
                                d="M 1 0 h2 M 0 1h4 M 0 2h4 M 0 3h4 M 1 4h2" />
                            <g fill="black" class="dot">
                                <use transform="translate(1 1)" href="#pixel-dot-rect" />
                                <use transform="translate(2 1)" href="#pixel-dot-rect" />
                                <use transform="translate(1 2)" href="#pixel-dot-rect" />
                                <use transform="translate(2 2)" href="#pixel-dot-rect" />
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            
            <h2 class="error-number">3</h2>
        </div>
        
        <p class="error-message" id="typer">
            <span class="typed-text"></span>
            <span class="caret"></span>
        </p>

        <p class="text-lg text-white mb-8">
        Sorry, you are not allowed to access this page. 
        If you believe this is a mistake, please contact the system administrator.
       </p>

        
        <div class="flex gap-4">
            <!-- Go Back Button -->
            <a href="{{ url()->previous() ?: route('home') }}" 
              class="px-4 py-2 kt-btn rounded-full">
                Go Back
            </a>

            <!-- Go Home Button -->
            <a href="{{ route('dashboard') }}" 
              class="px-4 py-2 kt-btn kt-btn-secondary rounded-full">
                Go Home
            </a>
         </div>
    
    </div>
</div>

</div>



@push('scripts')
<script>

function updateEyeDirection(event) {
    const ghost = document.getElementById('ghost-grid');
    if (!ghost) return;

    const ghostRect = ghost.getBoundingClientRect();
    const centerX = ghostRect.left + ghostRect.width / 2;
    const centerY = ghostRect.top + ghostRect.height / 2;

    const dx = event.clientX - centerX;
    const dy = event.clientY - centerY;

    const maxOffset = 1;
    const normX = Math.max(-maxOffset, Math.min(maxOffset, dx / 25));
    const normY = Math.max(-maxOffset, Math.min(maxOffset, dy / 25));

    ghost.style.setProperty('--x', normX.toFixed(2));
    ghost.style.setProperty('--y', normY.toFixed(2));
}

function initTyper() {
    const typer = document.querySelector('#typer .typed-text');
    const text = "Access Denied";
    let displayedText = '';
    let currentIndex = 0;
    let isTyping = true;
    let intervalId = null;

    function type() {
        if (currentIndex < text.length) {
            displayedText = text.slice(0, currentIndex + 1);
            typer.textContent = displayedText;
            currentIndex++;
        } else {
            clearInterval(intervalId);
            setTimeout(() => {
                isTyping = false;
                startErasing();
            }, 2000);
        }
    }

    function erase() {
        if (displayedText.length > 0) {
            displayedText = displayedText.slice(0, -1);
            typer.textContent = displayedText;
        } else {
            clearInterval(intervalId);
            setTimeout(() => {
                isTyping = true;
                currentIndex = 0;
                startTyping();
            }, 70);
        }
    }

    function startTyping() {
        intervalId = setInterval(type, 70);
    }

    function startErasing() {
        intervalId = setInterval(erase, 50);
    }

    setTimeout(startTyping, 70); 
}

window.addEventListener('mousemove', updateEyeDirection);
initTyper();
</script>
@endpush

</x-guest-layout>       