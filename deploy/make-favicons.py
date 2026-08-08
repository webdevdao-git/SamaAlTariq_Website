"""
Generate the favicon set from the Figma logo mark.

Run after replacing public/images/logo-mark.png:
    python3 deploy/make-favicons.py

The mark is near-white (mean luminance 252/255), so it cannot sit on a
transparent favicon — it would vanish against a light tab bar. Every icon here
is therefore the white mark on the brand teal, which also gives the tab a
recognisable colour block at 16px where the shape itself is barely legible.

Apple touch icons are flattened deliberately: iOS composites transparency onto
black, so an RGBA source would come out with black corners.
"""

from PIL import Image

TEAL = (63, 167, 179)          # --color-teal #3FA7B3
SRC = "public/images/logo-mark.png"
PAD = 0.20                      # share of the canvas left as margin around the mark


def render(size: int, background=TEAL) -> Image.Image:
    canvas = Image.new("RGBA", (size, size), (*background, 255))
    mark = Image.open(SRC).convert("RGBA")

    inner = int(size * (1 - PAD * 2))
    ratio = min(inner / mark.width, inner / mark.height)
    mark = mark.resize((max(1, int(mark.width * ratio)),
                        max(1, int(mark.height * ratio))), Image.LANCZOS)

    canvas.alpha_composite(mark, ((size - mark.width) // 2, (size - mark.height) // 2))
    return canvas


# Multi-resolution .ico — the legacy fallback browsers request by default.
render(64).save("public/favicon.ico", sizes=[(16, 16), (32, 32), (48, 48)])

for size in (32, 180, 192, 512):
    name = "apple-touch-icon" if size == 180 else f"icon-{size}"
    img = render(size)
    if size == 180:
        img = img.convert("RGB")   # iOS composites alpha onto black
    img.save(f"public/{name}.png")

print("wrote favicon.ico, icon-32/192/512.png, apple-touch-icon.png")
