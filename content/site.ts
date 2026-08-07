/**
 * Single source of truth for every string and image on the landing page.
 * Copy is transcribed verbatim from the Figma file
 * "Sama Al Tariq — Landing Page redesign" (node 50:2119 / frame 1195:2).
 *
 * Anything marked PLACEHOLDER was not present in the Figma file and needs
 * client sign-off — see README "Content still to confirm".
 */

export const site = {
  name: "Sama Al Tariq",
  legalName: "Sama Al Tariq Building Contracting L.L.C.",
  tagline: "Building Contracting LLC.",
  copyright: "© 2026 Sama Al Tariq Building Contracting L.L.C.",
} as const;

export const nav = [
  { label: "Home", href: "#top" },
  { label: "About", href: "#about" },
  { label: "Projects", href: "#projects" },
  { label: "Services", href: "#services" },
  { label: "Process", href: "#process" },
  { label: "Contact", href: "#contact" },
] as const;

export const social = [
  { label: "Instagram", href: "https://instagram.com" }, // PLACEHOLDER url
  { label: "Facebook", href: "https://facebook.com" }, // PLACEHOLDER url
  { label: "LinkedIn", href: "https://linkedin.com" }, // PLACEHOLDER url
] as const;

export const hero = {
  eyebrow: "Delivering Quality Since 2023",
  intro:
    "We deliver exceptional construction, engineering, and contracting solutions that shape modern communities through innovation and uncompromising standards.",
  cta: { label: "Explore Projects", href: "#projects" },
  words: { first: "Building", second: "With Precision", third: "Future" },
  image: "/images/hero.webp",
} as const;

export const about = {
  label: "Who We Are",
  heading: [
    "We build with precision,",
    "delivering structures that stand as",
    "lasting symbols of quality and trust.",
  ],
  image: "/images/about-interior.webp",
  subheading: ["Redefining", "Construction Excellence"],
  body: [
    "Every successful structure begins with thoughtful planning, expert engineering, and flawless execution. These principles guide every decision we make and every project we deliver.",
    "By combining technical expertise with innovative construction practices, Sama Al Tariq create developments that stand the test of time.",
  ],
  stats: [
    { value: "03+", label: "Years of Expertise" },
    { value: "250k+", label: "m² Delivered" },
    { value: "12+", label: "Completed Projects" },
  ],
} as const;

export const clients = {
  label: ["Companies We've", "Worked With"],
  logos: [
    { name: "Dusit Thani Abu Dhabi", src: "/images/clients/dusit-thani.webp", width: 600, height: 279 },
    { name: "archcorp", src: "/images/clients/archcorp.webp", width: 400, height: 120 },
    { name: "ALEC", src: "/images/clients/alec.webp", width: 600, height: 270 },
    { name: "bluecamel", src: "/images/clients/bluecamel.webp", width: 600, height: 168 },
    { name: "novomed", src: "/images/clients/novomed.webp", width: 600, height: 102 },
    { name: "Taj Dubai", src: "/images/clients/taj-dubai.webp", width: 500, height: 500 },
  ],
} as const;

export type Project = {
  title: string;
  category: string;
  image: string;
  width: number;
  height: number;
};

export const projects = {
  heading: ["Featured", "Projects"],
  cta: { label: "View All Projects", href: "#contact" },
  items: [
    {
      title: "Jumeirah Golf Estate Villas",
      category: "Luxury Residential",
      image: "/images/projects/jumeirah-golf-estate-villas.webp",
      width: 1500,
      height: 1000,
    },
    {
      title: "Villa PV39 Tilal-Al-Ghaf",
      category: "Luxury Residential",
      image: "/images/projects/villa-pv39-tilal-al-ghaf.webp",
      width: 1200,
      height: 675,
    },
    {
      title: "W Residence, Palm Jumeirah",
      category: "Luxury Residential",
      image: "/images/projects/w-residence-palm-jumeirah.webp",
      width: 1200,
      height: 675,
    },
    {
      title: "WASL Properties HQ",
      category: "Corporate",
      image: "/images/projects/wasl-properties-hq.webp",
      width: 1400,
      height: 788,
    },
    {
      title: "Emirates Hills Villa",
      category: "Luxury Residential",
      image: "/images/projects/emirates-hills-villa.webp",
      width: 1400,
      height: 786,
    },
  ] satisfies Project[],
} as const;

