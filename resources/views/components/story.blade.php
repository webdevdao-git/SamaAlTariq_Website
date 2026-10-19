@props(['bgs' => []])

{{--
    The held-photograph layout the landing page uses (sections/story), for any
    page: one photograph full-screen behind white cards, changing as each
    window (<x-story-win>) arrives. `bgs` maps each window's key to its
    photograph, in page order. motion/story-backdrop.js runs it.
--}}
<div class="story" data-story>
    <div class="story__bd" aria-hidden="true">
        @foreach ($bgs as $key => $src)
            <div class="story__bg {{ $loop->first ? 'is-on' : '' }}" data-story-bg="{{ $key }}">
                <img src="{{ \App\Support\Asset::versioned($src) }}" alt="" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
            </div>
        @endforeach
        <div class="story__shade"></div>
    </div>

    <div class="story__flow">
        {{ $slot }}
    </div>
</div>
