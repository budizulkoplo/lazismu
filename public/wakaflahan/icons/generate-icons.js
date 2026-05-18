// Generate simple SVG-based PNG icons for PWA
// Uses built-in Node.js to create SVG files that work as icons

const fs = require('fs');
const path = require('path');

const sizes = [72, 96, 128, 144, 152, 192, 384, 512];

function generateSVG(size) {
  const cx = size / 2;
  const cy = size * 0.43;
  const r = size * 0.19;
  const innerR = r * 0.5;
  const fontSize = size * 0.1;
  const rayDist = r + size * 0.04;
  const rayLen = size * 0.04;
  const strokeW = size * 0.015;

  let rays = '';
  for (let i = 0; i < 8; i++) {
    const angle = (i * Math.PI * 2) / 8 - Math.PI / 2;
    const x1 = cx + Math.cos(angle) * rayDist;
    const y1 = cy + Math.sin(angle) * rayDist;
    const x2 = cx + Math.cos(angle) * (rayDist + rayLen);
    const y2 = cy + Math.sin(angle) * (rayDist + rayLen);
    rays += `<line x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}" stroke="rgba(255,255,255,0.5)" stroke-width="${strokeW}" stroke-linecap="round"/>`;
  }

  // Mosque dome path
  const domeX1 = size * 0.25;
  const domeY = size * 0.69;
  const domeCtrlY = size * 0.55;
  const domeX2 = size * 0.75;
  const domeBottom = size * 0.76;

  return `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 ${size} ${size}">
  <rect width="${size}" height="${size}" fill="#317dc0"/>
  <circle cx="${cx}" cy="${cy}" r="${r}" fill="#f1b53c"/>
  <circle cx="${cx}" cy="${cy}" r="${innerR}" fill="#ffffff"/>
  ${rays}
  <path d="M${domeX1},${domeY} Q${cx},${domeCtrlY} ${domeX2},${domeY} L${domeX2},${domeBottom} L${domeX1},${domeBottom} Z" fill="#1e9c9f"/>
  <text x="${cx}" y="${size * 0.89}" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" font-family="sans-serif" font-size="${fontSize}" font-weight="bold">WBM</text>
</svg>`;
}

const iconsDir = __dirname;

sizes.forEach(size => {
  const svg = generateSVG(size);
  const filename = path.join(iconsDir, `icon-${size}x${size}.svg`);
  fs.writeFileSync(filename, svg);
  console.log(`Generated: icon-${size}x${size}.svg`);
});

console.log('\nAll SVG icons generated!');
console.log('Note: SVGs work as PWA icons in modern browsers.');
