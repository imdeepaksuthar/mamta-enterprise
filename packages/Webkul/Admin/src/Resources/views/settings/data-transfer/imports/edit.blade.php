<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.before', ['import' => $import]) !!}

    <x-admin::form
        :action="route('admin.settings.data_transfer.imports.update', $import->id)"
        method="PUT"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.before', ['import' => $import]) !!}

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.settings.data-transfer.imports.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.settings.data_transfer.imports.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.settings.data-transfer.imports.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.data-transfer.imports.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Body Content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Container -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.before', ['import' => $import]) !!}

                <!-- Setup Import Panel -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.data-transfer.imports.edit.general')
                    </p>

                    <!-- Type -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.data-transfer.imports.edit.type')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="type"
                            id="type"
                            :value="old('type') ?? $import->type"
                            ref="importType"
                            rules="required"
                            :label="trans('admin::app.settings.data-transfer.imports.edit.type')"
                        >
                            @foreach (config('importers') as $code => $importer)
                                <option 
                                    value="{{ $code }}"
                                    v-pre
                                >
                                    @lang($importer['title'])
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <!-- Source Sample Download Links -->
                        <div class="flex items-center mt-2.5">
                            <span>
                                @lang('admin::app.settings.data-transfer.imports.create.download-sample')
                            </span>

                            <x-admin::dropdown>
                                <x-slot:toggle>
                                    <span class="cursor-pointer text-2xl icon-arrow-down"></span>
                                </x-slot>

                                <x-slot:content>
                                    <div class="grid gap-2.5 max-md:my-0">
                                        @foreach ($supportedFormats as $format)
                                            <a
                                                :href="'{{ route('admin.settings.data_transfer.imports.download_sample', ['type' => ':type:', 'format' => ':format:']) }}'.replace(':type:', $refs['importType']?.value).replace(':format:', '{{ $format }}')"
                                                target="_blank"
                                                id="source-sample-link"
                                                class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                                            >
                                                {{ strtoupper($format) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </x-slot>
                            </x-admin::dropdown>
                        </div>

                        <x-admin::form.control-group.error control-name="type" />
                    </x-admin::form.control-group>

                    <!-- Images Directory Path -->
                    <!-- File -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.data-transfer.imports.edit.file')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="file"
                            name="file"
                            :label="trans('admin::app.settings.data-transfer.imports.edit.file')"
                        />

                        <!-- Display Existing File -->
                        @if(isset($import) && $import->file_path)
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.data-transfer.imports.edit.current-file'):
                                <a 
                                    href="{{ route('admin.settings.data_transfer.imports.download', $import->id) }}" 
                                    class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                                    target="_blank"
                                    v-pre
                                >
                                    {{ basename($import->file_path) }}
                                </a>
                            </div>
                        @endif

                        <x-admin::form.control-group.error control-name="file" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.after', ['import' => $import]) !!}

                <!-- Product Images Panel -->
                <v-product-images></v-product-images>
            </div>

            <!-- Right Container -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.before', ['import' => $import]) !!}

                <!-- Settings Panel -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.edit.settings')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content>
                        <!-- Action -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.action')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="action"
                                id="action"
                                :value="old('action') ?? $import->action"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.action')"
                            >
                                <option value="append">@lang('admin::app.settings.data-transfer.imports.edit.create-update')</option>
                                <option value="delete">@lang('admin::app.settings.data-transfer.imports.edit.delete')</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="action" />
                        </x-admin::form.control-group>

                        <!-- Validation Strategy -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.validation-strategy')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="validation_strategy"
                                id="validation_strategy"
                                :value="old('validation_strategy') ?? $import->validation_strategy"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.validation-strategy')"
                            >
                                <option value="stop-on-errors">@lang('admin::app.settings.data-transfer.imports.edit.stop-on-errors')</option>
                                <option value="skip-errors">@lang('admin::app.settings.data-transfer.imports.edit.skip-errors')</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="validation_strategy" />
                        </x-admin::form.control-group>

                        <!-- Allowed Errors -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.allowed-errors')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="allowed_errors"
                                :value="old('allowed_errors') ?? $import->allowed_errors"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.allowed-errors')"
                                :placeholder="trans('admin::app.settings.data-transfer.imports.edit.allowed-errors')"
                            />

                            <x-admin::form.control-group.error control-name="allowed_errors" />
                        </x-admin::form.control-group>

                        <!-- CSV Field Separator -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.field-separator')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="field_separator"
                                :value="old('field_separator') ?? $import->field_separator"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.field-separator')"
                                :placeholder="trans('admin::app.settings.data-transfer.imports.edit.field-separator')"
                            />

                            <x-admin::form.control-group.error control-name="field_separator" />
                        </x-admin::form.control-group>

                        <!-- Process In Queue -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.process-in-queue')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="process_in_queue"
                                :value="1"
                                :checked="(boolean) $import->process_in_queue"
                            />

                            <x-admin::form.control-group.error control-name="process_in_queue" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.after', ['import' => $import]) !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.after', ['import' => $import]) !!}
    </x-admin::form>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-images-template"
        >
            <div
                class="box-shadow rounded bg-white p-4 dark:bg-gray-900 mt-3.5"
                v-if="selectedType === 'products'"
            >
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.settings.data-transfer.imports.edit.product-images')
                </p>

                <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                    @lang('admin::app.settings.data-transfer.imports.edit.product-images-info')
                </p>

                <input type="hidden" name="image_source_type" :value="imageSourceType" />

                <div class="mt-4 flex flex-col gap-3">
                    <!-- Option 1: Image links in the file -->
                    <div
                        class="rounded border p-4 cursor-pointer transition-all"
                        :class="imageSourceType === 'link' 
                            ? 'border-blue-500 bg-[#f8faff] dark:bg-gray-800/40' 
                            : 'border-gray-200 hover:border-gray-300 dark:border-gray-800 dark:hover:border-gray-700'"
                        @click="imageSourceType = 'link'"
                    >
                        <div class="flex items-start gap-3">
                            <span
                                class="text-2xl cursor-pointer select-none leading-none"
                                :class="imageSourceType === 'link' ? 'icon-radio-selected text-blue-600' : 'icon-radio-normal text-gray-500'"
                            ></span>

                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.settings.data-transfer.imports.edit.image-links-in-file')
                                    </span>

                                    <span class="rounded-full bg-green-600 px-2 py-0.5 text-[11px] font-semibold text-white">
                                        @lang('admin::app.settings.data-transfer.imports.edit.recommended')
                                    </span>
                                </div>

                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                    @lang('admin::app.settings.data-transfer.imports.edit.image-links-info')
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Option 2: Upload a ZIP of images -->
                    <div
                        class="rounded border p-4 cursor-pointer transition-all"
                        :class="imageSourceType === 'zip' 
                            ? 'border-blue-500 bg-[#f8faff] dark:bg-gray-800/40' 
                            : 'border-gray-200 hover:border-gray-300 dark:border-gray-800 dark:hover:border-gray-700'"
                        @click="imageSourceType = 'zip'"
                    >
                        <div class="flex items-start gap-3">
                            <span
                                class="text-2xl cursor-pointer select-none leading-none"
                                :class="imageSourceType === 'zip' ? 'icon-radio-selected text-blue-600' : 'icon-radio-normal text-gray-500'"
                            ></span>

                            <div class="flex-1">
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.edit.upload-zip-images')
                                </span>

                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                    @lang('admin::app.settings.data-transfer.imports.edit.upload-zip-info')
                                </p>

                                <div v-if="imageSourceType === 'zip'" class="mt-3" @click.stop>
                                    <input
                                        type="file"
                                        name="images_zip"
                                        id="images_zip"
                                        accept=".zip"
                                        class="block w-full text-xs text-gray-900 border border-gray-200 dark:border-gray-700 rounded cursor-pointer bg-white dark:bg-gray-800 px-3 py-2"
                                        @change="onZipFileChange"
                                    />
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                        @lang('admin::app.settings.data-transfer.imports.edit.upload-zip-help')
                                    </p>

                                    <p v-if="zipFileName" class="mt-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                        ✓ Selected: @{{ zipFileName }} (@{{ zipFileSize }})
                                    </p>

                                    <p v-if="zipError" class="mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                                        @{{ zipError }}
                                    </p>

                                    @if ($errors->has('images_zip'))
                                        <p class="mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                                            {{ $errors->first('images_zip') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option 3: Images already on the server -->
                    <div
                        class="rounded border p-4 cursor-pointer transition-all"
                        :class="imageSourceType === 'server' 
                            ? 'border-blue-500 bg-[#f8faff] dark:bg-gray-800/40' 
                            : 'border-gray-200 hover:border-gray-300 dark:border-gray-800 dark:hover:border-gray-700'"
                        @click="imageSourceType = 'server'"
                    >
                        <div class="flex items-start gap-3">
                            <span
                                class="text-2xl cursor-pointer select-none leading-none"
                                :class="imageSourceType === 'server' ? 'icon-radio-selected text-blue-600' : 'icon-radio-normal text-gray-500'"
                            ></span>

                            <div class="flex-1">
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.edit.images-already-on-server')
                                </span>

                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                    @lang('admin::app.settings.data-transfer.imports.edit.images-on-server-info')
                                </p>

                                <div v-show="imageSourceType === 'server'" class="mt-3" @click.stop>
                                    <input
                                        type="text"
                                        name="images_directory_path"
                                        :value="imageSourceType === 'server' ? imagesDirectoryPath : ''"
                                        @input="imagesDirectoryPath = $event.target.value"
                                        placeholder="@lang('admin::app.settings.data-transfer.imports.edit.images-directory')"
                                        class="w-full rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-blue-500"
                                    />

                                    @if ($errors->has('images_directory_path'))
                                        <p class="mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                                            {{ $errors->first('images_directory_path') }}
                                        </p>
                                    @endif

                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                        @lang('admin::app.settings.data-transfer.imports.edit.file-info')
                                    </p>

                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                        @lang('admin::app.settings.data-transfer.imports.edit.file-info-example')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-product-images', {
                template: '#v-product-images-template',

                data() {
                    return {
                        selectedType: "{{ old('type') ?? $import->type ?? 'products' }}",
                        imageSourceType: "{{ old('image_source_type') ?? ($import->images_directory_path ? 'server' : 'server') }}",
                        imagesDirectoryPath: "{{ old('images_directory_path') ?? ($import->images_directory_path ?? '') }}",
                        zipFileName: '',
                        zipFileSize: '',
                        zipError: '',
                    };
                },

                mounted() {
                    const selectEl = document.getElementById('import-type');
                    if (selectEl) {
                        this.selectedType = selectEl.value;
                        selectEl.addEventListener('change', (e) => {
                            this.selectedType = e.target.value;
                        });
                    }

                    this.$emitter.on('import-type-changed', (type) => {
                        this.selectedType = type;
                    });
                },

                methods: {
                    onZipFileChange(event) {
                        const file = event.target.files[0];
                        this.zipError = '';

                        if (!file) {
                            this.zipFileName = '';
                            this.zipFileSize = '';
                            return;
                        }

                        if (!file.name.toLowerCase().endsWith('.zip')) {
                            this.zipError = 'Please choose a valid .zip archive.';
                            event.target.value = '';
                            this.zipFileName = '';
                            this.zipFileSize = '';
                            return;
                        }

                        this.zipFileName = file.name;
                        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                        this.zipFileSize = sizeMb + ' MB';
                    }
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                const form = document.querySelector('form[action*="settings/data-transfer/imports"]');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    const typeSelect = document.getElementById('type') || document.getElementById('import-type');
                    const zipInput = document.getElementById('images_zip');
                    const zipRadio = form.querySelector('input[name="image_source_type"]');

                    // If user selects zip in edit mode, ensure a zip is provided if they opted for zip
                    if (typeSelect && typeSelect.value === 'products' && zipRadio && zipRadio.value === 'zip') {
                        if (!zipInput || !zipInput.files || zipInput.files.length === 0) {
                            // On edit, if there's no new zip chosen, warn or proceed only if path exists
                            const existingPath = "{{ $import->images_directory_path }}";
                            if (!existingPath) {
                                e.preventDefault();
                                alert('Please select a ZIP file containing product images before submitting.');
                                zipInput?.focus();
                                return false;
                            }
                        }
                    }

                    const submitBtns = form.querySelectorAll('button[type="submit"]');
                    submitBtns.forEach(btn => {
                        btn.disabled = true;
                        btn.style.opacity = '0.7';
                        btn.style.pointerEvents = 'none';
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

                    let banner = document.getElementById('zip-upload-banner');
                    if (!banner) {
                        banner = document.createElement('div');
                        banner.id = 'zip-upload-banner';
                        banner.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-blue-600 text-white px-5 py-3.5 rounded-lg shadow-2xl';
                        banner.innerHTML = `
                            <svg class="h-5 w-5 animate-spin text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold">Uploading & Processing ZIP archive...</p>
                                <p class="text-xs text-blue-100">Depending on ZIP size, this may take 15–45 seconds. Please do not close or reload this page.</p>
                            </div>
                        `;
                        document.body.appendChild(banner);
                    }
                });
            });
        </script>
    @endPushOnce
</x-admin::layouts>
