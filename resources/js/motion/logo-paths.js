/**
 * The Sama Al Tariq mark as vector paths, traced from public/images/logo-mark.png
 * (540x462): six bands, top to bottom, each the outline of one stroke of the
 * building. Generated from the PNG's alpha edges and simplified to straight
 * runs — regenerate if the mark changes.
 *
 * `origin` is the middle of the thickest run of the top band (the peak), the
 * one place in the mark solid enough to fly a camera into.
 */
export const LOGO = {
    width: 540,
    height: 462,
    origin: { x: 395, y: 35.4 },
    paths: [
        "M5,209.9 L142,209.3 L395,3.9 L539,198.1 L539,213.5 L433,102.5 L395,66.9 L224,175.7 L141,230.9 L5,231.0 Z",
        "M5,261.2 L143,260.8 L321,144.3 L395,99.2 L539,249.0 L539,266.6 L427,167.0 L396,141.9 L141,282.7 L5,282.6 Z",
        "M5,312.5 L144,312.0 L395,172.8 L433,204.4 L539,300.5 L539,320.3 L427,235.7 L396,214.8 L216,297.4 L142,333.7 L5,333.9 Z",
        "M5,361.3 L143,360.5 L395,245.5 L427,267.0 L539,354.7 L539,371.9 L479,338.3 L396,296.4 L141,377.4 L5,377.3 Z",
        "M5,405.1 L142,404.8 L395,326.9 L446,351.3 L539,401.5 L539,417.7 L396,366.3 L141,421.6 L5,421.4 Z",
        "M5,447.5 L149,447.0 L395,397.7 L415,402.6 L539,445.0 L539,458.8 L396,437.5 L154,459.1 L5,458.9 Z"
],
};
