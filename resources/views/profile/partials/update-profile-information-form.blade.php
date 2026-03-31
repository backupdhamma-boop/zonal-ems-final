<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" x-data="{ photoName: null, photoPreview: null }">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="col-span-6 sm:col-span-4">
            <!-- Profile Photo File Input -->
            <input type="file" class="hidden"
                        x-ref="photo"
                        name="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <x-input-label for="photo" :value="__('Photo')" />

            <!-- Current Profile Photo -->
            <div class="mt-2" x-show="! photoPreview">
                @if($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-full h-20 w-20 object-cover">
                @else
                    <div class="rounded-full h-20 w-20 bg-gray-200 flex items-center justify-center text-gray-500 font-bold uppercase">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2" x-show="photoPreview" style="display: none;">
                <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <x-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Select A New Photo') }}
            </x-secondary-button>

            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div>
                <x-input-label for="name" :value="__('Username')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <hr class="border-gray-200">
        <h3 class="text-md font-bold text-gray-700 uppercase tracking-wider">Professional Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="full_name" :value="__('Full Name')" />
                <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $user->full_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
            </div>

            <div>
                <x-input-label for="nic_number" :value="__('NIC Number')" />
                <x-text-input id="nic_number" name="nic_number" type="text" class="mt-1 block w-full" :value="old('nic_number', $user->nic_number)" />
                <x-input-error class="mt-2" :messages="$errors->get('nic_number')" />
            </div>

            <div>
                <x-input-label for="designation" :value="__('Designation')" />
                <x-text-input id="designation" name="designation" type="text" class="mt-1 block w-full" :value="old('designation', $user->designation)" />
                <x-input-error class="mt-2" :messages="$errors->get('designation')" />
            </div>

            <div>
                <x-input-label for="workplace" :value="__('Workplace')" />
                <x-text-input id="workplace" name="workplace" type="text" class="mt-1 block w-full" :value="old('workplace', $user->workplace)" />
                <x-input-error class="mt-2" :messages="$errors->get('workplace')" />
            </div>

            <div>
                <x-input-label for="appointment_date" :value="__('Appointment Date')" />
                <x-text-input id="appointment_date" name="appointment_date" type="date" class="mt-1 block w-full" :value="old('appointment_date', $user->appointment_date)" />
                <x-input-error class="mt-2" :messages="$errors->get('appointment_date')" />
            </div>
        </div>

        <hr class="border-gray-200">
        <h3 class="text-md font-bold text-gray-700 uppercase tracking-wider">Contact Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" placeholder="10 Digits" />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="address" :value="__('Permanent Address')" />
                <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
