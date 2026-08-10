{{--
    Every tab icon on the site, in one place.

    Regenerate the files with deploy/make-favicons.py after changing
    public/images/logo-mark.png.

    The ?v= is the file's mtime. Hostinger's CDN caches these for seven days
    (max-age=604800), so without it a replaced icon keeps serving the old one
    for a week. Changing the file changes the URL, so the CDN treats it as a new
    resource.

    This lives in one component rather than in each layout because the version
    was the thing that drifted: the public site cache-busted and the portal,
    admin and auth layouts did not, so a signed-in tab kept the previous mark
    while the marketing site showed the new one.
--}}
@php($v = fn (string $file) => asset($file).'?v='.(@filemtime(public_path($file)) ?: 1))

<link rel="icon" href="{{ $v('favicon.ico') }}" sizes="48x48">
<link rel="icon" type="image/png" href="{{ $v('icon-32.png') }}" sizes="32x32">
<link rel="apple-touch-icon" href="{{ $v('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ $v('site.webmanifest') }}">