export const services = {
  label: "Our Expertise",
  heading:
    "We Approach every project as a unique opportunity to deliver exceptional engineering and enduring value.",
  cta: { label: "View All Services", href: "#contact" },
  items: [
    {
      tab: "Fit-Out Contracting",
      title: ["Fit Out Contracting", "And Turnkey Solutions"],
      image: "/images/services/fit-out-contracting.webp",
    },
    {
      tab: "Design & Build",
      title: ["Design And Build", "Interior And Architecture"],
      image: "/images/services/design-and-build.webp",
    },
    {
      // PLACEHOLDER title — tab label from Figma, headline not designed
      tab: "Commercial & Office",
      title: ["Commercial And Office", "Fit-Out Delivery"],
      image: "/images/services/commercial-and-office.webp",
    },
    {
      // PLACEHOLDER title
      tab: "Villa Renovation",
      title: ["Villa Renovation", "And Refurbishment"],
      image: "/images/services/villa-renovation.webp",
    },
    {
      // PLACEHOLDER title
      tab: "Custom Joinery",
      title: ["Custom Joinery", "And Bespoke Furniture"],
      image: "/images/services/custom-joinery.webp",
    },
    {
      // PLACEHOLDER title
      tab: "Carpentry & Millwork",
      title: ["Carpentry And Millwork", "Precision Fabrication"],
      image: "/images/services/carpentry-and-millwork.webp",
    },
  ],
} as const;

export const process = {
  heading: ["Our", "Process"],
  steps: [
    {
      number: "01",
      title: "Tender  And Cost Estimation",
      body: "Accurate cost planning and detailed estimates that establish a strong foundation for every successful project.",
      image: "/images/process/tender-and-cost-estimation.webp",
    },
    {
      // PLACEHOLDER step — only step 01 exists in the Figma file
      number: "02",
      title: "Design And Engineering",
      body: "Detailed drawings, material selection, and value engineering that turn an approved budget into a buildable scheme.",
      image: "/images/process/design-and-engineering.webp",
    },
    {
      // PLACEHOLDER step
      number: "03",
      title: "Construction And Fit-Out",
      body: "Coordinated site delivery with dedicated supervision, strict QA checkpoints, and transparent weekly progress reporting.",
      image: "/images/process/construction-and-fit-out.webp",
    },
    {
      // PLACEHOLDER step
      number: "04",
      title: "Handover And Aftercare",
      body: "Snagging, testing and commissioning, full documentation, and a maintenance period that protects the finished asset.",
      image: "/images/process/handover-and-aftercare.webp",
    },
  ],
} as const;

export const precision = {
  word: "Precision",
  heading: "Engineering excellence for projects that define tomorrow.",
  body: "Every project is shaped through thoughtful planning, collaborative expertise, and a commitment to delivering lasting value for our clients.",
  cta: { label: "Book a consultation", href: "#contact" },
  image: "/images/precision.webp",
} as const;

export const inquiry = {
  label: "Inquiries",
  heading: ["Let’s Build the", "Future Together."],
  body: "Every successful development begins with the right partner. Let's discuss your goals and create a solution tailored to your project's unique requirements",
  submit: "Send Inquiry",
  background: "/images/inquiry-bg.webp",
  propertyTypes: [
    "Villa",
    "Apartment",
    "Commercial / Office",
    "Retail",
    "Hospitality",
    "Other",
  ],
} as const;

export const footer = {
  recent: {
    label: "Recently Completed",
    image: "/images/footer-recent.webp",
    href: "#projects",
  },
  wordmark: "Sama Al Tariq",
  wordmarkSub: "Building Contracting LLC.",
} as const;
