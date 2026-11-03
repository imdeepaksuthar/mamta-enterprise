<!-- Fullscreen Process Monitoring Modal -->
<div
    id="import-process-modal"
    class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="import-process-title"
>
    <div class="relative w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
        <!-- Header -->
        <div class="flex items-start gap-4">
            <div id="modal-icon-container" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400">
                <!-- Uploading Spinner -->
                <svg id="modal-icon-uploading" class="h-6 w-6 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <!-- Success Checkmark -->
                <svg id="modal-icon-success" class="h-6 w-6 hidden text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>

                <!-- Error Alert -->
                <svg id="modal-icon-error" class="h-6 w-6 hidden text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>

            <div class="flex-1">
                <h3 id="import-process-title" class="text-lg font-bold text-gray-900 dark:text-white">
                    Processing Import Submission
                </h3>
                <p id="import-process-subtitle" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Uploading files and preparing the catalog import pipeline...
                </p>
            </div>
        </div>

        <!-- Progress Overview -->
        <div id="modal-progress-section" class="mt-6">
            <div class="flex items-baseline justify-between mb-2">
                <span id="modal-percent-text" class="text-3xl font-extrabold text-blue-600 dark:text-blue-400 tracking-tight">
                    0%
                </span>
                <span id="modal-status-badge" class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">
                    <span id="modal-status-dot" class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                    <span id="modal-status-badge-text">Uploading Files</span>
                </span>
            </div>

            <!-- Progress Bar Outer -->
            <div class="h-3.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800 p-0.5 border border-gray-200/50 dark:border-gray-700/50">
                <div
                    id="modal-progress-bar"
                    class="h-full rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-150 ease-out"
                ></div>
            </div>

            <!-- Transfer Metrics Grid -->
            <div id="modal-metrics-grid" class="mt-4 grid grid-cols-3 gap-2 rounded-xl bg-gray-50 dark:bg-gray-800/60 p-3 text-center border border-gray-100 dark:border-gray-800">
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 dark:text-gray-500">Transferred</p>
                    <p id="metric-transferred" class="mt-1 text-xs font-semibold text-gray-800 dark:text-gray-200">0 MB / 0 MB</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 dark:text-gray-500">Speed</p>
                    <p id="metric-speed" class="mt-1 text-xs font-semibold text-gray-800 dark:text-gray-200">0 MB/s</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 dark:text-gray-500">Estimated Time</p>
                    <p id="metric-eta" class="mt-1 text-xs font-semibold text-gray-800 dark:text-gray-200">Calculating...</p>
                </div>
            </div>
        </div>

        <!-- Pipeline Steps Checklist -->
        <div class="mt-5 space-y-2.5 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 p-3.5">
            <!-- Step 1: Upload -->
            <div id="step-row-upload" class="flex items-center gap-3 text-xs text-gray-700 dark:text-gray-300">
                <span id="step-icon-upload" class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300">
                    <svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </span>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 dark:text-gray-200">1. Upload Files & Image Archive</p>
                    <p id="step-desc-upload" class="text-[11px] text-gray-500 dark:text-gray-400">Transferring CSV and images to server storage...</p>
                </div>
            </div>

            <!-- Step 2: Extraction -->
            <div id="step-row-extract" class="flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
                <span id="step-icon-extract" class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                </span>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 dark:text-gray-200">2. Server ZIP Extraction & Processing</p>
                    <p id="step-desc-extract" class="text-[11px] text-gray-500 dark:text-gray-400">Waiting for upload completion...</p>
                </div>
            </div>

            <!-- Step 3: Initialization -->
            <div id="step-row-finalize" class="flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
                <span id="step-icon-finalize" class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                </span>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 dark:text-gray-200">3. Import Pipeline Initialization</p>
                    <p id="step-desc-finalize" class="text-[11px] text-gray-500 dark:text-gray-400">Preparing validation screen...</p>
                </div>
            </div>
        </div>

        <!-- Warning Notice -->
        <div id="modal-warning-box" class="mt-4 flex items-start gap-2.5 rounded-xl border border-amber-200 dark:border-amber-800/60 bg-amber-50 dark:bg-amber-950/30 p-3 text-xs text-amber-800 dark:text-amber-300">
            <svg class="h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z" />
            </svg>
            <p>Please do not close, refresh, or leave this page while the transfer and archive processing are in progress.</p>
        </div>

        <!-- Error Container (Hidden by default) -->
        <div id="modal-error-box" class="mt-4 hidden rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 p-4">
            <div class="flex items-start gap-3">
                <svg class="h-5 w-5 shrink-0 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div class="flex-1">
                    <p class="text-xs font-bold text-red-800 dark:text-red-300">Process Halted</p>
                    <p id="modal-error-message" class="mt-1 text-xs text-red-700 dark:text-red-400 leading-relaxed"></p>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button
                    type="button"
                    id="modal-error-close-btn"
                    class="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 focus:outline-none transition-colors"
                >
                    Close & Fix Details
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        function setupImportProcessMonitor() {
            const form = document.querySelector('form[action*="settings/data-transfer/imports"]');
            if (!form) return;

            const modal = document.getElementById('import-process-modal');
            if (!modal) return;

            const iconUploading = document.getElementById('modal-icon-uploading');
            const iconSuccess = document.getElementById('modal-icon-success');
            const iconError = document.getElementById('modal-icon-error');
            const iconContainer = document.getElementById('modal-icon-container');

            const title = document.getElementById('import-process-title');
            const subtitle = document.getElementById('import-process-subtitle');
            const progressSection = document.getElementById('modal-progress-section');
            const percentText = document.getElementById('modal-percent-text');
            const progressBar = document.getElementById('modal-progress-bar');
            const statusBadgeText = document.getElementById('modal-status-badge-text');
            const statusBadge = document.getElementById('modal-status-badge');
            const statusDot = document.getElementById('modal-status-dot');

            const metricTransferred = document.getElementById('metric-transferred');
            const metricSpeed = document.getElementById('metric-speed');
            const metricEta = document.getElementById('metric-eta');

            const stepIconUpload = document.getElementById('step-icon-upload');
            const stepDescUpload = document.getElementById('step-desc-upload');
            const stepRowExtract = document.getElementById('step-row-extract');
            const stepIconExtract = document.getElementById('step-icon-extract');
            const stepDescExtract = document.getElementById('step-desc-extract');
            const stepRowFinalize = document.getElementById('step-row-finalize');
            const stepIconFinalize = document.getElementById('step-icon-finalize');
            const stepDescFinalize = document.getElementById('step-desc-finalize');

            const warningBox = document.getElementById('modal-warning-box');
            const errorBox = document.getElementById('modal-error-box');
            const errorMessage = document.getElementById('modal-error-message');
            const errorCloseBtn = document.getElementById('modal-error-close-btn');

            let serverTimer = null;
            let currentXhr = null;

            function formatBytes(bytes) {
                if (!bytes || bytes <= 0) return '0 MB';
                const mb = bytes / (1024 * 1024);
                if (mb >= 1000) {
                    return (mb / 1024).toFixed(2) + ' GB';
                }
                return mb.toFixed(2) + ' MB';
            }

            function formatSpeed(bytesPerSec) {
                if (!bytesPerSec || bytesPerSec <= 0) return '0 MB/s';
                const mb = bytesPerSec / (1024 * 1024);
                if (mb >= 1) return mb.toFixed(1) + ' MB/s';
                const kb = bytesPerSec / 1024;
                return kb.toFixed(0) + ' KB/s';
            }

            function formatTime(seconds) {
                if (!isFinite(seconds) || seconds <= 0) return 'Few seconds';
                const s = Math.round(seconds);
                if (s < 60) return s + 's remaining';
                const m = Math.floor(s / 60);
                const remS = s % 60;
                return m + 'm ' + remS + 's remaining';
            }

            function resetModalUI() {
                if (serverTimer) {
                    clearInterval(serverTimer);
                    serverTimer = null;
                }

                modal.classList.remove('hidden');
                progressSection.classList.remove('hidden');
                warningBox.classList.remove('hidden');
                errorBox.classList.add('hidden');

                iconUploading.classList.remove('hidden');
                iconSuccess.classList.add('hidden');
                iconError.classList.add('hidden');
                iconContainer.className = 'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400';

                title.textContent = 'Processing Import Submission';
                subtitle.textContent = 'Uploading files and preparing the catalog import pipeline...';

                percentText.textContent = '0%';
                percentText.className = 'text-3xl font-extrabold text-blue-600 dark:text-blue-400 tracking-tight';
                progressBar.style.width = '0%';
                progressBar.className = 'h-full rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-150 ease-out';

                statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300';
                statusDot.className = 'h-1.5 w-1.5 rounded-full bg-blue-600 animate-pulse';
                statusBadgeText.textContent = 'Uploading Files';

                metricTransferred.textContent = '0 MB / 0 MB';
                metricSpeed.textContent = '0 MB/s';
                metricEta.textContent = 'Calculating...';

                // Step 1
                stepIconUpload.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300';
                stepIconUpload.innerHTML = '<svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
                stepDescUpload.textContent = 'Transferring CSV and images to server storage...';

                // Step 2
                stepRowExtract.className = 'flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500';
                stepIconExtract.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400';
                stepIconExtract.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>';
                stepDescExtract.textContent = 'Waiting for upload completion...';

                // Step 3
                stepRowFinalize.className = 'flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500';
                stepIconFinalize.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400';
                stepIconFinalize.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>';
                stepDescFinalize.textContent = 'Preparing validation screen...';
            }

            function setExtractionPhase() {
                percentText.textContent = '100%';
                progressBar.style.width = '100%';

                // Step 1 complete
                stepIconUpload.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400';
                stepIconUpload.innerHTML = '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                stepDescUpload.textContent = 'Files uploaded successfully.';

                // Step 2 active
                stepRowExtract.className = 'flex items-center gap-3 text-xs text-gray-700 dark:text-gray-300';
                stepIconExtract.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300';
                stepIconExtract.innerHTML = '<svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
                stepDescExtract.textContent = 'Extracting ZIP archive and indexing images on server...';

                statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 text-xs font-semibold text-indigo-700 dark:text-indigo-300';
                statusDot.className = 'h-1.5 w-1.5 rounded-full bg-indigo-600 animate-ping';
                statusBadgeText.textContent = 'Server Extracting ZIP';

                subtitle.textContent = 'Upload complete! Server is extracting ZIP and preparing catalog import...';

                metricTransferred.textContent = '100% Uploaded';
                metricSpeed.textContent = 'Server Unpacking';

                let elapsed = 0;
                metricEta.textContent = 'Elapsed: 0s';
                if (serverTimer) clearInterval(serverTimer);
                serverTimer = setInterval(() => {
                    elapsed++;
                    metricEta.textContent = 'Elapsed: ' + elapsed + 's';
                    stepDescExtract.textContent = 'Extracting ZIP archive (' + elapsed + 's elapsed)...';
                }, 1000);
            }

            function setCompletionPhase(redirectUrl) {
                if (serverTimer) {
                    clearInterval(serverTimer);
                    serverTimer = null;
                }

                // Step 2 complete
                stepIconExtract.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400';
                stepIconExtract.innerHTML = '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                stepDescExtract.textContent = 'Archive extracted and verified.';

                // Step 3 complete
                stepRowFinalize.className = 'flex items-center gap-3 text-xs text-gray-700 dark:text-gray-300';
                stepIconFinalize.className = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400';
                stepIconFinalize.innerHTML = '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                stepDescFinalize.textContent = 'Import setup complete! Redirecting...';

                iconUploading.classList.add('hidden');
                iconSuccess.classList.remove('hidden');
                iconError.classList.add('hidden');
                iconContainer.className = 'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400';

                percentText.className = 'text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight';
                progressBar.className = 'h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-600';

                statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-300';
                statusDot.className = 'h-1.5 w-1.5 rounded-full bg-emerald-600';
                statusBadgeText.textContent = 'Complete! Redirecting';

                title.textContent = 'Import Ready';
                subtitle.textContent = 'Redirecting to import validation pipeline...';
                metricSpeed.textContent = 'Done';
                metricEta.textContent = '0s';

                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 700);
            }

            function setErrorPhase(msg) {
                if (serverTimer) {
                    clearInterval(serverTimer);
                    serverTimer = null;
                }

                progressSection.classList.add('hidden');
                warningBox.classList.add('hidden');
                errorBox.classList.remove('hidden');

                iconUploading.classList.add('hidden');
                iconSuccess.classList.add('hidden');
                iconError.classList.remove('hidden');
                iconContainer.className = 'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400';

                title.textContent = 'Submission Failed';
                subtitle.textContent = 'The import process could not be completed.';
                errorMessage.textContent = msg || 'An unexpected error occurred during submission.';

                // Re-enable submit buttons
                const submitBtns = form.querySelectorAll('button[type="submit"]');
                submitBtns.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'pointer-events-none');
                    btn.removeAttribute('style');
                    const originalText = btn.getAttribute('data-original-text');
                    if (originalText) {
                        btn.innerHTML = originalText;
                    }
                });
            }

            errorCloseBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const isEdit = form.querySelector('input[name="_method"]')?.value === 'PUT';
                const typeSelect = form.querySelector('[name="type"]') || document.getElementById('import-type');
                const fileInput = form.querySelector('input[name="file"]');
                const imageRadio = form.querySelector('input[name="image_source_type"]');
                const zipInput = document.getElementById('images_zip');
                const serverPathInput = form.querySelector('input[name="images_directory_path"]');

                // Client-side pre-flight validation
                if (!isEdit && (!fileInput || !fileInput.files || fileInput.files.length === 0)) {
                    alert('Please select a file to import (CSV, XLS, XLSX, XML).');
                    fileInput?.focus();
                    return false;
                }

                if (typeSelect && typeSelect.value === 'products' && imageRadio) {
                    const sourceType = imageRadio.value;
                    if (sourceType === 'zip') {
                        const hasZipFile = zipInput && zipInput.files && zipInput.files.length > 0;
                        if (!hasZipFile) {
                            if (!isEdit) {
                                alert('Please select a ZIP file containing product images before submitting.');
                                zipInput?.focus();
                                return false;
                            }
                        } else {
                            if (!zipInput.files[0].name.toLowerCase().endsWith('.zip')) {
                                alert('Please select a valid .zip archive file.');
                                zipInput.focus();
                                return false;
                            }
                        }
                    } else if (sourceType === 'server') {
                        if (!serverPathInput || !serverPathInput.value.trim()) {
                            alert('Please specify the images directory path on the server.');
                            serverPathInput?.focus();
                            return false;
                        }
                    }
                }

                // Cache original button text & disable submit buttons
                const submitBtns = form.querySelectorAll('button[type="submit"]');
                submitBtns.forEach(btn => {
                    if (!btn.getAttribute('data-original-text')) {
                        btn.setAttribute('data-original-text', btn.innerHTML);
                    }
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'pointer-events-none');
                    btn.innerHTML = `
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading & Processing...
                        </span>
                    `;
                });

                resetModalUI();

                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                currentXhr = xhr;

                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');

                let startTime = Date.now();
                let lastLoaded = 0;
                let lastTime = startTime;
                let speed = 0;

                xhr.upload.onprogress = function (event) {
                    if (event.lengthComputable) {
                        const now = Date.now();
                        const timeDiff = (now - lastTime) / 1000;
                        if (timeDiff >= 0.4) {
                            speed = (event.loaded - lastLoaded) / timeDiff;
                            lastLoaded = event.loaded;
                            lastTime = now;
                        }

                        const percent = Math.min(99, Math.round((event.loaded / event.total) * 100));
                        percentText.textContent = percent + '%';
                        progressBar.style.width = percent + '%';

                        metricTransferred.textContent = formatBytes(event.loaded) + ' / ' + formatBytes(event.total);
                        metricSpeed.textContent = formatSpeed(speed);

                        const remainingBytes = Math.max(0, event.total - event.loaded);
                        const remainingSeconds = speed > 0 ? (remainingBytes / speed) : 0;
                        metricEta.textContent = formatTime(remainingSeconds);

                        stepDescUpload.textContent = 'Transferred ' + formatBytes(event.loaded) + ' of ' + formatBytes(event.total) + ' (' + percent + '%)...';
                    }
                };

                xhr.upload.onload = function () {
                    setExtractionPhase();
                };

                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let res = {};
                        try {
                            res = JSON.parse(xhr.responseText);
                        } catch (err) {}

                        const redirectUrl = res.redirect_url || xhr.responseURL || window.location.href;
                        setCompletionPhase(redirectUrl);
                    } else if (xhr.status === 422) {
                        let errorMsg = 'Validation error occurred.';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.errors) {
                                const firstKey = Object.keys(res.errors)[0];
                                errorMsg = res.errors[firstKey][0] || res.message;
                            } else if (res.message) {
                                errorMsg = res.message;
                            }
                        } catch (err) {}
                        setErrorPhase(errorMsg);
                    } else if (xhr.status === 413) {
                        setErrorPhase('The uploaded file is too large for the web server (HTTP 413 Payload Too Large).');
                    } else {
                        let errorMsg = 'Server responded with error status ' + xhr.status + '.';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.message) errorMsg = res.message;
                        } catch (err) {}
                        setErrorPhase(errorMsg);
                    }
                };

                xhr.onerror = function () {
                    setErrorPhase('Network error occurred while uploading. Please check your network connection and server limits, then try again.');
                };

                xhr.onabort = function () {
                    setErrorPhase('Upload was aborted.');
                };

                xhr.send(formData);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupImportProcessMonitor);
        } else {
            setupImportProcessMonitor();
        }
    })();
</script>
