// tweaks-app.jsx — Wakaf Bersama MU

const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{
  "accent": "teal",
  "showJejak": true,
  "showApresi": true,
  "ctaLabel": "Saya Ingin Berwakaf"
}/*EDITMODE-END*/;

function TweaksApp(){
  const [t, setTweak] = useTweaks(TWEAK_DEFAULTS);

  // Swap the secondary accent (used in eyebrow, pillar p1, info icons, steps, closing mosque)
  React.useEffect(()=>{
    const map = {
      teal:   { primary: '#1e9c9f', deep: '#147376', soft: '#e0f3f3' },
      blue:   { primary: '#317dc0', deep: '#235a8a', soft: '#e6f0fa' },
      green:  { primary: '#3a8a4d', deep: '#246934', soft: '#dfeede' },
      mauve:  { primary: '#8b5fbf', deep: '#5e3d8a', soft: '#ede5f6' },
    };
    const c = map[t.accent] || map.teal;
    const r = document.documentElement;
    r.style.setProperty('--teal',      c.primary);
    r.style.setProperty('--teal-deep', c.deep);
    r.style.setProperty('--teal-soft', c.soft);
  }, [t.accent]);

  React.useEffect(()=>{
    const j = document.querySelector('.jejak');
    if(j) j.style.display = t.showJejak ? '' : 'none';
  }, [t.showJejak]);

  React.useEffect(()=>{
    const a = document.querySelector('.apresi-grid');
    if(a) a.style.display = t.showApresi ? '' : 'none';
  }, [t.showApresi]);

  React.useEffect(()=>{
    // Update hero CTA + nav CTA labels (preserve trailing arrow)
    const targets = document.querySelectorAll('.hero-actions .btn-primary, .nav-cta');
    targets.forEach(el => {
      const arrow = el.textContent.includes('→') ? ' →' : '';
      el.textContent = (t.ctaLabel || 'Saya Ingin Berwakaf') + arrow;
    });
  }, [t.ctaLabel]);

  return (
    <TweaksPanel title="Tweaks">
      <TweakSection label="Aksen warna" />
      <TweakRadio
        label="Accent"
        value={t.accent}
        options={['teal', 'blue', 'green', 'mauve']}
        onChange={(v)=>setTweak('accent', v)}
      />

      <TweakSection label="Section" />
      <TweakToggle
        label="Jejak Sholat Ied"
        value={t.showJejak}
        onChange={(v)=>setTweak('showJejak', v)}
      />
      <TweakToggle
        label="Visual apresiasi fisik"
        value={t.showApresi}
        onChange={(v)=>setTweak('showApresi', v)}
      />

      <TweakSection label="Konten" />
      <TweakText
        label="Label CTA utama"
        value={t.ctaLabel}
        onChange={(v)=>setTweak('ctaLabel', v)}
      />
    </TweaksPanel>
  );
}

const root = ReactDOM.createRoot(document.getElementById('tweaks-root'));
root.render(<TweaksApp />);
