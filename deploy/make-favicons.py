"""
Generate the favicon set from the Figma logo mark.

Run after replacing public/images/logo-mark.png:
    python3 deploy/make-favicons.py

The source mark is white (mean luminance 252/255) and is recoloured to brand
teal here — a white mark is invisible on a light tab bar, which is why the site
showed no icon at all before.

Two decisions worth knowing about:

1. The 16px frame uses a filled silhouette, not the real mark. The logo is built
   from ~20 fine horizontal strokes; below about 24px they merge into a pale
   blur that reads as nothing. Filling each column from its top edge to its
   bottom keeps the distinctive peaked-building outline and stays legible. 32px
   and above use the artwork as drawn.

2. Backgrounds differ by purpose:
     favicon.ico, icon-32   transparent — the tab supplies its own ground, and
                            teal holds up against light and dark chrome alike
     apple-touch-icon       opaque white; iOS composites alpha onto black
     icon-192 / icon-512    opaque white; Android masks launcher icons and
                            places them on arbitrary wallpaper
"""

from PIL import Image

TEAL = (63, 167, 179)          # --color-teal #3FA7B3
SRC = "public/images/logo-mark.png"
SILHOUETTE_BELOW = 24          # px; under this the real strokes stop resolving


def tinted(solid: bool) -> Image.Image:
    """The mark in teal — either as drawn, or filled to a solid silhouette."""
    source = Image.open(SRC).convert("RGBA")
    alpha = source.getchannel("A")

    if solid:
        width, height = source.size
        read = alpha.load()
        filled = Image.new("L", source.size, 0)
        write = filled.load()

        for x in range(width):
            column = [y for y in range(height) if read[x, y] > 60]
            for y in range(column[0], column[-1] + 1) if column else ():
                write[x, y] = 255

        alpha = filled

    art = Image.new("RGBA", source.size, (*TEAL, 0))
    art.putalpha(alpha)
    return art


def render(size: int, pad: float, opaque: bool = False) -> Image.Image:
    canvas = Image.new("RGBA", (size, size),
                       (255, 255, 255, 255) if opaque else (0, 0, 0, 0))

    art = tinted(solid=size < SILHOUETTE_BELOW)
    inner = int(size * (1 - pad * 2))
    ratio = min(inner / art.width, inner / art.height)
    art = art.resize((max(1, int(art.width * ratio)),
                      max(1, int(art.height * ratio))), Image.LANCZOS)

    canvas.alpha_composite(art, ((size - art.width) // 2, (size - art.height) // 2))
    return canvas


# Tab icons — transparent, minimal padding so 16px keeps every pixel it can.
# Each .ico frame is rendered at its own size, so 16px picks up the silhouette.
frames = [render(s, pad=0.06) for s in (16, 32, 48)]
frames[2].save("public/favicon.ico", sizes=[(16, 16), (32, 32), (48, 48)],
               append_images=frames[:2])
render(32, pad=0.06).save("public/icon-32.png")

# App icons — opaque white, roomier padding to sit inside a launcher mask.
render(180, pad=0.16, opaque=True).convert("RGB").save("public/apple-touch-icon.png")
for size in (192, 512):
    render(size, pad=0.16, opaque=True).convert("RGB").save(f"public/icon-{size}.png")

print("wrote favicon.ico (16 silhouette / 32 / 48), icon-32/192/512.png, apple-touch-icon.png")
