import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { ArrowRightPill } from "@/components/icons";
import { projects, type Project } from "@/content/site";

/**
 * Figma: frame 1219:209, 1728×2078.
 * Two-line display heading on the 80px gutter, then an asymmetric grid that
 * runs to a 24px gutter: a tall 992px hero card beside two stacked cards, and
 * a second row of two equal cards. Captions sit under each image as
 * title / category on one justified line.
 */
function ProjectCard({
  project,
  ratio,
  priority = false,
  sizes,
}: {
  project: Project;
  ratio: string;
  priority?: boolean;
  sizes: string;
}) {
  return (
    <figure className="group flex h-full flex-col gap-3">
      <div
        className="relative w-full flex-1 overflow-hidden bg-white"
        style={{ aspectRatio: ratio }}
      >
        <Image
          src={project.image}
          alt={project.title}
          fill
          priority={priority}
          sizes={sizes}
          className="object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.04]"
        />
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
        <Reveal as="h2" className="display text-fluid-section uppercase text-ink">
          {projects.heading.map((line) => (
            <span key={line} className="block">
              {line}
            </span>
          ))}
        </Reveal>
      </div>

      <div className="shell-flush mt-[clamp(2rem,3.4vw,58px)]">
        <Reveal className="grid gap-6 lg:grid-cols-[992fr_660fr]">
          <ProjectCard
            project={hero}
            ratio="992 / 727"
            sizes="(max-width: 1024px) 100vw, 58vw"
          />
          <div className="grid gap-6 lg:grid-rows-2">
            <ProjectCard
              project={upperRight}
              ratio="660 / 333"
              sizes="(max-width: 1024px) 100vw, 39vw"
            />
            <ProjectCard
              project={lowerRight}
              ratio="660 / 333"
              sizes="(max-width: 1024px) 100vw, 39vw"
            />
          </div>
        </Reveal>

        <Reveal className="mt-6 grid gap-6 lg:grid-cols-2">
          <ProjectCard
            project={wasl}
            ratio="826 / 637"
            sizes="(max-width: 1024px) 100vw, 49vw"
          />
          <ProjectCard
            project={emirates}
            ratio="826 / 635"
            sizes="(max-width: 1024px) 100vw, 49vw"
          />
        </Reveal>
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
