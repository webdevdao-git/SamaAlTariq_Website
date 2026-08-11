@php($inquiry = config('site.inquiry'))

{{--
    Figma: frame 1226:955, 1728×980.
    A full-bleed interior photo with a white card (1466 wide, 40px radius,
    64px padding) floating over it: copy column on the left with the copyright
    pinned to its bottom, form on the right.

    The photo is sized by the section, so the section carries a viewport
    min-height: without it the section is only as tall as the card plus its
    padding and the photo reads as a band rather than a backdrop. min-h with
    centred content rather than a fixed height — a viewport shorter than the
    card (or a card grown by validation errors) pushes the section taller
    instead of overflowing it. svh, not vh, so mobile browser chrome does not
    make it overshoot.

    The card keeps its own max-width, so none of this changes its size.

    The form is a plain POST with CSRF and server-side validation. It works
    without JavaScript — old input and errors come back from the session.
--}}
<section id="contact"
         class="relative isolate flex min-h-[100svh] items-center border-t border-hairline px-[clamp(1rem,4.63vw,80px)] py-[clamp(2rem,3.4vw,58px)]">
    <img src="{{ asset($inquiry['background']) }}" alt="" loading="lazy" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">

    <div class="reveal mx-auto w-full max-w-[1466px] rounded-[clamp(20px,2.31vw,40px)] bg-white p-[clamp(1.25rem,2.78vw,48px)] shadow-[0_30px_80px_-40px_rgba(0,0,0,0.35)]">
        <div class="flex flex-col gap-[clamp(2rem,4vw,68px)] lg:flex-row">
            <div class="flex w-full flex-col justify-between gap-[clamp(1.5rem,3vw,52px)] lg:w-[548px] lg:shrink-0">
                <div class="flex flex-col gap-[clamp(0.875rem,1.5vw,26px)]">
                    <p class="text-fluid-label font-medium text-teal">{{ $inquiry['label'] }}</p>
                    <h2 class="display max-w-[444px] text-fluid-h2 leading-[1.3] text-ink">
                        @foreach ($inquiry['heading'] as $line)
                            <span class="inline sm:block">{{ $line }} </span>
                        @endforeach
                    </h2>
                    <p class="text-fluid-body font-medium text-ink-muted">{{ $inquiry['body'] }}</p>
                </div>

                <p class="text-fluid-body font-medium text-ink">{{ config('site.copyright') }}</p>
            </div>

            <form method="POST" action="{{ route('enquiries.store') }}"
                  class="flex flex-1 flex-col gap-[clamp(1.15rem,2vw,34px)]">
                @csrf

                <div class="grid gap-[clamp(1.15rem,2vw,34px)] sm:grid-cols-2 sm:gap-x-[clamp(1rem,1.29vw,22px)]">
                    <div>
                        <label for="name" class="field-label">Name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               value="{{ old('name') }}" class="field"
                               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name')<span id="name-error" class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="phone" class="field-label">Phone Number</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" required
                               value="{{ old('phone') }}" class="field"
                               @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                        @error('phone')<span id="phone-error" class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="email" class="field-label">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}" class="field"
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<span id="email-error" class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    {{-- The select sits on the row its name is written on, so the row that
                         looks like the control is the control. It read as a label before,
                         and clicking a label focuses a select without opening it — the
                         field took focus, drew its teal underline and never showed a menu.

                         The name is therefore the placeholder option rather than a <label>,
                         and it is replaced by the chosen value in place. The real label is
                         sr-only: a placeholder is not an accessible name, and this one stops
                         being visible as soon as someone chooses.

                         The filler below is the empty control row the other fields show; see
                         .field-select for how the two stacks end up the same height. --}}
                    {{-- Label above, control below — the same two rows every other field
                         here has, so "Property Type" reads as a heading like "Email" and
                         "Select" sits on the line you actually operate, with the chevron at
                         the end of that line. The chevron belongs to the control, not to
                         the heading.

                         The native select is still the control: it holds the value, it is
                         what the form submits, and it is what a browser without JavaScript
                         shows. The button and menu are hidden until the script marks this
                         wrapper, so nothing here depends on JavaScript to work — only to
                         look like the rest of the page.

                         "Select" is the empty option and the button's starting text. It is
                         deliberately absent from the menu: it is not a property type, so
                         offering it would let someone choose their way back to an empty
                         required field. --}}
                    <div>
                        <label for="project_type" id="project_type-label" class="field-label">Property Type</label>

                        <div class="field-select" data-select>
                            {{-- Wraps the control alone, so it is exactly one row tall and
                                 the chevron centres on that row. pointer-events-none lets
                                 the click through to whichever control is beneath, so the
                                 chevron opens the menu like the rest of the row. One chevron
                                 serves both the native select and the button. --}}
                            <div class="relative">
                                <select id="project_type" name="project_type" required
                                        class="field-select__native" data-select-native
                                        @error('project_type') aria-invalid="true" aria-describedby="project_type-error" @enderror>
                                    <option value="">Select</option>
                                    @foreach ($inquiry['property_types'] as $type)
                                        <option value="{{ $type }}" @selected(old('project_type') === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>

                                <button type="button" class="field-select__button"
                                        id="project_type-button" data-select-button
                                        role="combobox" aria-haspopup="listbox" aria-expanded="false"
                                        aria-controls="project_type-listbox"
                                        aria-labelledby="project_type-label project_type-button">
                                    <span data-select-value>Select</span>
                                </button>

                                <span aria-hidden="true"
                                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center text-ink">
                                    <x-icon name="chevron-down" class="field-select__chevron"/>
                                </span>
                            </div>

                            <ul class="field-select__menu" id="project_type-listbox" role="listbox"
                                data-select-listbox aria-labelledby="project_type-label" hidden>
                                @foreach ($inquiry['property_types'] as $i => $type)
                                    <li class="field-select__option" role="option" aria-selected="false"
                                        id="project_type-option-{{ $i }}" data-value="{{ $type }}">{{ $type }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @error('project_type')<span id="project_type-error" class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label for="location" class="field-label">Project Location in Dubai</label>
                    <input id="location" name="location" type="text" value="{{ old('location') }}" class="field"
                           @error('location') aria-invalid="true" aria-describedby="location-error" @enderror>
                    @error('location')<span id="location-error" class="field-error" role="alert">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="project_brief" class="field-label">Brief Project Description</label>
                    <textarea id="project_brief" name="project_brief" rows="2" class="field resize-none"
                              @error('project_brief') aria-invalid="true" aria-describedby="project_brief-error" @enderror>{{ old('project_brief') }}</textarea>
                    @error('project_brief')<span id="project_brief-error" class="field-error" role="alert">{{ $message }}</span>@enderror
                </div>

                {{-- Honeypot: off-screen and hidden from assistive tech, irresistible to bots --}}
                <div aria-hidden="true" class="absolute left-[-9999px] h-0 w-0 overflow-hidden">
                    <label for="company">Company</label>
                    <input id="company" name="company" type="text" tabindex="-1" autocomplete="off" value="">
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <button type="submit" class="pill group">
                        {{ $inquiry['submit'] }}
                        <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </button>

                    @if (session('enquiry_status'))
                        <p role="status" class="text-fluid-sm text-teal">{{ session('enquiry_status') }}</p>
                    @elseif ($errors->any())
                        <p role="alert" class="text-fluid-sm text-[#c0392b]">Please check the highlighted fields.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
