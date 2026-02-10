<template>
  <div class="page">
    <header class="hero">
      <div class="hero-copy">
        <p class="eyebrow">Interactive Science Platform</p>
        <h1>
          Virtual <span class="accent">Lab</span>
        </h1>
        <p class="subhead">
          Run experiments, watch guided demonstrations, and learn by doing.
          Built for clarity, speed, and real understanding.
        </p>
        <div class="cta">
          <NuxtLink to="/login" class="btn primary">Login</NuxtLink>
          <NuxtLink to="/experiments" class="btn ghost">Explore Labs</NuxtLink>
        </div>
        <div class="quick-links">
          <NuxtLink to="/service">Services</NuxtLink>
          <span>•</span>
          <NuxtLink to="/contact">Contact</NuxtLink>
        </div>
      </div>
      <div class="hero-card">
        <div class="card-top">
          <span class="chip">Featured</span>
          <span class="chip ghost">Physics</span>
        </div>
        <h3>Ohm’s Law</h3>
        <p>Visualize voltage, current, and resistance with guided steps.</p>
        <div class="card-meta">
          <span>8 min</span>
          <span>Beginner</span>
        </div>
      </div>
    </header>

    <section class="grid">
      <div class="panel">
        <h4>Guided Experiments</h4>
        <p>Step-by-step procedures that mirror real lab workflows.</p>
      </div>
      <div class="panel">
        <h4>Video Demonstrations</h4>
        <p>Stream optimized HLS lessons with clear narration.</p>
      </div>
      <div class="panel">
        <h4>Structured Outcomes</h4>
        <p>Track objectives, observations, and results in one place.</p>
      </div>
    </section>

    <section class="cta-band">
      <div>
        <h2>Start your next experiment in minutes</h2>
        <p>Access labs from anywhere with a single login.</p>
      </div>
      <NuxtLink to="/login" class="btn dark">Get Started</NuxtLink>
    </section>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Sans:wght@400;500&display=swap');

:global(body) {
  background: #0b1020;
}

.page {
  color: #e5e7eb;
  min-height: 100vh;
  padding: 48px 20px 72px;
  background:
    radial-gradient(900px 500px at 10% -10%, rgba(99, 102, 241, 0.25), transparent 60%),
    radial-gradient(700px 450px at 90% 10%, rgba(14, 165, 233, 0.2), transparent 60%),
    linear-gradient(180deg, #0b1020 0%, #0b1226 100%);
}

.hero {
  max-width: 1100px;
  margin: 0 auto 48px;
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 32px;
  align-items: center;
}

.hero-copy h1 {
  font-family: "Space Grotesk", sans-serif;
  font-size: 48px;
  line-height: 1.05;
  margin: 10px 0 14px;
}

.eyebrow {
  font-family: "IBM Plex Sans", sans-serif;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  font-size: 12px;
  color: #93c5fd;
}

.accent {
  color: #7dd3fc;
}

.subhead {
  font-family: "IBM Plex Sans", sans-serif;
  font-size: 16px;
  color: #cbd5f5;
  max-width: 520px;
}

.cta {
  margin-top: 20px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.btn {
  padding: 10px 18px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  font-family: "IBM Plex Sans", sans-serif;
  border: 1px solid transparent;
}

.btn.primary {
  background: #38bdf8;
  color: #0b1020;
}

.btn.ghost {
  background: transparent;
  color: #e5e7eb;
  border-color: #334155;
}

.btn.dark {
  background: #0f172a;
  color: #e5e7eb;
  border: 1px solid #334155;
}

.quick-links {
  margin-top: 16px;
  font-size: 14px;
  color: #94a3b8;
}

.quick-links a {
  color: #cbd5f5;
  text-decoration: none;
}

.hero-card {
  background: #0f172a;
  border: 1px solid #1e293b;
  border-radius: 16px;
  padding: 22px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
}

.card-top {
  display: flex;
  gap: 8px;
  margin-bottom: 14px;
}

.chip {
  padding: 4px 8px;
  border-radius: 999px;
  background: #1d4ed8;
  font-size: 12px;
}

.chip.ghost {
  background: #0b1226;
  border: 1px solid #1e293b;
  color: #93c5fd;
}

.hero-card h3 {
  margin: 0 0 6px;
  font-family: "Space Grotesk", sans-serif;
}

.hero-card p {
  color: #94a3b8;
  margin: 0 0 18px;
  font-size: 14px;
}

.card-meta {
  display: flex;
  gap: 12px;
  font-size: 12px;
  color: #94a3b8;
}

.grid {
  max-width: 1100px;
  margin: 0 auto 40px;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.panel {
  background: #0f172a;
  border: 1px solid #1e293b;
  border-radius: 14px;
  padding: 18px;
}

.panel h4 {
  margin: 0 0 6px;
  font-family: "Space Grotesk", sans-serif;
}

.panel p {
  margin: 0;
  color: #94a3b8;
  font-size: 14px;
}

.cta-band {
  max-width: 1100px;
  margin: 0 auto;
  background: linear-gradient(90deg, #0ea5e9, #1d4ed8);
  border-radius: 16px;
  padding: 22px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.cta-band h2 {
  margin: 0 0 6px;
  font-family: "Space Grotesk", sans-serif;
}

.cta-band p {
  margin: 0;
  color: #e0f2fe;
}

@media (max-width: 900px) {
  .hero {
    grid-template-columns: 1fr;
  }
  .grid {
    grid-template-columns: 1fr;
  }
  .cta-band {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
