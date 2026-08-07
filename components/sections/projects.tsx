import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { Parallax } from "@/components/motion/parallax";
import { SplitLines } from "@/components/motion/split-lines";
import { ArrowRightPill } from "@/components/icons";
import { projects, type Project } from "@/content/site";

/**
 * Figma: frame 1219:209, 1728×2078.
 * Two-line display heading on the 80px gutter, then an asymmetric grid that
 * runs to a 24px gutter: a tall 992px hero card beside two stacked cards, and
 * a second row of two equal cards. Captions sit under each image as
 * title / category on one justified line.
 */
/**
 * `drift` is the parallax sweep for this card. Cards are given different
 * values so the grid breathes as it scrolls rather than moving as one slab —
 * the reference site varies it the same way, by column.
 */
function ProjectCard({
  project,
  ratio,
  priority = false,
  sizes,
  drift = 60,
}: {
  project: Project;
  ratio: string;
  priority?: boolean;
  sizes: string;
  drift?: number;
}) {
  return (
    <figure className="group flex h-full flex-col gap-3">
      <div
        className="relative w-full flex-1 overflow-hidden bg-white"
        style={{ aspectRatio: ratio }}
      >
        {/*
          The image is oversized and drifts inside a clipping frame, so the
          parallax never exposes an edge as the card crosses the viewport.
        */}
        <Parallax distance={drift} className="absolute -inset-y-[8%] inset-x-0">
          <div className="relative h-full w-full">
            <Image
              src={project.image}
              alt={project.title}
              fill
              priority={priority}
              sizes={sizes}
              className="object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.04]"
            />
          </div>
        </Parallax>
      </div>
      <figcaption className="flex items-center justify-between gap-4 text-fluid-body font-semibold">
        <span className="text-ink">{project.title}</span>
        <span className="shrink-0 text-ink-muted">{project.category}</span>
      </figcaption>
    </figure>
  );
}

export function Projects() {
  const [hero, upperRight, lowerRight, wasl, emirates] = projects.items;

  return (
    <section id="projects" className="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
      <div className="shell">
        <h2 className="display text-fluid-section uppercase text-ink">
          {projects.heading.map((line, i) => (
            <SplitLines
              key={line}
              as="span"
              text={line}
              delay={i * 110}
              className="block"
            />
          ))}
        </h2>
      </div>

      {/*
        Reveal is dropped from the grids: it animates the wrapper's transform,
        which would fight the per-card parallax writing to the same property.
        The cards carry their own motion.
      */}
      <div className="shell-flush mt-[clamp(2rem,3.4vw,58px)]">
        <div className="grid gap-6 lg:grid-cols-[992fr_660fr]">
          <ProjectCard
            project={hero}
            ratio="992 / 727"
            sizes="(max-width: 1024px) 100vw, 58vw"
            drift={70}
          />
          <div className="grid gap-6 lg:grid-rows-2">
            <ProjectCard
              project={upperRight}
              ratio="660 / 333"
              sizes="(max-width: 1024px) 100vw, 39vw"
              drift={40}
            />
            <ProjectCard
              project={lowerRight}
              ratio="660 / 333"
              sizes="(max-width: 1024px) 100vw, 39vw"
              drift={55}
            />
          </div>
        </div>

        <div className="mt-6 grid gap-6 lg:grid-cols-2">
          <ProjectCard
            project={wasl}
            ratio="826 / 637"
            sizes="(max-width: 1024px) 100vw, 49vw"
            drift={65}
          />
          <ProjectCard
            project={emirates}
            ratio="826 / 635"
            sizes="(max-width: 1024px) 100vw, 49vw"
            drift={45}
          />
        </div>
      </div>

      <Reveal className="mt-[clamp(2.5rem,4.5vw,78px)] flex justify-center">
        <a href={projects.cta.href} className="pill group">
          {projects.cta.label}
          <ArrowRightPill className="transition-transform duration-300 group-hover:translate-x-0.5" />
        </a>
      </Reveal>
    </section>
  );
}
