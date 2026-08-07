import { Hero } from "@/components/sections/hero";
import { About } from "@/components/sections/about";
import { Clients } from "@/components/sections/clients";
import { Projects } from "@/components/sections/projects";
import { Services } from "@/components/sections/services";
import { Process } from "@/components/sections/process";
import { Precision } from "@/components/sections/precision";
import { InquirySection } from "@/components/sections/inquiry-section";
import { Footer } from "@/components/sections/footer";

export default function Home() {
  return (
    <>
      <main>
        <Hero />
        <About />
        <Clients />
        <Projects />
        <Services />
        <Process />
        <Precision />
        <InquirySection />
      </main>
      <Footer />
    </>
  );
}
